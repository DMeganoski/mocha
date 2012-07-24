<?php
if (!defined('APPLICATION'))
    exit();
$Session = Gdn::Session();

if (!function_exists('WriteActivity'))
   include($this->FetchViewLocation('helper_functions', 'activity', 'dashboard'));

/**
 * This will be the view for creating, editing, and managing tasks. 
 */
$Project = $this->Project;
?><div class="Timeline">
    <span class="Deliverable Count"><? echo T('Deliverables').": ".$Project->Deliverables->Count; ?></span>
    <span class="Milestone Count"><? echo T('Milestones').": ".$Project->Milestones->Count; ?></span>
    <span class="Task Count"><? echo T('Tasks').": ".$Project->Tasks->Count; ?></span>
</div>
<div class="Box"><h2><? echo T("Activity"); ?></h2></div><?
echo $this->Form->Open();
echo $this->Form->Errors();
foreach($this->ProjectActivity as $Activity) {
    ?><ul class="DataList Activities"><?
    WriteActivity($Activity, $this, $Session, $Comment);
    ?></ul><?
}
echo $this->Form->Close();