<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *


  /*
 * Then retrieve Milestones
 * Then retrieve Deliverables
 * 
 * Output Deliveralbes, get related tasks for each
 */
?><div class='ItemContent'>
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
foreach ($this->_Tasks as $Task) {

    $Children = $this->TaskModel->GetWhere(array('ProjectID' => $this->ViewingProjectID, 'ParentID' => $Task->TaskID));


    $TypeChoices = array(1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');
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

    echo "<li id='" . $Task->TaskID . "' class='Item " . $Class . " type" . $Task->Type . "'>";
    echo "<div class='ItemContent'>";
    echo "<div class='TaskType' src='" . PATH_APPLICATIONS . DS . "mocha/design/images/tasktype/" . $Task->Type . ".jpg'></div>";
    echo "<span class='Title'>$Task->Title</span>";
    echo "<span class='Due' timestamp='" . $Task->DueTimestamp . "'>Due: $Task->DateDue</span>";
    echo "<span class='Delete'><a class='' href='index.php?p=/task/delete/" . $Task->ProjectID . DS . $Task->TaskID . "'>x</a></span></div>";
    echo "</li>";
    if (!empty($Children)) {
        ?><ol class="Children"><?
            foreach ($Children as $Child) {
                echo "<li id='" . $Child->TaskID . "' class='type" . $Child->Type ." Item Child draggable'>";
                echo "<div class='ItemContent'>";
                echo "<div class='TaskType' src='" . PATH_APPLICATIONS . DS . "mocha/design/images/tasktype/" . $Child->Type . ".jpg'></div>";
                echo "<span class='Title'>$Child->Title</span>";
                echo "<span class='Due' timestamp='" . $Child->DueTimestamp . "'>Due: $Child->DateDue</span>";
                echo "<span class='Delete'><a class='' href='index.php?p=/task/delete/" . $Child->ProjectID . DS . $Child->TaskID . "'>x</a></span>";
            }
            ?></ol><?
    }
}
?><script type="text/javascript">
        $('span.Due').each(function() {
            timestamp = $(this).attr('timestamp');
            if (timestamp <= today) {
                $(this).addClass('Today');
            } else if (timestamp >= today) {
                $(this).addClass('Overdue');
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
                console.log("parentID:" + parentID);
                console.log("childID:" + childID);
                $.post("index.php?p=/task/nesttask", {ParentID: parentID, ChildID: childID},
                function(data) {
                    console.log(data);
                }
                );
            }
        });
</script>


