<?php

if (!defined('APPLICATION'))
    exit();

class ProjectsSideModule extends Gdn_Module {

    public $ViewingProjectID = NULL;
    
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
	$String = '';
	$Session = Gdn::Session();
	$this->LoggedIn = Gdn::Session()->IsValid();
	$permissions = $Session->User->Permissions;
	$this->admin = preg_match('/Garden.Settings.Manage/', $permissions);
	// Retrieve and sort tasks upcoming for this project
	$this->_CountTasks();
	$this->_GetTasks();
	$this->_GetTimes();

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
	$this->DeliverablesCount = $this->TaskModel->GetCount(array('ProjectID' => $ProjectID, 'Type' => 3));
	$this->MilestonesCount = $this->TaskModel->GetCount(array('ProjectID' => $ProjectID, 'Type' => 2));
	// since we're going by the numeric type id,
	// we have to add types 0 and 1, both simple tasks.
	$UnnestedTasks = $this->TaskModel->GetCount(array('ProjectID' => $ProjectID, 'Type' => 0));
	$NestedTasks = $this->TaskModel->GetCount(array('ProjectID' => $ProjectID, 'Type' => 1));
	$this->TasksCount = $NestedTasks + $UnnestedTasks;
	
    }

    /**
     * Method for retreiving current project's task data 
     */
    private function _GetTasks() {
	// Get current date / time
	$TodaysDate = time(); // Timestamp format
	$this->Date = $TodaysDate;
	
	// Query data
	$this->_Tasks = $this->TaskModel->GetWhere(array("t.ProjectID" => $this->ViewingProjectID));
	
	// Select Tasks to show
	// TODO check to see if we need to offset user timezone
	foreach ($this->_Tasks as &$Task) {
	    
	    // First, lets do some math for days
	    $Hour = 3600;
	    $Hours24 = $Hour * 24;
	    $Hours48 = $Hour * 48;
	    
	    $TaskTimestamp = Gdn_Format::ToTimestamp($Task->DateDue);
	    
	    $Task->Timestamp = $TaskTimestamp;
	    //$Task
	    if ($TaskTimestamp < $TodaysDate+$Hours24) {
		$Task->Today = 1;
	    } elseif ($TaskTimestamp < $TodaysDate+$Hours48) {
		$Task->Tomorrow = 1;
	    } else {
		$Task->Future = 1;
	    }
	}
    }
    
    /**
     * Method for setting timestamps for future days. 
     */
    private function _GetTimes() {
	// Get current date / time
	$TodaysDate = time(); // Timestamp format
	//$this->Date = $TodaysDate;
	$this->OneDay = new DateInterval("P1D");
	
	$this->Now = date('Y-m-d');
	$this->Date = new DateTime($this->Now);
	$this->Date->add($OneDay);
    }
    
    private function _GetProject() {
	
	$ProjectModel = new ProjectModel();
	
    }

}
