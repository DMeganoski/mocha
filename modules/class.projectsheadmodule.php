<?php

if (!defined('APPLICATION'))
    exit();

class ProjectsHeadModule extends Gdn_Module {

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
	return 'Content';
    }

    public function ToString() {
	$String = '';
	$Session = Gdn::Session();
	$permissions = $Session->User->Permissions;
	$this->admin = preg_match('/Garden.Settings.Manage/', $permissions);

	ob_start();

	include_once(PATH_APPLICATIONS . DS . 'mocha/views/modules/projecthead.php');

	$String = ob_get_contents();
	@ob_end_clean();
	return $String;
    }

    // Functional Methods

    /**
     * Method for determining controller and view names
     * @param type $ControllerName
     * @param type $ViewName 
     */
    public function SetView($ControllerName, $ViewName = 'Index') {

	$this->_ControllerName = $ControllerName;
	$this->_ViewName = $ViewName;
    }

}
