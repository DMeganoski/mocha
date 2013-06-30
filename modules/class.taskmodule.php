<?php

if (!defined('APPLICATION'))
    exit();

/**
 * Blank template Module file
 */
class TaskModule extends Gdn_Module {

    
    /**
     * Name of the current controller,
     * set by viewing controller
     * @var type  String
     */
    private $_ControllerName = NULL;
    
    /**
     * Name of the controller's view method,
     * set by viewing controller
     * @var type  String
     */
    private $_ViewName = NULL;
        
    /**
     * Called once on creation
     * @param type $Sender
     */
    public function __construct(&$Sender = '') {
	parent::__construct($Sender);
    }

    /**
     * Define the placement of the module
     * @return string
     */
    public function AssetTarget() {
	return 'Panel';
    }

    /**
     * Custom String for determining the controller.
     * Useful for determining current page and modifying content accordinglys
     * @param type $ControllerName
     * @param type $ViewName
     */
    public function SetView($ControllerName, $ViewName = 'Index') {

	$this->_ControllerName = $ControllerName;
	$this->_ViewName = $ViewName;
    }

    /**
     * Main method, called for rendering html
     * @return type String (HTML)
     */
    public function ToString() {
        // Still not sure of a better solution
        if (C('Garden.RewriteUrls')) {
            $this->HomeLink = "/";
        } else {
            $this->HomeLink = "/index.php?p=/";
        }
        $ProjectID = $this->ViewingProjectID;
        $this->_CountTasks($ProjectID);
        
	$String = '';
	ob_start();

	include_once(PATH_APPLICATIONS . DS . 'application/views/modules/taskmodule.php');

	$String = ob_get_contents();
	@ob_end_clean();
	return $String;
    }
    
    /**
     * Custom Function for counting the number of tasks.
     * @param type $ProjectID
     */
    private function _CountTasks($ProjectID) {
	
	// Create related model
	$this->TaskModel = new TaskModel();
		
	// Count Tasks
        $this->TotalCount = $this->TaskModel->CountTasks($ProjectID);
        $this->OverdueCount = $this->TaskModel->CountTasks($ProjectID,$this->Date->getTimestamp(),0);//$this->TaskModel->GetWhereGreater($ProjectID, time());
	$this->TodayCount = $this->TaskModel->CountTasks($ProjectID,$this->Date->getTimestamp(),1);
	$this->FutureCount = $this->TaskModel->CountTasks($ProjectID,$this->Date->getTimestamp(),2);
	
    }

}
