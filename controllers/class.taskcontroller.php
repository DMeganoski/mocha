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
	}

	// Call Gdn_Controller's Initialize() as well.
	parent::Initialize();
    }

    public function Create() {

	$ProjectID = GetValue(0, $this->RequestArgs, FALSE);

	$Session = Gdn::Session();
	$UserID = $Session->UserID;

	$Validation = new Gdn_Validation();
	$this->Form = new Gdn_Form(/* $Validation */);

	$this->Form->SetModel($this->TaskModel);
	$this->Form->AddHidden("ProjectID", $ProjectID);

	if ($this->Form->AuthenticatedPostBack() === FALSE) {
	    $this->Form->SetFormValue("ProjectID", $ProjectID);
	    $this->Form->SetFormValue("Title", "New Task");
	} else {
	    if ($this->Form->Save()) {
		// Create the activity model
		$this->ActivityModel = new ActivityModel($Validation);
		// Get the related data
		$FormValues = $this->Form->FormValues();
		$User = Gdn::UserModel()->GetID($UserID);
		$Project = $this->ProjectModel->GetID($ProjectID);
		$this->ActivityModel->Name = 'Activity';
		$NewActivityID = $this->TaskModel->AddActivity(
			$UserID, 'CreateProjectTask', $User->Name." created the task: \"".$FormValues['Title']."\"", '$UserID', '', 'project/' . $ProjectID, FALSE);
		$Results = $this->ActivityModel->ValidationResults();
		print_r($Results);
		$this->InformMessage('Task Saved');
		//Redirect('/project/'.$ProjectID);
	    } else {
		$this->InformMessage('Task Not Saved');
	    }
	}

	$this->Render();
    }

    public function Delete() {



	$ProjectID = GetValue(0, $this->RequestArgs, NULL);
	$TaskID = GetValue(1, $this->RequestArgs, NULL);
	$Task = $this->ProjectTaskModel->GetID($TaskID);
	if ($this->Form->AuthenticatedPostBack() === FALSE) {
	    
	} else {

	    // Tricky way of getting a cancel button
	    $FormValues = $this->Form->FormValues();
	    $Verify = $FormValues['Submit'];

	    if ($Verify == "Delete") {
		$Deleted = $this->ProjectTaskModel->Delete($TaskID);
		$this->InformMessage("Task Deleted");
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

}
