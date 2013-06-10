<?php

if (!defined('APPLICATION'))
    exit(); // Make sure this file can't get accessed directly

/**
 * Hooks for other applications
 * 
 * TODO: add profile tab and page 
 */
class MochaHooks implements Gdn_IPlugin {

    /**
     * Add the main menu link
     * @param type $Sender
     */
    
    
    public function Base_Render_Before($Sender) {
        
        if (C(Garden.RewriteUrls)) {
            $HomeLink = "/";
        } else {
            $Homelink = "/index.php?p=/";
        }
        $Sender->SetData('projects_link',
                "<a href='".$HomeLink."projects'>".T('Projects')."</a>"
                );
	if ($Sender->Menu) {
	    $Sender->Menu->AddLink('Projects', T('Projects'), '/projects', FALSE, array('class' => 'Projects', 'Standard' => TRUE));
	}
    }
    
    public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
	  $Menu->AddItem('Projects', T('Projects'));
      $Menu->AddLink('Projects', T('G Categories'), 'mocha/settings/managecategories', 'Garden.Settings.Manage');
      $Menu->AddLink('Projects', T('G Flood Control'), 'mocha/settings/floodcontrol', 'Garden.Settings.Manage');
      $Menu->AddLink('Projects', T('G Advanced'), 'mocha/settings/advanced', 'Garden.Settings.Manage');
   }
   
   public function ProfileController_AddProfileTabs_handler(&$Sender) {

	$Sender->AddProfileTab('projects', "/profile/projects/".$Sender->User->UserID."/".Gdn_Format::Url($Sender->User->Name), 'Projects', 'Projects');
}

   /*
    * Create a list of user uploaded items in the profile
    */
	public function ProfileController_Projects_Create(&$Sender, $params) {

		$Sender->AddCssFile('/applications/mocha/design/mocha.profile.css');
		$Sender->UserID = ArrayValue(0, $Sender->RequestArgs, '');
		$Sender->UserName = ArrayValue(1, $Sender->RequestArgs, '');
		$ProjectModel = new ProjectModel();
		if (Gdn::Session()->UserID == $Sender->UserID && $ProjectModel->GetCount(array('InsertUserID' => $Sender->UserID)) > 0)
			$Sender->Uploads = $ProjectModel->GetWhere('InsertUserID', $Sender->UserID);
		$Sender->GetUserInfo($Sender->UserID, $Sender->UserName);
		$Sender->SetTabView('projects', PATH_APPLICATIONS.DS.'mocha/views/profile'.DS.'projects.php/', 'Profile', 'Dashboard');

		$Sender->HandlerType = HANDLER_TYPE_NORMAL;
		$Sender->Render();
	}

    /**
     * Special function automatically run upon clicking 'Enable' on your application.
     * Change the word 'mocha' anywhere you see it.
     */
    public function Setup() {
	// You need to manually include structure.php here for it to get run at install.
	include(PATH_APPLICATIONS . DS . 'mocha' . DS . 'settings' . DS . 'structure.php');

	// Stores a value in the config to indicate it has previously been installed.
	// You can use if(C('Mocha.Setup', FALSE)) to test whether to repeat part of your setup.
	SaveToConfig('Mocha.Setup', TRUE);
    }

    /**
     * Special function automatically run upon clicking 'Disable' on your application.
     */
    public function OnDisable() {
	// Optional. Delete this if you don't need it.
    }

    /**
     * Special function automatically run upon clicking 'Remove' on your application.
     */
    public function CleanUp() {
	// Optional. Delete this if you don't need it.
    }

}