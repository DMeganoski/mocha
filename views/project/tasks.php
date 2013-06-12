<?php if(!defined('APPLICATION')) exit();
$this->Now = date('Y-m-d');
$this->Date = new DateTime($this->Now);
$TodayTimestamp = $this->Date->getTimestamp();
$this->OneDay = new DateInterval("P1D");
$this->Date->add($this->OneDay);
$TomorrowTimestamp = $this->Date->getTimestamp();

?><h1>Project Tasks</h1>
<div id="TaskFormBox" style="display:none;"></div>
<ul class="DataList TaskList"><div class='Loading'></div></ul>
<div id="JsInfo" style="display:none"  projectID="<? echo $this->ViewingProjectID ?>" today="<? echo $TodayTimestamp ?>" tomrorrow="<? echo $TomorrowTimestamp ?>" userID="<? echo $this->ViewingUserID; ?>"></div>

