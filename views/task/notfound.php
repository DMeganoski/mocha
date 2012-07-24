<?php
if (!defined('APPLICATION'))
    exit();

echo T("The page you are looking for could not be found.");
if ($this->Message !== NULL) {
    echo $this->Message;
}
?>
