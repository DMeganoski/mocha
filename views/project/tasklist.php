<?php
if (!defined("APPLICATION"))
    exit;
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
?><p class="description"><? echo $Task->Description; ?></p>
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