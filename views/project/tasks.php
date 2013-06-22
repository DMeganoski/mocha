<?php if(!defined('APPLICATION')) exit();
$Date = new DateTime(date('Y-m-d'));
$TodayTimestamp = $Date->getTimestamp();
$OneDay = new DateInterval("P1D");
$Date->add($OneDay);
$TomorrowTimestamp = $Date->getTimestamp();

?><h1><? echo $this->Project->Title; ?></h1>
<h2 class="page-subheader">Task Tracker</h2>
<div id="TaskFormBox" style="display:none;"></div>
<div id="accordion" class="TaskList"><div class='Loading'></div></div>
<div id="JsInfo" style="display:none"  projectID="<? echo $this->ViewingProjectID ?>" today="<? echo $TodayTimestamp ?>" tomrorrow="<? echo $TomorrowTimestamp ?>" userID="<? echo $this->ViewingUserID; ?>"></div>

