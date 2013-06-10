<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript">
    $('span.Delete a').addClass("Popup");
</script><? /*
<span class="Deliverable Count"><? echo T('Deliverables') . ": " . $this->DeliverablesCount; ?></span>
    	<span class="Milestone Count"><? echo T('Milestones') . ": " . $this->MilestonesCount; ?></span>
    	<span class="Task Count"><? echo T('Tasks') . ": " . $this->TasksCount; ?></span><?
	*/foreach ($this->_Tasks as $Task) {
	    
		echo "<li class='".$Task->TaskID." Item'>";
                echo "<div class='ItemContent'>".$Task->Title . "</div>";
                echo "<span class='Delete'><a href='/task/delete/" . $Task->ProjectID . DS . $Task->TaskID . "'>x</a></span><br/>";
		echo "$Task->Timestamp</li>";
	    
	}
        //echo $this->ProjectTasks;

