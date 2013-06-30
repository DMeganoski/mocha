<?php if (!defined("APPLICATION"))
    exit;
?><div class="Box TodayBox">
    <h4><? echo T("Tasks"); ?></h4>
    <ul class="<? echo $this->TodayTimestamp; ?> nav nav-tabs nav-stacked">
        <li><a href=''><? echo T('Total: '); ?><span class="Total Count"><? echo $this->TotalCount; ?></span></a></li>
        <li><a href=''><? echo T('Overdue: '); ?><span class="Overdue Count"><? echo $this->OverdueCount; ?></span></a></li>
        <li><a href=''><? echo T('Today: '); ?><span class="Today Count"><? echo $this->TodayCount; ?></span></a></li>
        <li><a href=''><? echo T('Future: '); ?><span class="Future Count"><? echo $this->FutureCount; ?></span></a></li>
    </ul>
</div>