<?php
/**
 * An associative array of information about this application.
 *
 * Be sure to change the 'Skeleton' in the array's key to your app's short name.
 */
$ApplicationInfo['Mocha'] = array(
   'Description' => "Mocha is a project management application built on the garden platform. It is intended to be a miracle solution to managing development of mulitple software applications.",
   'Version' => '1.0',
   'RegisterPermissions' => FALSE, // Array of permissions that should be added to the application when it is installed.
   'SetupController' => 'setup',
   'AllowEnable' => TRUE, // Remove this when you create your own application (leaving it will make it so the application can't be enabled)
   'Author' => "Darryl Meganoski",
   'AuthorEmail' => 'dmeganoski@sorealsolutions.com',
   'AuthorUrl' => 'http://sorealsolutions.com',
   'License' => 'GPL v2'
);