<?php
if ($this->editing == 1) {
    $TypeChoices = array(1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');
} else {
    $TypeChoices = array(0 => 'Nested', 1 => 'Task', 2 => 'Milestone', 3 => 'Deliverable');

}
if (!defined('APPLICATION'))
    exit();
/*
 * This is the create view for the Task controller.
 * Displays a form for creating a new task.
 */

echo $this->Form->Open();
echo $this->Form->Errors();

?><style type="text/css">
</style><?

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
	echo $this->Form->Close("Save");
    ?></div>
</div>
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script>
    $( "#Form_FakeDate" ).datepicker({ altField: "#Form_DateDue" });
    $( "#Form_FakeDate" ).datepicker( "option", "altFormat", "yy-mm-dd" );
</script>