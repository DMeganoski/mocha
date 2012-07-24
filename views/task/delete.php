<?php
if (!defined('APPLICATION'))
    exit();
echo $this->Form->Open();
echo $this->Form->Errors();
?><div>
    <h1><? echo T("Confirm"); ?></h1>
    <h3><? echo T("Are You Sure You want to delete the task: "); ?></h3>
    <h3><? echo $Task->Title.T("?"); ?></h3>
</div><?
echo $this->Form->Button("Submit", array('value' => 'Delete'));
echo $this->Form->Button("Submit", array('value' => 'Cancel'));
