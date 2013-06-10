<?php
if (!defined('APPLICATION'))
    exit();

$Project = $this->Project;

// Create dates for inserted and updated
$DateInserted = new DateTime($this->Project->DateInserted);
$DateUpdated = new DateTime($this->Project->DateUpdated);

?><h1><? echo $Project->Title; ?></h1>
<ul class='DataList'>
    <li class='Item'>
        <div class='ItemContent'>
            <div class='InfoBox'><?
		echo "<h5>Created: </h5>".$DateInserted->format('F d, Y');
	    ?></div>
             <div class='InfoBox'><?
                echo "<h5>Last Updated: </h5>".$DateUpdated->format('g:i a l F d, Y');
            ?></div>
            <div class='InfoBox'><?
		echo "<h5>Home Page: </h5>http://homepage.com";
	    ?></div>
            <div class='InfoBox'><?
		echo "<h5>Download: </h5>http://download.com";
	    ?></div>
        </div>
    </li>
</ul>
<div class="Box ProjectDescription">
    <p class="ProjectDescription"><?
    echo $this->Project->Description;
    ?></p>
</div>


