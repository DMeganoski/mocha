$(document).ready(function() {

    // Create custom functions to refresh the list when a task is deleted.
    userID = $('#JsInfo').attr('userid');
    projectID = $('#JsInfo').attr('projectid');
    userTimestamp = $('#JsInfo').attr('usertimestamp');
    console.log(userTimestamp);


    /*jQuery.fn.updateTasks = function(timestamp) {
     $.post("index.php?p=/task/gettasks", {UserID: userID, ProjectID: projectID, Timestamp: timestamp},
     function (data) {
     $('div.TaskList').html(data);
     }
     );
     };*/
    
    $.fn.newTask = function(projectID) {
        $.get('index.php?p=/task/create/' + projectID, function(data) {
            $('#TaskFormBox').html(data);
            $('#TaskFormBox').slideDown(300);
        });
    };
    $.fn.editTask = function(taskID) {
        //$.post('index.php?p=/task/edit/', {"TaskID" : taskID}, 
        //function(data) {
            //taskContent.hide();
            $("#content" + taskID).next('#editTask').show();
        //});
    };
    
    /* TODO: Get This working
    $('span.edit').click(function() {
        taskID = $(this).attr('taskid');
        $.post('index.php?/task/edit/', {"ProjectID": projectID, 'TaskID': taskID},
        function(data) {
            $(this).parents('.taskContent').hide().append(html);
        });
    });*/

    $("h1.page-header").append("<div class='pull-right'><button class='Button NewTask' onclick=\"$('#TaskFormBox').newTask(projectID);\"><span class='Sprite SpEditProfile'></span>New Task</button></div>");


    console.log('Script Loaded');
    //$('ul.TaskList').updateTasks(userTimestamp);
    //$.datepicker.formatDate( "yy-mm-dd", new Date( 2007, 1 - 1, 26 ) );
    //$( "#datepicker" ).datepicker();
    $("#datepicker").click(function() {
        $.datepicker().show();
    });

    $('span.Due').each(function() {
        timestamp = $(this).attr('timestamp');
        // Ignore warning, doesn't work with "==="
        if (timestamp < userTimestamp && timestamp > (userTimestamp + (24 * 3600))) {
            $(this).addClass('Today');
            $(this).html('T');
        } else if (timestamp < userTimestamp) {
            $(this).addClass('Overdue');
            $(this).html('O');
        } else if (timestamp > userTimestamp) {
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
                //$('ul.TaskList').updateTasks(userTimestamp);
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

        parentCount = $('#JsInfo').attr('ParentCount');
        for (i = 0; i < (parentCount + 1); i++) {
            $("#accordion" + i).accordion({
                event: "hoverintent",
                header: "> div > h5",
                collapsible: true,
                active: false,
                heightStyle: "content"
            });
        }

    });

});


