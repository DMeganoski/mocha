<?php

if (!defined('APPLICATION'))
    exit();

/**
 * This is the controller for viewing a list of all available projects.
 */
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
        if (C('Garden.RewriteUrls')) {
            $this->HomeLink = "/";
        } else {
            $this->HomeLink = "/index.php?p=/";
        }
        
	// Call Gdn_Controller's Initialize() as well.
	parent::Initialize();
    }

    public function Index() {
        
        // TODO: Add check if user has projects, decided default page.
	$this->View = 'all';
        $this->All();
    }
    
    public function All() {
        // Configure Side Module
	$ProjectsSideModule = new ProjectsSideModule();
	$ProjectsSideModule->SetView('Projects');
		
	// Add modules to page
	$this->AddModule($ProjectsSideModule);
	
	// TODO: Add for each to to sort through projects that the user can see.

	$this->Projects = $this->ProjectModel->Get();
	
	Gdn::UserModel()->JoinUsers($this->Projects, array('InsertUserID'));
	
	$this->Render();
    }
    
    public function User() {
        
        $ProjectsSideModule = new ProjectsSideModule();
	$ProjectsSideModule->SetView('Projects');
	
	
	// Add modules to page
	$this->AddModule($ProjectsSideModule);
	
	// TODO: Add for each to to sort through projects that the user can see.

	$this->Projects = $this->ProjectModel->GetWhere("InsertUserID", $this->User->UserID);
        
        $this->View = 'all';
        $this->Render();
        
    }

}
