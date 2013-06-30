<?php
if (!defined('APPLICATION'))
    exit();

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
        switch ($this->ControllerView) {
        
            case "Tasks":
                break;
            case "Overview":
                break;
            case "Timeline":
                break;
            default:
                break;
        
        }
        ?><div class="Box TaskBox">
            <ul class ="nav nav-tabs nav-stacked">
                <li><a href='<? echo $this->HomeLink . "project/overview/" . $this->ViewingProjectID ?>'>Overview</a></li>
                <li><a href='<? echo $this->HomeLink . "project/tasks/" . $this->ViewingProjectID ?>'>Tasks</a></li>
                <li><a href='<? echo $this->HomeLink . "project/timeline/" . $this->ViewingProjectID ?>'>Timeline</a></li>
            </ul>
        </div><?
        break;
}



