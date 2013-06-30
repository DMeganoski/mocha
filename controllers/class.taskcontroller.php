<?php

if (!defined('APPLICATION'))
    exit();

/**
 * TODO: Security measures all around 
 */
class TaskController extends MochaController {

    /** @var array List of objects to prep. They will be available as $this->$Name. */
    public $Uses = array('Form', 'TaskModel', 'ActivityModel', 'ProjectModel');

    /**
     * If you use a constructor, always call parent.
     * Delete this if you don't need it.
     *
     * @access public
     */
    public function __construct() {
	parent::__construct();
    }

    /**
     * This is a good place to include JS, CSS, and modules used by all methods of this controller.
     *
     * Always called by dispatcher before controller's requested method.
     * 
     * @since 1.0
     * @access public
     */
    public function Initialize() {
	// There are 4 delivery types used by Render().
	// DELIVERY_TYPE_ALL is the default and indicates an entire page view.
	if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	    $this->Head = new HeadModule($this);

	if ($this->Head) {
	    $this->Head->AddTag('meta', array(
		'name' => 'description',
		'content' => "X"
	    ));
	    $this->AddJsFile('jquery.js');
	    $this->AddJsFile('css_browser_selector.js');
	    $this->AddJsFile('jquery.livequery.js');
	    $this->AddJsFile('jquery.form.js');
	    $this->AddJsFile('jquery.popup.js');
	    $this->AddJsFile('jquery.gardenhandleajaxform.js');
	    $this->AddJsFile('global.js');
	    $this->AddCssFile('style.css');
	    $this->AddCssFile('custom.css');

	    $this->AddCssFile('info.css');
	    $this->AddJsFile('info.js');
	    $this->AddCssFile('widgetchoices.css');
	    $this->AddJsFile('widgetchoices.js');
	    $this->AddCssFile('editor.css');

	    $this->AddCssFile('/applications/mocha/design/project.css');
            $this->AddCssFile('/applications/mocha/design/task.css');
	}
        
        if (C('Garden.RewriteUrls')) {
            $this->HomeLink = "/";
        } else {
            $this->HomeLink = "/index.php?p=/";
        }

	// Call Gdn_Controller's Initialize() as well.
	parent::Initialize();
    }

    public function Create() {
        $this->AddCssFile('task.css');
	$ProjectID = GetValue(0, $this->RequestArgs, FALSE);

	$Session = Gdn::Session();
	$UserID = $Session->UserID;

	$Validation = new Gdn_Validation();
	$this->Form = new Gdn_Form(/* $Validation */);

	$this->Form->SetModel($this->TaskModel);
	$this->Form->AddHidden("ProjectID", $ProjectID);
        $this->Today = date('Y-m-d');
	$this->Date = new DateTime($this->Today);
        //$this->Form->AddHidden("DateDue", $this->Date);

	if ($this->Form->AuthenticatedPostBack() === FALSE) {
	    $this->Form->SetFormValue("ProjectID", $ProjectID);
	    $this->Form->SetFormValue("Title", "New Task");
	} else {
            $FormValues = $this->Form->FormValues();
            $Timestamp = Gdn_Format::ToTimestamp($FormValues['DateDue']);
            $this->Form->AddHidden("DueTimestamp", $Timestamp);
            $this->Form->SetFormValue("DueTimestamp", $Timestamp);
	    if ($this->Form->Save()) {
		// Create the activity model
		$this->ActivityModel = new ActivityModel($Validation);
		// Get the related data
		
		$User = Gdn::UserModel()->GetID($UserID);
		$this->ActivityModel->Name = 'Activity';
		$NewActivityID = $this->TaskModel->AddActivity(
			$UserID, 'CreateProjectTask', $User->Name." created the task: <a href='".$this->HomeLink."project/task/".$ProjectID."' >\"".$FormValues['Title']."\"</a>", '$UserID', '', 'project/' . $ProjectID, FALSE);
		$Results = $this->ActivityModel->ValidationResults();
		$this->InformMessage('Task Saved');
		Redirect('index.php?p=/project/tasks/'.$ProjectID);
	    } else {
		$this->InformMessage('Task Not Saved');
	    }
	}
        include_once(PATH_APPLICATIONS.DS.'mocha/views/task/create.php');
	//$this->Render();
    }
    
    public function Edit() {
        $this->AddCssFile('task.css');
	

	$Session = Gdn::Session();
	$UserID = $Session->UserID;
        
        $ProjectID = GetValue(0, $this->RequestArgs, FALSE);
        $TaskID = GetValue(1, $this->RequestArgs, FALSE);
        
        $this->Task = $this->TaskModel->Get($TaskID);
        $this->editing = 1;
        
	$Validation = new Gdn_Validation();
	$this->Form = new Gdn_Form(/* $Validation */);

	$this->Form->SetModel($this->TaskModel);
	$this->Form->AddHidden("ProjectID", $ProjectID);
        $this->Form->SetData($this->Task);
        $this->Today = date('Y-m-d');
	$this->Date = new DateTime($this->Today);
        //$this->Form->AddHidden("DateDue", $this->Date);

	if ($this->Form->AuthenticatedPostBack() === FALSE) {
	    $this->Form->SetFormValue("ProjectID", $ProjectID);
	    $this->Form->SetFormValue("Title", "New Task");
	} else {
            $FormValues = $this->Form->FormValues();
            $Timestamp = Gdn_Format::ToTimestamp($FormValues['DateDue']);
            $this->Form->AddHidden("DueTimestamp", $Timestamp);
            $this->Form->SetFormValue("DueTimestamp", $Timestamp);
	    if ($this->Form->Save()) {
		
		$this->InformMessage('Task Saved');
		Redirect('index.php?p=/project/tasks/'.$ProjectID);
	    } else {
		$this->InformMessage('Task Not Saved');
	    }
	}
        include_once(PATH_APPLICATIONS.DS.'mocha/views/task/create.php');
	//$this->Render();
    }
    
    public function PostTask() {
        
        $Request = Gdn::Request();
        $ProjectID = $Request->Post('ProjectID');
        $TaskType = $Request->Post('Type');
        
        $this->ViewingProjectID = $ProjectID;
        $this->NewTaskType = $TaskType;
        
        include_once(PATH_APPLICATIONS.DS.'mocha/views/task/create.php');
        
        
    }

    public function Delete() {
        
	$ProjectID = GetValue(0, $this->RequestArgs, NULL);
	$TaskID = GetValue(1, $this->RequestArgs, NULL);
                
        // Create and Configure the side module
        $SideModule = new ProjectsSideModule();
        $SideModule->ViewingProjectID = $ProjectID;
        $SideModule->SetView('Task', 'Delete');

        // Add the module
        $this->AddModule($SideModule);
        $this->AddJsFile('sidepanel.js');
        
        $this->AddModule($SideModule);
	$Task = $this->TaskModel->Get($TaskID);
        //$this->AddJsFile('sidepanel.js');
	if ($this->Form->AuthenticatedPostBack() === FALSE) {
            
	} else {

	    // Tricky way of getting a cancel button
	    $FormValues = $this->Form->FormValues();
	    $Verify = $FormValues['Submit'];

	    if ($Verify == "Delete") {
		$this->TaskModel->Delete($TaskID);
		$this->InformMessage("Task Deleted");
                Redirect('/project/tasks/'.$ProjectID);
	    } else {
		$this->InformMessage("Delete Canceled");
	    }
	}

	$this->Render();
    }

    public function NotFound($Reason = NULL) {

	if ($Reason !== NULL) {
	    $this->Message = $reason;
	    $this->Render();
	} else {
	    $this->Message = NULL;
	    $this->Render();
	}
    }
    
    /**
     * A post function to get the tasks for a specific project and user and return them as an html list 
     */
    public function GetTasks() {
        
        $Request = Gdn::Request();
	$ProjectID = $Request->Post('ProjectID');
        $Timestamp = $Request->Post('Timestamp');
        $UserID = $Request->Post('UserID');
        
        $this->ViewingProjectID = $ProjectID;
        $this->_CountTasks($Timestamp);
	$this->_GetTasks($Timestamp);
	$this->_GetTimes($Timestamp);
        include_once(PATH_APPLICATIONS.DS."mocha/views/task/tasklist.php");
        
    }
    
    public function NestTask() {
        
        $Request = Gdn::Request();
	$ParentID = $Request->Post('ParentID');
	$ChildID = $Request->Post('ChildID');
        
        $Return = $this->TaskModel->NestTask($ParentID,$ChildID);
        echo $Return;
    }
    
    /**
     * Method for retreiving current project's task data 
     */
    private function _GetTasks($Timestamp) {
	// Get current date / time
	$TodaysDate = time(); // Timestamp format
	$this->Date = $TodaysDate;
	
	// Query data
	$this->_Tasks = $this->TaskModel->GetWhere(array("t.ProjectID" => $this->ViewingProjectID, 'Type >=' => 1));
	
	// Select Tasks to show
	// TODO check to see if we need to offset user timezone
	/*foreach ($this->_Tasks as &$Task) {
	    
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
	}*/
    }
    
    private function _CountTasks() {
	
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
     * Method for setting timestamps for future days. 
     */
    private function _GetTimes() {
	// Get current date / time
	//$TodaysDate = time(); // Timestamp format
	//$this->Date = $TodaysDate;
	$this->OneDay = new DateInterval("P1D");
	
	$this->Now = date('Y-m-d');
	$this->Date = new DateTime($this->Now);
        $this->TodayTimestamp = $this->Date->getTimestamp();
	$this->Date->add($this->OneDay);
	$this->TomorrowTimestamp = $this->Date->getTimestamp();
        

    }

}
