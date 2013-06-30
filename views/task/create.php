<?php

if (!defined('APPLICATION'))
    exit();
/*
 * This is the create view for the Task controller.
 * Displays a form for creating a new task.
 */

if ($this->editing == 1) {
    $TypeChoices = array(0 => 'Nested',1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');
} else {
    $TypeChoices = array(1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');

}
echo $this->Form->Open();
echo $this->Form->Errors();

?><div class="row">
    <div class="column type"><?
	echo $this->Form->DropDown("Type", $TypeChoices);
    ?></div>
    <div class="column title"><?
	echo $this->Form->TextBox("Title", array("value"=>"Title"));
    ?></div>
    <div class="column due"><?
	echo $this->Form->TextBox("FakeDate");
        echo $this->Form->TextBox("DateDue", array("style"=>"display:none;"));
    ?></div>
    <div class="column description"><?
	echo $this->Form->TextBox("Description", array("value"=>"Description"));
    ?></div>
    <div class="column save"><?
    echo $this->Form->Button("Submit", array('value' => 'Save'));
    if ($this->Editing == 1) {
    echo $this->Form->Button("Submit", array('value' => 'Cancel'));
    }
    ?></div>
</div><?