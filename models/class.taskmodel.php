<?php

if (!defined('APPLICATION'))
    exit();

class TaskModel extends Gdn_Model {

    public $Uses = array('ProjectModel');

    public function __construct() {
        parent::__construct('Task');
    }

    public function Query() {
        $this->SQL->Select("t.*")
                ->From("Task t");
    }

    /**
     * Method for retrieving tasks data for one or all projects
     * 
     * @param type $ID Requested TaskID, optional.
     * @return type 
     */
    public function Get($ID = FALSE) {
        $this->Query();
        if (!$ID) {
            $AllProjects = $this->SQL->Get();
            return $AllProjects;
        } else {
            $SelectedProject = $this->SQL->Where('t.TaskID', $ID)
                            ->Get()->FirstRow();

            return $SelectedProject;
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
        $Return = $this->SQL->Get();
        return $Return;
    }

    public function GetCount($Column, $Value) {

        if (is_array($Column)) {
            return $this->SQL
                            ->Select('t.TaskID', 'count', 'CountItems')
                            ->From('Task t')
                            ->Where($Column)
                            ->Get()->FirstRow()->CountItems;
        } else {
            return $this->SQL
                            ->Select('t.TaskID', 'count', 'CountItems')
                            ->From('Task t')
                            ->Where($Column, $Value)
                            ->Get()->FirstRow()->CountItems;
        }
    }

    public function Update($Set, $Where = FALSE) {
        if (!is_array($Set))
            return NULL;
        $this->DefineSchema();

        $this->SQL->Update('Task')
                ->Set($Set);

        if ($Where != FALSE)
            $this->SQL->Where($Where);

        $this->SQL->Put();
    }

    public function Delete($TaskID) {

        $Return = $this->SQL->Delete('Task', array('TaskID' => $TaskID));

        return $Return;
    }

    public function GetWhereGreater($ProjectID, $Timestamp) {

        $Return = $this->SQL->Query(
                "SELECT * FROM Task
                WHERE ProjectID = $ProjectID 
                and (`Timestamp` >= $Timestamp)
                ORDER BY Timestamp asc"
                )->Get();
        
        return $Return;
    }

    /* ---------------------------- Functional Methods --------------------- */

    // Not for retrieving data, but modifying it, ect.

    public function NestTask($ParentID, $ChildID) {

        $this->Query();
        // check to see the parent isn't currently a child
        $ParentData = $this->SQL->Where("TaskID", $ParentID)->Get()->FirstRow();
        if ($ParentData->Type == 0) {
            $this->Update(
                    // set fields
                    array('RelatedID' => $ParentID, 'Type' => 1),
                    // where
                    array('TaskID', $ChildID)
            );
        }
    }

    public function AddActivity($ActivityUserID, $ActivityType, $Story = '', $RegardingUserID = '', $CommentActivityID = '', $Route = '', $SendEmail = '') {
        // Build the story for the activity.
        $ProjectModel = new ProjectModel();
        $ProjectData = $ProjectModel->Get($ProjectID);
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

}
