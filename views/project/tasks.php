<?php
if (!defined('APPLICATION'))
    exit();
$Date = new DateTime(date("Y-m-d\TH:i:sO"));
$UTCTimestamp = $Date->getTimestamp();
$UserTimestamp = $UTCTimestamp + ($this->UserOffset * 3600);
//$OneDay = new DateInterval("P1D");
//$Date->add($OneDay);
//$TomorrowTimestamp = $Date->getTimestamp();
echo $UTCTimestamp;
echo "<br/>";
echo $UserTimestamp;
?><h1><? echo $this->Project->Title; ?></h1>
<h2 class="page-subheader">Task Tracker</h2>
<div id="TaskFormBox" style="display:none;"></div>
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
<div id="accordion" class="TaskList"><?
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
            <div class='taskContent' id ='content<? echo $Task->TaskID; ?>'><?
                /* -----------------Task Content Box ------------------------ */
                ?><div id='taskEdit' style="display:none"><?
                $Validation = new Gdn_Validation();
                $this->Form = new Gdn_Form(/* $Validation */);

                $this->Form->SetModel($this->TaskModel);
                $this->Form->AddHidden("ProjectID", $this->ViewingProjectID);
                $this->Form->SetData($Task);
                $this->Today = date('Y-m-d');
                $this->Date = new DateTime($this->Today);
                //$this->Form->AddHidden("DateDue", $this->Date);

                if ($this->Form->AuthenticatedPostBack() === FALSE) {
                    $this->Form->SetFormValue("ProjectID", $this->ViewingProjectID);
                } else {
                    $FormValues = $this->Form->FormValues();
                    $Timestamp = Gdn_Format::ToTimestamp($FormValues['DateDue']);
                    $this->Form->AddHidden("DueTimestamp", $Timestamp);
                    $this->Form->SetFormValue("DueTimestamp", $Timestamp);
                    if ($this->Form->Save()) {

                        $this->InformMessage('Task Saved');
                        Redirect('index.php?p=/project/tasks/' . $this->ViewingProjectID);
                    } else {
                        $this->InformMessage('Task Not Saved');
                    }
                }
                include_once(PATH_APPLICATIONS . DS . 'mocha/views/task/create.php');
                ?></div>
                <p class="description"><? echo $Task->Description; ?></p>
                <span class="Options">
                    <span class="ToggleFlyout OptionsMenu">
                        <span class="btn btn-mini" title="Options">Options</span>
                        <span class="SpFlyoutHandle"></span>
                        <ul class="Flyout dropdown-menu">
                            <li><button class='edit<? echo $Task->TaskID ?>' onclick="$('.edit<? echo $Task->TaskID ?>').editTask(<? echo $Task->TaskID; ?>);" taskid="<? echo $Task->TaskID ?>">Edit</a></li>
                            <li><a class='delete' href='index.php?p=/task/delete/<? echo $Task->ProjectID . DS . $Task->TaskID; ?>'>Delete</a></li>
                        </ul>
                    </span>
                </span>
                <span class='DueDate' timestamp='<? echo $Task->DueTimestamp; ?>'>Due: <? echo $Task->DateDue . " - " . $Task->DueTimestamp; ?></span><?
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
                                                <li><a class='edit' onclick="$(this).editTask(<? echo $Child->TaskID ?>)" taskid="<? echo $Child->TaskID ?>">Edit</a></li>
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
    ?>
    <div id="JsInfo" 
         style="display:none"  
         projectID="<? echo $this->ViewingProjectID ?>" 
         userTimestamp="<? echo $UserTimestamp ?>" 
         userID="<? echo $this->ViewingUserID; ?>"
         parentCount="<? echo $ParentCount; ?>"></div>   
