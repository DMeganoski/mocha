<?php

if (!defined('APPLICATION'))
    exit(); // Make sure this file can't get accessed directly

/**
 * Hooks for other applications
 * 
 * TODO: add profile tab and page 
 */
class MochaHooks implements Gdn_IPlugin {

    /**
     * Add the main menu link
     * @param type $Sender
     */
    public function Base_Render_Before($Sender) {
	if ($Sender->Menu) {
	    $Sender->Menu->AddLink('Projects', T('Projects'), '/projects', FALSE, array('class' => 'Projects', 'Standard' => TRUE));
	}
    }

    /**
     * Special function automatically run upon clicking 'Enable' on your application.
     * Change the word 'mocha' anywhere you see it.
     */
    public function Setup() {
	// You need to manually include structure.php here for it to get run at install.
	include(PATH_APPLICATIONS . DS . 'mocha' . DS . 'settings' . DS . 'structure.php');

	// Stores a value in the config to indicate it has previously been installed.
	// You can use if(C('Mocha.Setup', FALSE)) to test whether to repeat part of your setup.
	SaveToConfig('Mocha.Setup', TRUE);
    }

    /**
     * Special function automatically run upon clicking 'Disable' on your application.
     */
    public function OnDisable() {
	// Optional. Delete this if you don't need it.
    }

    /**
     * Special function automatically run upon clicking 'Remove' on your application.
     */
    public function CleanUp() {
	// Optional. Delete this if you don't need it.
    }

}