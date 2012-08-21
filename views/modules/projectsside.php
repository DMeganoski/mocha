<?php
if (!defined('APPLICATION'))
    exit();

$TodayTimestamp = $this->Date->getTimestamp();

if ($this->admin) {
    ?><a href="/project/create" class="BigButton">Start a new Project</a><?
    if ($this->_ControllerName == "Project") {
	?><a href="/task/create/<? echo $this->ViewingProjectID ?>" class="BigButton NewTask Popup">Create a new task for this project</a><?
    } //else {
    if (!empty($this->Project->PaypalCode)) {
	?><div class="Box"><?
	echo $this->Project->PaypalCode;
	?></div><?
    }
// OK, for the task list. Here we go.
    ?><div class="Box TodayBox">
    	<h4><? echo T("Due Today"); ?></h4>
    	<p><? echo $this->Date->format('M d, Y'); ?></p>
        <ul class="Tasks <? echo $this->TodayTimestamp; ?>">
    	<span class="Deliverable Count"><? echo T('Deliverables') . ": " . $this->DeliverablesCount; ?></span>
    	<span class="Milestone Count"><? echo T('Milestones') . ": " . $this->MilestonesCount; ?></span>
    	<span class="Task Count"><? echo T('Tasks') . ": " . $this->TasksCount; ?></span><?
	foreach ($this->_Tasks as $TodayTask) {
	    if ($TodayTask->Today == 1) {
		echo'<li>' . $TodayTask->Title . "<span class='Delete'><a href='/task/delete/" . $TodayTask->ProjectID . DS . $TodayTask->TaskID . "'>x</a></span><br/>";
		echo "$TodayTask->Timestamp</li>";
	    }
	}
        ?></ul><?
	$this->Date->add($this->OneDay);
	$TomorrowTimestamp = $this->Date->getTimestamp();
    ?></div>
    <div class="Box TomrorrowBox">
	
	<h4><? echo T("Due Tomorrow");?></h4>
	<p><? echo $this->Date->format('M d, Y'); ?></p>
        <ul class="Tasks TomrorrowTasks">
            <li><span class="Deliverable Count"><? echo T('Deliverables') . ": " . $this->DeliverablesCount; ?></span>
                <span class="Milestone Count"><? echo T('Milestones') . ": " . $this->MilestonesCount; ?></span>
                <span class="Task Count"><? echo T('Tasks') . ": " . $this->TasksCount; ?></span>
            </li><?
            foreach ($this->_Tasks as $TomorrowTask) {
                if ($TomorrowTask->DateInserted < $TomorrowTimestamp) {
                    echo "<li class='".$TomorrowTask->TaskID."'>" . $TomorrowTask->Title . "<span class='Delete'><a href='/task/delete/" . $TomorrowTask->ProjectID . DS . $TomorrowTask->TaskID . "' class='Popup'>x</a></span><br/>";
                    echo "$TomorrowTask->Timestamp</li>";
                }
            }
    ?></ul>
	</div><?
}   
// Create hidden div containing project info for js to pick up data from
?><div id="JsInfo" style="display:none"  projectID="<? echo $this->ViewingProjectID ?>" today="<? echo $TodayTimestamp ?>" tomrorrow="<? echo $TomorrowTimestamp ?>" userID="<? echo $this->ViewingUserID; ?>"></div>
