<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript">
    $('span.Delete a').addClass("Popup");
</script>
<span class="Deliverable Count"><? echo T('Deliverables') . ": " . $this->DeliverablesCount; ?></span>
    	<span class="Milestone Count"><? echo T('Milestones') . ": " . $this->MilestonesCount; ?></span>
    	<span class="Task Count"><? echo T('Tasks') . ": " . $this->TasksCount; ?></span><?
	foreach ($this->_Tasks as $TodayTask) {
	    if ($TodayTask->Today == 1) {
		echo'<li class="'.$TodayTask->TaskID.'">' . $TodayTask->Title . "<span class='Delete'><a href='/task/delete/" . $TodayTask->ProjectID . DS . $TodayTask->TaskID . "'>x</a></span><br/>";
		echo "$TodayTask->Timestamp</li>";
	    }
	}
        echo $this->ProjectTasks;

