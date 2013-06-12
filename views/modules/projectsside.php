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
        ?><img class='Project' src='/applications/mocha/design/images/default.jpg' />
        <div class="Box TaskBox">
            <ul class ="nav nav-tabs nav-stacked">
                <li><a href='<? echo $this->HomeLink . "project/overview/" . $this->ViewingProjectID ?>'>Overview</a></li>
                <li><a href='<? echo $this->HomeLink . "project/tasks/" . $this->ViewingProjectID ?>'>Tasks</a></li>
                <li><a href='<? echo $this->HomeLink . "project/timeline/" . $this->ViewingProjectID ?>'>Timeline</a></li>
            </ul>
        </div><?
// OK, for the task list. Here we go.
        ?><div class="Box TodayBox">
            <h4><? echo T("Tasks"); ?></h4>
            <ul class="<? echo $this->TodayTimestamp; ?> nav nav-tabs nav-stacked">
                <li><a href=''><? echo T('Total: '); ?><span class="Total Count"><? echo $this->TotalCount; ?></span></a></li>
                <li><a href=''><? echo T('Overdue: '); ?><span class="Overdue Count"><? echo $this->OverdueCount; ?></span></a></li>
                <li><a href=''><? echo T('Today: '); ?><span class="Today Count"><? echo $this->TodayCount; ?></span></a></li>
                <li><a href=''><? echo T('Future: '); ?><span class="Future Count"><? echo $this->FutureCount; ?></span></a></li>
                </ul><?
            ?></div><?
        break;
}



