<?php

if (!defined('APPLICATION'))
    exit();
/*
 * This is the edit view for the Project controller.
 * Displays a form for creating or editing a project.
 */

echo $this->Form->Open();
echo $this->Form->Errors();
?><ul>
    <li><?
	echo $this->Form->Label("Title","Title");
    ?></li>
    <li><?
	echo $this->Form->TextBox("Title");
    ?></li>
</ul>
<ul>
    <li><?
	echo $this->Form->Label("Description", "Description");
    ?></li>
    <li><?
	echo $this->Form->TextBox("Description", array('multiline' => TRUE));
    ?></li>
</ul>
<ul>
    <li><?
	echo $this->Form->Label("PaypalCode Donate Button Code","Paypal")
    ?></li>
    <li><?
	echo $this->Form->TextBox("PaypalCode", array('multiline' => TRUE));
    ?></li>
</ul>
<ul>
    <li><?
	echo $this->Form->Close("Save");
    ?></li>
</ul>