<?php

if (!defined('APPLICATION'))
    exit();

class ProjectController extends MochaController {

    /** @var array List of objects to prep. They will be available as $this->$Name. */
    public $Uses = array('Form', 'ProjectModel', 'TaskModel', 'ActivityModel');
    
    // variable for determining view method, useful.
    private $_Method;

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
	}

	// Add Modules
	$this->AddModule('GuestModule');
	$this->AddModule('SignedInModule');

	// Call Gdn_Controller's Initialize() as well.
	parent::Initialize();
    }

    public function Index() {
	
	$UserID = Gdn::Session()->UserID;

	$RequestedID = GetValue(0, $this->RequestArgs, 0);

	if ($RequestedID === 0) {
	    $this->Render();
	} else {
	    Redirect("project/overview/$RequestedID");
	}
    }
    
    public function Overview() {
	
	$RequestedID = GetValue(0, $this->RequestArgs, FALSE);
	
	// Get the viewing user ID
	$Session = Gdn::Session();
	$ViewingUserID = $Session->UserID;
	$this->UserName = $Session->User->Name;

	// Create and configure the head module (tabs)
	$ProjectsHeadModule = new ProjectsHeadModule();
	$ProjectsHeadModule->ViewingProjectID = $RequestedID;
	$ProjectsHeadModule->SetView('Project','Overview');

	// Create and Configure the side module
	$ProjectsSideModule = new ProjectsSideModule();
	$ProjectsSideModule->ViewingProjectID = $RequestedID;
	$ProjectsSideModule->SetView('Project','Overview');
	
	// Add the modules
	$this->AddModule($ProjectsHeadModule);
	$this->AddModule($ProjectsSideModule);
	
	// Get Project Data
	$this->Project = $this->ProjectModel->GetWhere('ProjectID', $RequestedID)->FirstRow();
	
	$this->Render();
    }

    public function Timeline($RequestedID) {
	
	$RequestedID = GetValue(0, $this->RequestArgs, FALSE);
	
	$this->Method = "Overview";

	$this->AddJsFile('sidepanel.js');
	$this->AddJsFile('/applications/dashboard/js/activity.js');

	// Get the viewing user ID
	$Session = Gdn::Session();
	$ViewingUserID = $Session->UserID;
	$this->UserName = $Session->User->Name;

	// Create and configure the head module (tabs)
	$ProjectsHeadModule = new ProjectsHeadModule();
	$ProjectsHeadModule->SetView('Project');

	// Create and Configure the side module
	$ProjectsSideModule = new ProjectsSideModule();
	$ProjectsSideModule->ViewingProjectID = $RequestedID;
	$ProjectsSideModule->SetView('Project');

	// Add the modules
	$this->AddModule($ProjectsHeadModule);
	$this->AddModule($ProjectsSideModule);

	// Get Project Data
	$this->Project = $this->ProjectModel->GetWhere('ProjectID', $RequestedID)->FirstRow();

	// Get Activity related to project
	//$this->ProjectActivity = $this->ProjectModel->GetActivity($this->Project->ProjectID);
	$this->ProjectActivity = $this->ActivityModel->GetWhere("Route", "project/" . $this->Project->ProjectID);

	/* --- Build Activity Form ------------ */
	$Comment = $this->Form->GetFormValue('Comment');
	$this->CommentData = FALSE;
	
	if ($Session->UserID > 0 && $this->Form->AuthenticatedPostBack() && !StringIsNullOrEmpty($Comment)) {
	    $Comment = substr($Comment, 0, 1000); // Limit to 1000 characters...
	    // Update About if necessary
	    $ActivityType = 'WallComment';
	    $NewActivityID = $this->ActivityModel->Add(
		    $Session->UserID, $ActivityType, $Comment);

	    if ($this->_DeliveryType === DELIVERY_TYPE_ALL) {
		Redirect('activity');
	    } else {
		// Load just the single new comment
		$this->HideActivity = TRUE;
		$this->ActivityData = $this->ActivityModel->GetWhere('ActivityID', $NewActivityID);
		$this->View = 'timeline';
	    }
	    
	} else {
	    
	    $Limit = 50;
	    $this->ActivityData = is_array($RoleID) ? $this->ActivityModel->GetForRole($RoleID, $Offset, $Limit) : $this->ActivityModel->Get('', $Offset, $Limit);
	    $TotalRecords = is_array($RoleID) ? $this->ActivityModel->GetCountForRole($RoleID) : $this->ActivityModel->GetCount();
	    if ($this->ActivityData->NumRows() > 0) {
		$ActivityData = $this->ActivityData->ResultArray();
		$ActivityIDs = ConsolidateArrayValuesByKey($ActivityData, 'ActivityID');
		$this->CommentData = $this->ActivityModel->GetComments($ActivityIDs);
	    }
	    $this->View = 'timeline';

	    // Build a pager
	    $PagerFactory = new Gdn_PagerFactory();
	    $this->Pager = $PagerFactory->GetPager('MorePager', $this);
	    $this->Pager->MoreCode = 'More';
	    $this->Pager->LessCode = 'Newer Activity';
	    $this->Pager->ClientID = 'Pager';
	    $this->Pager->Configure(
		    $Offset, $Limit, $TotalRecords, 'project/timeline/' . (is_array($RoleID) ? implode(',', $RoleID) : '0') . '/%1$s/%2$s/'
	    );

	    // Deliver json data if necessary
	    if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
		$this->SetJson('LessRow', $this->Pager->ToString('less'));
		$this->SetJson('MoreRow', $this->Pager->ToString('more'));
		$this->View = 'timeline';
	    }
	}
	$this->Render();
    }

    public function Create() {
	$this->Method = "Create";

	$Session = Gdn::Session();
	$UserID = $Session->UserID;

	$this->Form = new Gdn_Form();

	$this->Form->SetModel($this->ProjectModel);

	$this->Form->AddHidden("InsertUserID", $UserID);

	if ($this->Form->AuthenticatedPostBack() !== FALSE) {
	    $this->Form->SetFormValue("InsertUserID", $UserID);
	    if ($this->Form->Save()) {
		$FormValues = $this->Form->FormValues();
		$this->ActivityModel = new ActivityModel($Validation);
		$this->ActivityModel->Name = 'Activity';
		$NewActivityID = $this->TaskModel->AddActivity(
			$UserID, 'CreateProject', $Session->User->Name.' created the '.$FormValues['Title'].' project.', '$UserID', '', 'project/' . $FormValues['ProjectID'], FALSE);
		$Results = $this->ActivityModel->ValidationResults();
		print_r($Results);
		$this->InformMessage("Changes Saved");
		Redirect('/projects/');
	    } else {
		$this->InformMessage("Changes Not Saved");
	    }
	} else {
	    $this->Form->SetData($this->ProjectModel->Data);
	}
	$this->Render('edit');
    }
    
    /**
     * Method for editing projects. 
     */
    public function Edit() {
	
	$ProjectID = GetValue(0, $this->RequestArgs, 0);
	
	if ($ProjectID > 0) {
	    
	    $ProjectModel = $this->ProjectModel;
	    $this->Form->SetModel($ProjectModel);
	    $this->Form->AddHidden("ProjectID");
	    $this->Form->SetFormValue("ProjectID", $ProjectID);
	    
	    if($this->Form->AuthenticatedPostBack() === FALSE) {
		$this->Form->SetData($ProjectModel->GetID($ProjectID));
	    } else {
		if($this->Form->Save()) {
		    
		    $this->InformMessage("Saved");
		    Redirect("/project/$ProjectID");
		    
		} else {
		    $this->InformMessage("Not Saved...");
		}
	    }
	    
	    
	}
	$this->Render();
	
    }
    
    public function Delete() {
	$ProjectID = GetValue(0, $this->RequestArgs, 0);
	
	if ($ProjectID > 0) {
	    
	    $ProjectModel = $this->ProjectModel;
	    
	    $ProjectModel->Delete($ProjectID);
	    
	    Redirect('/projects');
	    
	}
	
	
    }

    /* -------------------- Ajax request methods ---------------------------- */

    public function NestTask() {

	$Request = Gdn::Request();

	/* PREP: Verify user permission
	  $UserID = $Request->Post('UserID');
	  $TransientKey = $Request->Post('TransientKey');
	  $ProjectID = $Request->Post('ProjectID');
	 * 
	 */

	$ParentID = $Request->Post('ParentID');
	$ChildID = $Request->Post('ChildID');

	$this->TaskModel->NestTask($ParentID, $ChildID);
    }

}
