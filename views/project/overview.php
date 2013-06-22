<?php
if (!defined('APPLICATION'))
    exit();

$Project = $this->Project;

// Create dates for inserted and updated
$DateInserted = new DateTime($this->Project->DateInserted);
$DateUpdated = new DateTime($this->Project->DateUpdated);

?><h1><? 
echo $Project->Title; 
?></h1>
<div class="Options pull-right">
                <span class="ToggleFlyout OptionsMenu">
                    <span class="btn btn-mini" title="Options">Options</span>
                    <span class="SpFlyoutHandle"></span>
                    <ul class="Flyout dropdown-menu">
                        <li><a class='' href='index.php?p=/project/edit/<? echo $this->Project->ProjectID; ?>'>Edit</a></li>
                        <li><a class='' href='index.php?p=/project/delete/<? echo $this->Project->ProjectID; ?>'>Delete</a></li>
                    </ul>
                </span>
                <a class="Hijack Bookmark" title="Bookmark" href="/index.php?p=/project/bookmark/<? echo $this->Project->ProjectID; ?>"></a>
            </div>
<ul class='DataList'>
    <li class='Item'>
        <div class='ItemContent'>
            <div class='InfoBoxContainer'>
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
            <div class="Box ProjectDescription">
    <p class="ProjectDescription"><?
    echo $this->Project->Description;
    ?></p>
</div>
        </div>
    </li>
</ul>



