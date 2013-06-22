<?php

if (!defined('APPLICATION'))
    exit();

/**
 * 
 */
class ProjectModel extends Gdn_Model {

    public function __construct() {
	parent::__construct('Project');
    }

    /**
     * Method for building query of all project data 
     */
    public function Query() {
	$this->SQL->Select("p.*")
		->From("Project p");
    }

    /**
     * Method for retrieving data for one or all projects
     * 
     * @param type $ID Requested ProjectID, optional.
     * @return type 
     */
    public function Get($ID = FALSE) {
	$this->Query();
	if ($ID !== FALSE) {
	    $SelectedProject = $this->SQL->Where('ProjectID', $ID)
		->Get()->FirstRow();

	    return $SelectedProject;
	} else {
	    $AllProjects = $this->SQL->Get();
	    return $AllProjects;
	}
    }

    public function GetWhere($Column, $Value = NULL) {
	$this->Query();
	if (is_array($Column)) {
	    foreach ($Column as $Col => $Val) {
		$this->SQL->Where($Col, $Val);
	    }
	} else {
	    if ($Value != NULL) {
		$this->SQL->Where($Column, $Value);
	    }
	}
	$Result = $this->SQL->Get();
	return $Result;
    }

    public function GetCount($Column, $Value) {
	
	if(is_array($Column)) {
	    return $this->SQL
		    ->Select('p.ProjectID', 'count', 'CountItems')
		    ->From('Project p')
		    ->Where($Column)
		    ->Get()->FirstRow()->CountItems;
	} else {
	    return $this->SQL
		    ->Select('p.ProjectID', 'count', 'CountItems')
		    ->From('Project p')
		    ->Where($Column, $Value)
		    ->Get()->FirstRow()->CountItems;
	}
    }

    public function Update($Set, $Where = FALSE) {
	if (!is_array($Set))
	    return NULL;
	$this->DefineSchema();

	$this->SQL->Update('Project')
		->Set($Set);

	if ($Where != FALSE)
	    $this->SQL->Where($Where);

	$this->SQL->Put();
    }

    /* ------------------- Functional Methods --------------------------------- */

    public function NestTask($ParentID, $ChildID) {

	$this->Query();
	// check to see the parent isn't currently a child
	$ParentData = $this->SQL->Where("TaskID", $ParentID)->Get()->FirstRow();
	if ($ParentData->Type > 0) {
	    
	}
    }
    
    public function AddActivity($ActivityUserID, $ActivityType, $Story = '', $RegardingUserID = '', $CommentActivityID = '', $Route = '', $SendEmail = '') {
        // Build the story for the activity.
        $ProjectData = $this->Get($ProjectID);
        $ProjectName = $ProjectData->Title;
        //$Story = sprintf(T('Added Task: %s, Source: %s'), $ProjectName, $ProjectName);

        $ActivityTypeRow = $this->SQL
                ->Select('ActivityTypeID, Name, Notify')
                ->From('ActivityType')
                ->Where('Name', $ActivityType)
                ->Get()
                ->FirstRow();

        $ActivityTypeID = $ActivityTypeRow->ActivityTypeID;

        $Fields = array('ActivityTypeID' => $ActivityTypeID,
            'ActivityUserID' => $ActivityUserID
        );
        if ($Story != '')
            $Fields['Story'] = $Story;

        if ($Route != '')
            $Fields['Route'] = $Route;

        if (is_numeric($RegardingUserID))
            $Fields['RegardingUserID'] = $RegardingUserID;

        if (is_numeric($CommentActivityID))
            $Fields['CommentActivityID'] = $CommentActivityID;

        if (!isset($Fields['DateInserted']))
            $Fields['DateInserted'] = Gdn_Format::ToDateTime();


        $ID = $this->SQL->Insert('Activity', $Fields);
        //$ActivityModel = new ActivityModel();
        //$ActivityModel->Add(Gdn::Session()->UserID, 'CreateProjectTask', $Story);
        return $ID;
    }
    
    public function GetActivity($ProjectID) {
	$Route = "project/".$ProjectID;
	
	$this->SQL->Select('a.*')
		->From('Activity a')
		->Where("Route", $Route);
	$Result = $this->SQL->Get();
	return $Result;
	
    }

}