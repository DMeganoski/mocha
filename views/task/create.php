<?php

$TypeChoices = array(1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');

if (!defined('APPLICATION'))
    exit();
/*
 * This is the create view for the Task controller.
 * Displays a form for creating a new task.
 */

echo $this->Form->Open();
echo $this->Form->Errors();
?><ul>
    <li><?
	echo $this->Form->Label("Type")
    ?></li>
    <li><?
	echo $this->Form->DropDown("Type", $TypeChoices);
    ?></li>
</ul>
<ul>
    <li><?
	echo $this->Form->Label("Title");
    ?></li>
    <li><?
	echo $this->Form->TextBox("Title");
    ?></li>
</ul>
<ul>
    <li><?
	echo $this->Form->Label("Description");
    ?></li>
    <li><?
	echo $this->Form->TextBox("Description", array('multiline' => TRUE));
    ?></li>
</ul>
<ul>
    <li><?
	echo $this->Form->Close("Save");
    ?></li>
</ul>