<?php
if (!defined('APPLICATION'))
    exit();
?><h1><? echo $this->Project->Title; ?></h1>
<h2 class="page-subheader">Task Tracker</h2>
<div id="TaskFormBox" style="display:none;"><?
    $TypeChoices = array(1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');

    echo $this->Form->Open();
    echo $this->Form->Errors();
    ?><div class="row">
        <div class="column type"><?
    echo $this->Form->DropDown("Type", $TypeChoices);
    ?></div>
        <div class="column title"><?
            echo $this->Form->TextBox("Title", array("value" => "Title"));
            ?></div>
        <div class="column due"><?
            echo $this->Form->TextBox("FakeDate");
            echo $this->Form->TextBox("DateDue", array("style" => "display:none;"));
            ?></div>
        <div class="column description"><?
            echo $this->Form->TextBox("Description", array("value" => "Description"));
            ?></div>
        <div class="column save"><?
            echo $this->Form->Close("Save");
            if ($this->Editing == 1) {
                echo $this->Form->Button("Submit", array('value' => 'Cancel'));
            }
            ?></div>
    </div><?
            ?></div>
<div class='taskContent'>
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

</div>
<h2>Today</h2>
<div id="accordionToday" class="TaskList"><?
            /* ------------------------------ Task List ----------------------------- */
// Clear the number of top-level tasks
            $ParentCount = 0;
// Start loop for parents
            foreach ($this->_TodayTasks as $Task) {
                include PATH_APPLICATIONS . "/mocha/views/project/tasklist.php";
            }
            if ($ParentCount < 0) {
                ?><div class='group'>
                    <h5>
                        <div class='TaskType'></div>
                        <span class='Title'>No Tasks Due Today</span>
                    </h5>
                </div><?
            }
            ?></div>
<h2>Overdue</h2>
<div id="accordionOverdue" class="TaskList"><?
            /* ------------------------------ Task List ----------------------------- */
// Clear the number of top-level tasks
// Start loop for parents
            foreach ($this->_OverdueTasks as $Task) {
                include PATH_APPLICATIONS . "/mocha/views/project/tasklist.php";
            }
            ?></div>
<h2>Future</h2>
<div id="accordionFuture" class="TaskList"><?
            /* ------------------------------ Task List ----------------------------- */
// Start loop for parents
            foreach ($this->_FutureTasks as $Task) {
                include PATH_APPLICATIONS . "/mocha/views/project/tasklist.php";
            }
            ?></div>
<div id="JsInfo" 
     style="display:none"  
     projectID="<? echo $this->ViewingProjectID ?>" 
     userTimestamp="<? echo $this->UserTimestamp ?>" 
     userID="<? echo $this->ViewingUserID; ?>"
     parentCount="<? echo $ParentCount; ?>">
</div>

