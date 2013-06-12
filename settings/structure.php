<?php

if (!defined('APPLICATION'))
    exit(); // Make sure this file can't get accessed directly

if (!isset($Drop))
    $Drop = FALSE; // Safe default - Set to TRUE to drop the table if it already exists.

if (!isset($Explicit))
    $Explicit = FALSE; // Safe default - Set to TRUE to remove all other columns from table.

$Database = Gdn::Database();
$SQL = $Database->SQL(); // To run queries.
$Construct = $Database->Structure(); // To modify and add database tables.
$Validation = new Gdn_Validation(); // To validate permissions (if necessary).

/*
 * Construct the primary table for general project data
 * 
 * InsertUserID: the user who created the project
 * Title: the title of the project
 * Description: long project description
 * DateInserted: date/time project was created
 * DateUpdated: last day/time project was actively changed/updated
 * DateDue: goal set for project completion
 * 
 */
$Construct->Table('Project')
        ->PrimaryKey('ProjectID')
        ->Column('InsertUserID', 'int', FALSE)
        ->Column('Title', 'varchar(50)', TRUE)
        ->Column('Description', 'text', TRUE)
        ->Column('DateInserted', 'date', TRUE)
        ->Column('DateUpdated', 'date', TRUE)
        ->Column('DateDue', 'date', TRUE)
        ->Column('DueTimestamp', 'int', TRUE)
        ->Column('PaypalCode', 'text', TRUE)
        ->Column('Admin', 'text', TRUE)
        ->Column('Team', 'text', TRUE)
        ->Column('Followers', 'text', TRUE)
        ->Column('SourceHost', 'varchar(50)', TRUE)
        ->Column('SourceHTTP', 'varchar(255)', TRUE)
        ->Set($Explicit, $Drop);

/*
 * Table for tasks. All are relative to a project.
 * 
 * Type: The Type column defines the type of task.
 * 	0 = Unnested Task, 1 = Nested Task
 * 	2 = Milestone, 3 = Deliverable
 * ProjectID: the related project ID
 * InsertUserID: the user who created the task
 * ParentID: the ID of the task this task is nested in
 * Title: the name of the task
 * Description: long description of the task
 * DateInserted: date/time task was created
 * DateUpdatead: date/time task or child was updated.
 * DateDue: date/time set for completion
 * 
 */
$Construct->Table('Task')
        ->PrimaryKey('TaskID')
        /*
         * 
         */
        ->Column('Type', 'int', 0)
        ->Column('ProjectID', 'int', FALSE)
        ->Column('InsertUserID', 'int', FALSE)
        ->Column('ParentID', 'int', TRUE)
        ->Column('Title', 'varchar(50)', TRUE)
        ->Column('Description', 'text', TRUE)
        ->Column('DateInserted', 'date', TRUE)
        ->Column('DateUpdated', 'date', TRUE)
        ->Column('DateDue', 'date', TRUE)
        ->Column('DueTimestamp', 'int', TRUE)
        /* PREP: Assigned User
          ->Column('AssignUserID','int', FALSE)
         */
        ->Set($Explicit, $Drop);

$PermissionModel = Gdn::PermissionModel();

$PermissionModel->Database = $Database;

$PermissionModel->SQL = $SQL;

// Define some global permissions.
$PermissionModel->Define(array(
    'Mocha.Projects.Manage'
));

// Set the intial member permissions.
$PermissionModel->Save(array(
    'RoleID' => 8,
    'Mocha.Projects.Manage' => 0
));

// Set the initial administrator permissions.
$PermissionModel->Save(array(
    'RoleID' => 16,
    'Mocha.Projects.Manage' => 1
));
// Insert some activity types
///  %1 = ActivityName
///  %2 = ActivityName Possessive: Username
///  %3 = RegardingName
///  %4 = RegardingName Possessive: Username, his, her, your
///  %5 = Link to RegardingName's Wall
///  %6 = his/her
///  %7 = he/she
///  %8 = RouteCode & Route
//Created a new project
//if ($SQL->GetWhere('ActivityType', array('Name' => 'CreateProject'))->NumRows() == 0)
$SQL->Replace('ActivityType', array('AllowComments' => '1', 'Name' => 'CreateProject', 'FullHeadline' => '%1$s created a %8$s.', 'ProfileHeadline' => '%1$s added a %8$s.', 'RouteCode' => 'project', 'Public' => '1'), array('Name' => 'CreateProject'));
// Added Task to project
//if ($SQL->GetWhere('ActivityType', array('Name' => 'CreateProjectTask'))->NumRows() == 0)
$SQL->Replace('ActivityType', array('AllowComments' => '1', 'Name' => 'CreateProjectTask', 'FullHeadline' => '%1$s created a task for a %8$s.', 'ProfileHeadline' => '%1$s created a %8$s for.', 'RouteCode' => 'project', 'Public' => '1'), array('Name' => 'CreateProjectTask'));

//if ($SQL->GetWhere('ActivityType', array('Name' => 'CreateProjectTask'))->NumRows() == 0)
$SQL->Replace('ActivityType', array('AllowComments' => '1', 'Name' => 'CreateProjectTask', 'FullHeadline' => '%1$s created a task for a %8$s.', 'ProfileHeadline' => '%1$s created a %8$s for.', 'RouteCode' => 'project', 'Public' => '1'), array('Name' => 'CreateProjectTask'));

/* PREP: Useful later when users can invite to projects
if ($SQL->GetWhere('ActivityType', array('Name' => 'JoinInvite'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'JoinInvite', 'FullHeadline' => '%1$s accepted %4$s invitation for membership.', 'ProfileHeadline' => '%1$s accepted %4$s invitation for membership.'));
if ($SQL->GetWhere('ActivityType', array('Name' => 'JoinApproved'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array('AllowComments' => '1', 'Name' => 'JoinApproved', 'FullHeadline' => '%1$s approved %4$s membership application.', 'ProfileHeadline' => '%1$s approved %4$s membership application.'));
$SQL->Replace('ActivityType', array('AllowComments' => '1', 'FullHeadline' => '%1$s created an account for %3$s.', 'ProfileHeadline' => '%1$s created an account for %3$s.'), array('Name' => 'JoinCreated'), TRUE);
*/