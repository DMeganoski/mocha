<?php
if (!defined('APPLICATION'))
    exit();
$Class = 'Active';
?>
<div class="Tabs">
    <ul>
	<li class="<? echo $Class ?>"><a href="/project/overview/<? echo $this->ViewingProjectID; ?>" class="TabLink"><? echo T("Overview"); ?></a></li>
	<li class="<? echo $Class ?>"><a href="/project/timeline/<? echo $this->ViewingProjectID; ?>" class="TabLink"><? echo T("Timeline"); ?></a></li>
    </ul>
</div>