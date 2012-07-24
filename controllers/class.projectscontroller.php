<?php

if (!defined('APPLICATION'))
    exit();

class ProjectsController extends MochaController {

    /** @var array List of objects to prep. They will be available as $this->$Name. */
    public $Uses = array('Form', 'ProjectModel');

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
	}

	// Call Gdn_Controller's Initialize() as well.
	parent::Initialize();
    }

    public function Index() {
	
	// Configure Side Module
	$ProjectsSideModule = new ProjectsSideModule();
	$ProjectsSideModule->SetView('Projects');
	
	// Configure Head Module
	$ProjectsHeadModule = new ProjectsHeadModule();
	
	// Add modules to page
	$this->AddModule($ProjectsSideModule);
	$this->AddModule($ProjectsHeadModule);

	$this->Projects = $this->ProjectModel->Get();
	
	Gdn::UserModel()->JoinUsers($this->Projects, array('InsertUserID'));
	
	$this->Render();
    }

}
