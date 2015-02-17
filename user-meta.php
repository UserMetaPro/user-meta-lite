<?php
/*
Plugin Name: User Meta
Plugin URI: http://user-meta.com
Description: User management plugin. Frontend user profile, user egistration with extra fields. Login widget, user import, user redirection, email verification, admin approval, frontend lost-reset passwod and many more.
Author: Khaled Hossain
Version: 1.1.6
Author URI: http://khaledsaikat.com
*/

if ( realpath( __FILE__ ) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    exit( 'Please don\'t access this file directly.' );
}
require_once ( 'framework/init.php' );


if ( ! class_exists( 'userMeta' ) ) :
class userMeta extends pluginFramework {
    
    public $title;
    public $version;
    
    public $name        = 'user-meta';
    public $prefix      = 'um_';  
    public $prefixLong  = 'user_meta_';
    public $website     = 'http://user-meta.com';
  
    function __construct() {
        $this->pluginSlug       = plugin_basename(__FILE__);
        $this->pluginPath       = dirname( __FILE__ );
        $this->file             = __FILE__;
        $this->modelsPath       = $this->pluginPath . '/models/';
        $this->controllersPath  = $this->pluginPath . '/controllers/';
        $this->viewsPath        = $this->pluginPath . '/views/';
        
        $this->pluginUrl        = plugins_url( '' , __FILE__ ); 
        $this->assetsUrl        = $this->pluginUrl  . '/assets/';  
        
        $pluginHeaders = array(
            'Name'              => 'Plugin Name',
            'Version'           => 'Version',
        );
        
        $pluginData = get_file_data( $this->file, $pluginHeaders );
        
        $this->title            = $pluginData['Name'];
        $this->version          = $pluginData['Version'];
                  
        //Load Plugins & Framework modal classes
        global $pluginFramework, $userMetaCache;
        $this->cacheName        = 'userMetaCache';
        $userMetaCache          = new stdClass;
        
        $this->loadModels( $this->modelsPath );
        $this->loadModels( $pluginFramework->modelsPath );                                     
    }

}
endif;

global $userMeta;
$userMeta = new userMeta;
$userMeta->init();