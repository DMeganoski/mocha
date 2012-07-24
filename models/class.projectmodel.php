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
    
    public function GetActivity($ProjectID) {
	$Route = "project/".$ProjectID;
	
	$this->SQL->Select('a.*')
		->From('Activity a')
		->Where("Route", $Route);
	$Result = $this->SQL->Get();
	return $Result;
	
    }

}