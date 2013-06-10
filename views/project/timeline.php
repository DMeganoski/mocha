<?php
if (!defined('APPLICATION'))
    exit();
$Session = Gdn::Session();

if (!function_exists('WriteActivity'))
   include($this->FetchViewLocation('helper_functions', 'activity', 'dashboard'));

/**
 * This will be the view for creating, editing, and managing tasks. 
 */
?><h1><? echo T("Activity"); ?></h1><?
echo $this->Form->Open();
echo $this->Form->Errors();
foreach($this->ProjectActivity as $Activity) {
    ?><ul class="DataList Activities"><?
    WriteActivity($Activity, $this, $Session, $Comment);
    ?></ul><?
}
echo $this->Form->Close();