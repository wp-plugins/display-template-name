<?php 
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

var $adminOptionsName = "DisplayTemplateNameAdminOptions";
delete_option($adminOptionsName);