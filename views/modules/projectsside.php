<?php
if (!defined('APPLICATION'))
    exit();

$TodayTimestamp = $this->Date->getTimestamp();

switch ($this->_ControllerName) {

    /* ----------------- ProjectS Controller ----------------------------- */
    case "Projects":
        if ($this->admin) {
            ?><a href="<? echo $this->HomeLink; ?>project/create" class="BigButton">New Project</a><?
        }
        ?><div class="Box TaskBox">
            <ul class ="nav nav-tabs nav-stacked">
                <li><a href='<? echo $this->HomeLink; ?>projects/all'>All Projects</a></li>
                <li><a href='<? echo $this->HomeLink; ?>projects/user'>My Projects</a></li>
                <li><a href='<? echo $this->HomeLink; ?>projects/bookmarks'>Bookmarked Projects</a></li>
            </ul>
        </div><?
        break;

    /* ----------------- Project Controller ----------------------------- */
    case "Project":
        if ($this->admin) {
            ?><a href="<? echo $this->HomeLink; ?>task/create/<? echo $this->ViewingProjectID ?>" class="BigButton NewTask Popup">New Task</a><?
        }
        ?><div class="Box TaskBox">
            <ul class ="nav nav-tabs nav-stacked">
                <li><a href='<? echo $this->HomeLink . "project/overview/" . $this->ViewingProjectID ?>'>Overview</a></li>
                <li><a href='<? echo $this->HomeLink . "project/tasks/" . $this->ViewingProjectID ?>'>Tasks</a></li>
                <li><a href='<? echo $this->HomeLink . "project/timeline/" . $this->ViewingProjectID ?>'>Timeline</a></li>
            </ul>
        </div><?
// OK, for the task list. Here we go.
        ?><div class="Box TodayBox">
            <h4><? echo T("Due Today"); ?></h4>
            <p><? echo $this->Date->format('M d, Y'); ?></p>
            <ul class="Tasks <? echo $this->TodayTimestamp; ?> nav nav-tabs nav-stacked">
                <li class="Deliverable Count"><? echo T('Deliverables') . ": " . $this->DeliverablesCount; ?></li>
                <li class="Milestone Count"><? echo T('Milestones') . ": " . $this->MilestonesCount; ?></li>
                <li class="Task Count"><? echo T('Tasks') . ": " . $this->TasksCount; ?></li><?
                ?></ul><?
            $this->Date->add($this->OneDay);
            $TomorrowTimestamp = $this->Date->getTimestamp();
            ?></div>
        <div class="Box TomrorrowBox">

            <h4><? echo T("Due Tomorrow"); ?></h4>
            <p><? echo $this->Date->format('M d, Y'); ?></p>
            <ul class="Tasks TomrorrowTasks">
                <li><span class="Deliverable Count"><? echo T('Deliverables') . ": " . $this->DeliverablesCount; ?></span>
                    <span class="Milestone Count"><? echo T('Milestones') . ": " . $this->MilestonesCount; ?></span>
                    <span class="Task Count"><? echo T('Tasks') . ": " . $this->TasksCount; ?></span>
                </li><?
                foreach ($this->_Tasks as $TomorrowTask) {
                    if ($TomorrowTask->DateInserted < $TomorrowTimestamp) {
                        echo "<li class='" . $TomorrowTask->TaskID . "'>" . $TomorrowTask->Title . "<span class='Delete'><a href='" . $this->HomeLink . "/task/delete/" . $TomorrowTask->ProjectID . DS . $TomorrowTask->TaskID . "' class='Popup'>x</a></span><br/>";
                        echo "$TomorrowTask->Timestamp</li>";
                    }
                }
                ?></ul>
        </div><?
        break;
}



