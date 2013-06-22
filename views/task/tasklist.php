<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *


  /*
 * Phew. This is one big hot mess. Good luck.
 */
// First boxes displaying totals
?><div class='taskContent'>
    <div class='InfoBoxContainer'>
        <div class='InfoBox'><?
            echo "<h5>Open Tasks: </h5>22";
            ?></div>
        <div class='InfoBox'><?
            echo "<h5>Due Tasks: </h5>4";
            ?></div>
        <div class='InfoBox'><?
            echo "<h5>Deliverables: </h5>1";
            ?></div>
        <div class='InfoBox'><?
            echo "<h5>Milestones: </h5>4";
            ?></div>
    </div>
</div><?
/* ------------------------------ Task List ----------------------------- */

// Clear the number of top-level tasks
$ParentCount = 0;
// Start loop for parents
foreach ($this->_Tasks as $Task) {
    // Add one to the count, used for multiple accordions
    $ParentCount = $ParentCount + 1;
    switch ($Task->Type) {
        case 0: // Nested Task
        case 1: // Unnested Task
            $Class = 'draggable';
            break;
        case 2: // Milestone
        case 3: // Deliverable
            $Class = 'droppable';
            break;
    }
    ?><div id='<? echo $Task->TaskID; ?>' class='task group parent <? echo $Class ?> type<? echo $Task->Type; ?>'>
        <h5 class='Title'>
            <div class='TaskType' src='<? echo PATH_APPLICATIONS . DS; ?>mocha/design/images/tasktype/<? echo $Task->Type; ?>.jpg'></div>
            <span class='Title'><? echo $Task->Title; ?></span>
            <span class='Due' timestamp='<? echo $Task->DueTimestamp; ?>'></span>
        </h5>
        <div class='taskContent'>
            <p class="description"><? echo $Task->Description; ?></p>
            <span class="Options">
                <span class="ToggleFlyout OptionsMenu">
                    <span class="btn btn-mini" title="Options">Options</span>
                    <span class="SpFlyoutHandle"></span>
                    <ul class="Flyout dropdown-menu">
                        <li><a class='edit' taskid="<? echo $Task->TaskID?>">Edit</a></li>
                        <li><a class='delete' href='index.php?p=/task/delete/<? echo $Task->ProjectID . DS . $Task->TaskID; ?>'>Delete</a></li>
                    </ul>
                </span>
            </span>
            <span class='DueDate' timestamp='<? echo $Task->DueTimestamp; ?>'>Due: <? echo $Task->DateDue; ?></span><?
    // Now for child elements, still inside item content (folding) of the parent

    $Children = $this->TaskModel->GetWhere('ParentID', $Task->TaskID);
    if ($this->TaskModel->CountChildren($Task->TaskID) >= 1) {
        ?><div id="accordion<? echo $ParentCount; ?>" class="children"><?
                $ChildrenCount = 0;
                foreach ($Children as $Child) {
                    $ChildrenCount + 1;
                    ?><div id = '<? echo $Child->TaskID; ?>' class = 'task group child draggable type<? echo $Child->Type; ?>'>
                            <h5 class = 'Title'>
                                <div class = 'TaskType' src ='<? echo PATH_APPLICATIONS . DS; ?>mocha/design/images/tasktype/<? echo $Child->Type; ?>.jpg'></div>
                                <span class = 'Title'><? echo $Child->Title; ?></span>
                                <span class='Due' timestamp='<? echo $Child->DueTimestamp; ?>'></span>
                            </h5>
                            <div class='taskContent'>
                                <p class="description"><? echo $Child->Description; ?></p>
                                <span class="Options">
                                    <span class="ToggleFlyout OptionsMenu">
                                        <span class="btn btn-mini" title="Options">Options</span>
                                        <span class="SpFlyoutHandle"></span>
                                        <ul class="Flyout dropdown-menu">
                                            <li><a class='edit' onclick="$(this).editTask(<? echo $Child->TaskID?>)" taskid="<? echo $Child->TaskID?>">Edit</a></li>
                                            <li><a class='delete' href='index.php?p=/task/delete/<? echo $Child->ProjectID . DS . $Child->TaskID; ?>'>Delete</a></li>
                                        </ul>
                                    </span>
                                </span>
                                <span class='DueDate' timestamp='<? echo $Child->DueTimestamp; ?>'>Due: <? echo $Child->DateDue; ?></span>
                            </div>
                        </div><?
        }
                ?></div><?
                    // End Child Box
                } // no children
                ?></div>
    </div><?
        // End of first task box
    }
    $Today = date('Y-m-d');
    $Date = new DateTime($Today);
            ?><script type="text/javascript">
                $(document).ready(function() {
// Create custom functions to refresh the list when a task is deleted.
                    userID = <? echo Gdn::Session()->UserID; ?>;
                    projectID = $('#JsInfo').attr('projectID');
                    today = <? echo $Date->getTimestamp(); ?>;
                    console.log(today);

                    jQuery.fn.updateTasks = function(timeStamp) {
                        $.post("index.php?p=/task/gettasks", {UserID: userID, ProjectID: projectID, TimeStamp: timeStamp},
                        function(data) {
                            $('ul.TaskList').html(data);
                        }
                        );
                    };

                    $('span.Due').each(function() {
                        timestamp = $(this).attr('timestamp');
                        if (timestamp == today) {
                            $(this).addClass('Today');
                            $(this).html('T');
                        } else if (timestamp < today) {
                            $(this).addClass('Overdue');
                            $(this).html('O');
                        } else if (timestamp > today) {
                            $(this).addClass('Future');
                            $(this).html('F');
                        }
                    });
                    $(".draggable").draggable({
                        revert: true,
                        appendTo: "body",
                        helper: "clone"
                    });
                    $(".droppable").droppable({
                        hoverClass: "ui-state-hover",
                        accept: ":not(.ui-sortable-helper)",
                        drop: function(event, ui) {
                            //$( this ).appendTo('<div class="Black">Hey</div>');
                            parentID = $(this).attr("id");
                            childID = ui.draggable.attr("id");
                            ui.draggable.hide();
                            $.post("index.php?p=/task/nesttask", {ParentID: parentID, ChildID: childID},
                            function(data) {
                                document.location.reload();

                                //$(this).find('.children').append($(ui.draggable).clone());
                                //$(ui.draggable).remove();
                                //TODO: figure out how to update task list...
                                //$('ul.TaskList').updateTasks(today);
                            }
                            );
                        }
                    });

                    $(function() {
                        $("#accordion")
                                .accordion({
                            event: "hoverintent",
                            header: "> div > h5",
                            collapsible: true,
                            active: false,
                            heightStyle: "content"
                        });


                        for (i = 0; i <<? echo ($ParentCount + 1); ?>; i++) {
                            $("#accordion" + i).accordion({
                                event: "hoverintent",
                                header: "> div > h5",
                                collapsible: true,
                                active: false,
                                heightStyle: "content"
                            });
                        }

                    });

                    $('span.edit').click(function() {
                        taskID = $(this).attr('taskid');
                        $.post('index.php?/task/edit/', {"ProjectID": projectID, 'TaskID': taskID},
                        function(data) {
                            $(this).parents('.taskContent').hide().append(html);
                        });
                    });

                    /*
                     * hoverIntent | Copyright 2011 Brian Cherne
                     * http://cherne.net/brian/resources/jquery.hoverIntent.html
                     * modified by the jQuery UI team
                     */
                    $.event.special.hoverintent = {
                        setup: function() {
                            $(this).bind("mouseover", jQuery.event.special.hoverintent.handler);
                        },
                        teardown: function() {
                            $(this).unbind("mouseover", jQuery.event.special.hoverintent.handler);
                        },
                        handler: function(event) {
                            var currentX, currentY, timeout,
                                    args = arguments,
                                    target = $(event.target),
                                    previousX = event.pageX,
                                    previousY = event.pageY;
                            function track(event) {
                                currentX = event.pageX;
                                currentY = event.pageY;
                            }
                            ;
                            function clear() {
                                target
                                        .unbind("mousemove", track)
                                        .unbind("mouseout", clear);
                                clearTimeout(timeout);
                            }
                            function handler() {
                                var prop,
                                        orig = event;
                                if ((Math.abs(previousX - currentX) +
                                        Math.abs(previousY - currentY)) < 7) {
                                    clear();
                                    event = $.Event("hoverintent");
                                    for (prop in orig) {
                                        if (!(prop in event)) {
                                            event[ prop ] = orig[ prop ];
                                        }
                                    }
// Prevent accessing the original event since the new event
// is fired asynchronously and the old event is no longer
// usable (#6028)
                                    delete event.originalEvent;
                                    target.trigger(event);
                                } else {
                                    previousX = currentX;
                                    previousY = currentY;
                                    timeout = setTimeout(handler, 200);
                                }
                            }
                            timeout = setTimeout(handler, 200);
                            target.bind({
                                mousemove: track,
                                mouseout: clear
                            });
                        }
                    };

                });

</script>
