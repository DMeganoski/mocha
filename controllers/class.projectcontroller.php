<?php

if (!defined('APPLICATION'))
    exit();

class ProjectController extends MochaController {

    /**
     * 
     * @var array List of objects to prep. They will be available as $this->$Name.
     */
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

            $this->AddCssFile('/applications/mocha/design/project.css');
            $this->AddJsFile('/applications/mocha/js/project.js');
        }



        // Add Modules
        $this->AddModule('GuestModule');
        $this->AddModule('SignedInModule');

        // Call Gdn_Controller's Initialize() as well.
        parent::Initialize();
    }

    /**
     * Wahtever
     */
    public function Index() {

        $UserID = Gdn::Session()->UserID;

        $RequestedID = GetValue(0, $this->RequestArgs, 0);

        if ($RequestedID === 0) {
            $this->Render();
        } else {
            $this->View = "overview";
            $this->Overview();
        }
    }

    public function Overview() {

        $RequestedID = GetValue(0, $this->RequestArgs, FALSE);
        // Get the viewing user ID
        $Session = Gdn::Session();
        $ViewingUserID = $Session->UserID;
        $this->UserName = $Session->User->Name;


        // Create and Configure the side module
        $SideModule = new ProjectsSideModule();
        $SideModule->ViewingProjectID = $RequestedID;
        $SideModule->SetView('Project', 'Overview');

        // Add the module
        $this->AddModule($SideModule);
        $this->AddJsFile('sidepanel.js');

        // Get Project Data
        $this->Project = $this->ProjectModel->GetWhere('ProjectID', $RequestedID)->FirstRow();

        $this->Render();
    }

    public function Timeline($RequestedID) {

        $RequestedID = GetValue(0, $this->RequestArgs, FALSE);

        $this->Method = "Timeline";

        $this->AddJsFile('sidepanel.js');
        $this->AddJsFile('/applications/dashboard/js/activity.js');

        // Get the viewing user ID
        $Session = Gdn::Session();
        $ViewingUserID = $Session->UserID;
        $this->UserName = $Session->User->Name;

        // Create and Configure the side module
        $ProjectsSideModule = new ProjectsSideModule();
        $ProjectsSideModule->ViewingProjectID = $RequestedID;
        $ProjectsSideModule->SetView('Project');

        // Add the modules
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

    public function Tasks() {

        // JQuery UI files for advanced interface
        $this->AddJsFile('jquery-ui-1.10.3.custom.min.js');
        $this->AddCssFile('jquery-ui-1.10.3.custom.css');

// Hoverintent, taken from JQuery example site, makes accodion react to hover.
        $this->AddJsFile('project.tasks.hoverintent.js');

// And My custom files
        $this->AddJsFile('project.tasks.js');
        $this->AddCssFile('task.css');

// Get the viewing user ID
        $Session = Gdn::Session();
        $this->ViewingUserID = $Session->UserID;

// TODO: Don't think I need a name...
        $this->UserName = $Session->User->Name;

// Get the ID of the related project
        $RequestedID = GetValue(0, $this->RequestArgs, FALSE);
        $this->ViewingProjectID = $RequestedID;

        // Call function to define current timestamp
        $this->_GetTimestamps($Session->HourOffset());
        $this->_TodayTasks = $this->TaskModel->GetWhere(array(
            "t.ProjectID" => $RequestedID,
            'Type >=' => 1,
            'DueTimestamp >=' => ($this->UserTimestamp-86400),
            'DueTimestamp <=' => $this->UserTimestamp
        ));
        $this->_FutureTasks = $this->TaskModel->GetWhere(array(
            "t.ProjectID" => $RequestedID, 
            'Type >=' => 1,
            'DueTimestamp >=' => ($this->UserTimestamp)
        ));
        $this->_OverdueTasks = $this->TaskModel->GetWhere(array(
            "t.ProjectID" => $RequestedID, 
            'Type >=' => 1,
            'DueTimestamp <=' => ($this->UserTimestamp-86400)
        ));


        // Create and Configure the side module
        $SideModule = new ProjectsSideModule();
        $SideModule->ViewingProjectID = $RequestedID;
        $SideModule->SetView('Project', 'Tasks');

        // Add the module
        $this->AddModule($SideModule);
        $this->AddJsFile('sidepanel.js');

        $this->ViewingProjectID = $RequestedID;
        $this->Project = $this->ProjectModel->Get($RequestedID);

        $this->Tasks = $this->TaskModel->GetWhere("ProjectID", $RequestedID);
        $Validation = new Gdn_Validation();
        $this->Form = new Gdn_Form(/* $Validation */);

        $this->Form->SetModel($this->TaskModel);
        $this->Form->AddHidden("ProjectID", $this->ViewingProjectID);
        $this->Form->SetData($Task);
        $this->Today = date('Y-m-d');
        $this->Date = new DateTime($this->Today);
        //$this->Form->AddHidden("DateDue", $this->Date);

        if ($this->Form->AuthenticatedPostBack() === FALSE) {
            $this->Form->SetFormValue("ProjectID", $this->ViewingProjectID);
        } else {
            $FormValues = $this->Form->FormValues();
            $Timestamp = Gdn_Format::ToTimestamp($FormValues['DateDue']);
            $this->Form->AddHidden("DueTimestamp", $Timestamp);
            $this->Form->SetFormValue("DueTimestamp", $Timestamp);
            if ($this->Form->Save()) {

                $this->InformMessage('Task Saved');
                Redirect('index.php?p=/project/tasks/' . $this->ViewingProjectID);
            } else {
                $this->InformMessage('Task Not Saved');
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
                $User = Gdn::UserModel()->GetID($UserID);
                $Project = $FormValues['ProjectID'];
                $this->ActivityModel->Name = 'Activity';
                $NewActivityID = $this->ProjectModel->AddActivity(
                        $UserID, 'CreateProject', $User->Name . " created the project: \"" . $FormValues['Title'] . "\"", '$UserID', '', 'project/' . $ProjectID, FALSE);
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
            $this->Form->SetData($ProjectModel->GetID($ProjectID));
            if ($this->Form->AuthenticatedPostBack() === FALSE) {
                
            } else {
                if ($this->Form->Save()) {

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

        // TODO: Verify User
        // TODO: Verify the project isn't already deleted
        // TODO: Verify project has been deleted
        // TODO: Verify tasks for related project have been deleted

        $ProjectID = GetValue(0, $this->RequestArgs, 0);

        if ($ProjectID > 0 && Gdn::Session()->IsValid()) {

            $ProjectModel = $this->ProjectModel;

            $ProjectModel->Delete($ProjectID);

            $this->TaskModel->Delete(0, $ProjectID);

            Redirect('index.php?p=/projects');
        }
    }

    private function _GetTimestamps($UserOffset) {

        $this->Date = new DateTime(date("Y-m-d\TH:i:sO"));
        $this->UTCTimestamp = $this->Date->getTimestamp();
        $this->UserTimestamp = $this->UTCTimestamp + ($UserOffset * 3600);
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
