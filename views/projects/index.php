<?php

if (!defined('APPLICATION'))
    exit();
/*
 * Projects index view
 * Displays a list of projects passed from controller
 */
if (Gdn::Session()->IsValid()) {
?><h1><? echo T("All Projects"); ?></h1>
<ol class="Projects Items"><?
    foreach($this->Projects as $Project) {
    ?><li>
	<div class="Box Project">
	    <div class="Meta">
		<h1 class="Title"><? 
		    echo "<a href='/project/$Project->ProjectID'>".$Project->Title."</a>"; 
		?></h1><?
		echo "Created $Project->DateInserted";
		echo " by ".$Project->InsertName;
	    ?></div>
	    <div class="Description"><? echo $Project->Description; ?></div>
	    <div class="Controls">
		<a href="/project/edit/<? echo $Project->ProjectID; ?>">Edit</a>
		<a href="/project/delete/<? echo $Project->ProjectID; ?>">Delete</a>
	    </div>
	</div>
    </li><?
    }
?></ol><?
}