<?php
if (!defined('APPLICATION'))
    exit();
/*
 * Projects index view
 * Displays a list of projects passed from controller
 */
if (Gdn::Session()->IsValid()) {
    ?><h1><? echo T("All Projects"); ?></h1>
    <ol class="DataList Projects"><?
    if (!empty($this->Projects)) {
        foreach ($this->Projects as $Project) {
            ?><li class="Item">
                    <div class="Datalist Project">
                        <div class="Meta ItemContent">
                            <div class="Title"><?
            echo "<a href='" . $this->HomeLink . "project/$Project->ProjectID'>" . $Project->Title . "</a>";
            ?></div><?
                                echo "Created $Project->DateInserted";
                                echo " by " . $Project->InsertName;
                                ?></div>
                        <div class="Description"><? echo $Project->Description; ?></div>
                        <div class="Controls">
                            <a href="<? echo $this->HomeLink; ?>project/edit/<? echo $Project->ProjectID; ?>">Edit</a>
                            <a href="<? echo $this->HomeLink; ?>project/delete/<? echo $Project->ProjectID; ?>">Delete</a>
                        </div>
                    </div>
                </li><?
                }
            } else {
                            ?><li class="Item">
                <div class="Meta Item">
                    <h1 class="Title">
                        No Projects are currently Being Tracked.
                    </h1>
                </div>
            </li><?
    }
                        ?></ol><?
    }