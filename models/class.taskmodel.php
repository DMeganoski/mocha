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

    /**
     * 
     * @param type $ProjectID
     * @param type $Timestamp
     * @param type $Direction - Newer or older than timestamp, or irrelivent
     * @return type
     */
    public function CountTasks($ProjectID = NULL, $Timestamp = 0, $Direction = 2) {

        if ($ProjectID !== NULL) {
            switch($Direction) {
                case 0: // Overdue
                    $Return = $this->SQL->Select('t.TaskID', 'count', 'CountItems')
                        ->From('Task t')
                        ->Where('ProjectID', $ProjectID)
                        ->Where("DueTimestamp <=", $Timestamp - 1)
                        ->Where("DueTimestamp >=", 1)
                        ->Get()->FirstRow()->CountItems;
                    break;
                case 1: // Today
                    $Return = $this->SQL->Select('t.TaskID', 'count', 'CountItems')
                            ->From('Task t')
                            ->Where("DueTimestamp >=", $Timestamp)
                            ->Where("DueTimestamp <=", $Timestamp + (24*60*60) - 1)
                            ->Where('ProjectID',$ProjectID)
                            //->Where("DueTimestamp >=", 1)
                            ->Get()->FirstRow()->CountItems;
                    break;
                case 2: // Future TODO: Should I include today's? Hmmm...
                    $Return = $this->SQL->Select('t.TaskID', 'count', 'CountItems')
                            ->From('Task t')
                            ->Where("DueTimestamp >=", $Timestamp)
                            ->Where('ProjectID',$ProjectID)
                            //->Where("DueTimestamp >=", 1)
                            ->Get()->FirstRow()->CountItems;
                    break;
                case 3: // Both Directions (all)
                default:
                   $Return = $this->SQL->Select('t.TaskID', 'count', 'CountItems')
                            ->From('Task t')
                            ->Where('ProjectID',$ProjectID)
                            ->Get()->FirstRow()->CountItems;
                    break;
            }
            return $Return;
        } else {
            return 999;
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

        $Return = $this->SQL->Put();
    }

    /**
     * 
     * @param type $TaskID - Specific task to delete, irrelivent with projectid 
     * @param type $ProjectID - Delete all tasks for given project
     * @return type
     */
    public function Delete($TaskID, $ProjectID = NULL) {
        
        if ($ProjectID != NULL) {
            $Return = $this->SQL->Delete('Task', array('ProjectID' => $ProjectID));
        } else {
            $Return = $this->SQL->Delete('Task', array('TaskID' => $TaskID));
        }
        return $Return;
    }

    /*public function GetWhereGreater($ProjectID, $Timestamp) {
        
       

        $Return = $this->SQL->Query(
                "SELECT * FROM GDN_Task
                WHERE ProjectID = $ProjectID 
                and (`DateDue` >= $Timestamp)
                ORDER BY DateDue asc"
                )->Get()->FirstRow()->CountItems;
        
        return $Return;
    }*/

    /* ---------------------------- Functional Methods --------------------- */

    // Not for retrieving data, but modifying it, ect.

    public function NestTask($ParentID, $ChildID) {

        $this->Query();
        // check to see the parent isn't currently a child
        $ParentData = $this->SQL->Where("TaskID", $ParentID)->Get()->FirstRow();
        if ($ParentData->Type != 0 && $ParentData->Type != 1) {
            $Return = $this->SQL->Update('Task')
                    // set fields
                    ->Set('ParentID', $ParentID)
                    ->Set('Type', 0)
                    // where
                    ->Where('TaskID', $ChildID)->Put();
           
            
           return $Return;
        } else {
           return 'Nope';
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
