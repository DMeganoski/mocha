<?php
if (!defined('APPLICATION'))
    exit();
$Session = Gdn::Session();

$Project = $this->Project;
?><h2><? echo $Project->Title; ?></h2>
<div class="Box ProjectDetails">
    <div class="InfoBox"><?
	$DateInserted = new DateTime($this->Project->DateInserted);
	$DateUpdated = new DateTime($this->Project->DateUpdated);
	?><ul>
	    <li><?
		echo "Created: ".$DateInserted->format('F d, Y');
	    ?></li>
	    <li><?
		echo "Last Updated: ".$DateUpdated->format('g:i a l F d, Y');
	    ?></li>
	    <li><?
		echo "Home Page: ";
	    ?></li>
	    <li><?
		echo "Downloads/Source: ";
	    ?></li>
	</ul>
    </div>
    <p class="ProjectDescription"><?
    echo $this->Project->Description;
    ?></p>
</div>


