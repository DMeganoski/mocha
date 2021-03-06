<?php

if (!defined('APPLICATION'))
    exit();

class ProjectsSideModule extends Gdn_Module {

    public $ViewingProjectID = NULL;
    
    private $_ViewingUserID = NULL;
    
    /**
     * All Tasks for the current project,
     * set by $this->_GetTasks
     * @var type array
     */
    private $_Tasks = NULL;
    /**
     * Current project's tasks due today,
     * set by $this->GetTasks
     * @var type 
     */
    private $_TodayTasks = NULL;
    /**
     * Current projects's tasks due tomorrow,
     * set by $this->GetTasks
     * @var type 
     */
    private $_TomorrowTasks = NULL;
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
    
    public $TaskModel;
    
    public function __construct(&$Sender = '') {
	parent::__construct($Sender);
    }

    public function AssetTarget() {
	return 'Panel';
    }

    public function SetView($ControllerName, $ViewName = 'Index') {

	$this->_ControllerName = $ControllerName;
	$this->_ViewName = $ViewName;
    }

    public function ToString() {
        if (C('Garden.RewriteUrls')) {
            $this->HomeLink = "/";
        } else {
            $this->HomeLink = "/index.php?p=/";
        }
	$String = '';
	$Session = Gdn::Session();
	$this->LoggedIn = Gdn::Session()->IsValid();
	$permissions = $Session->User->Permissions;
	$this->admin = preg_match('/Projects.Manage/', $permissions);
	// Retrieve and sort tasks upcoming for this project
        $this->_GetTimes();
        $this->_CountTasks();

	ob_start();

	include_once(PATH_APPLICATIONS . DS . 'mocha/views/modules/projectsside.php');

	$String = ob_get_contents();
	@ob_end_clean();
	return $String;
    }
    
    private function _CountTasks() {
	
	// Create related model
	$this->TaskModel = new TaskModel();
	
	$ProjectID = $this->ViewingProjectID;
	
	// Count Tasks
        $this->TotalCount = $this->TaskModel->CountTasks($ProjectID);
        $this->OverdueCount = $this->TaskModel->CountTasks($ProjectID,$this->Date->getTimestamp(),0);//$this->TaskModel->GetWhereGreater($ProjectID, time());
	$this->TodayCount = $this->TaskModel->CountTasks($ProjectID,$this->Date->getTimestamp(),1);
	$this->FutureCount = $this->TaskModel->CountTasks($ProjectID,$this->Date->getTimestamp(),2);
	
    }
    
    /**
     * Method for setting timestamps for future days. 
     */
    private function _GetTimes() {
	// Get current date / time
	//$TodaysDate = time(); // Timestamp format
	//$this->Date = $TodaysDate;
	$this->OneDay = new DateInterval("P1D");
	
	$this->Today = date('Y-m-d');
	$this->Date = new DateTime($this->Today);
    }

}
