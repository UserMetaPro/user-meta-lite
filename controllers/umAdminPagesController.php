<?php

if ( ! class_exists( 'umAdminPagesController' ) ) :
class umAdminPagesController {
    
    function __construct() {      
        add_action( 'admin_menu',    array( $this, 'menuItem' ) );
        add_action( 'admin_notices', array( $this, 'umAdminNotices' ) );
    }
    
    
    function menuItem() {
        global $userMeta, $umAdminPages;
   
        $parentSlug = 'usermeta';
        
        // Top Level Menu
        add_utility_page( 'User Meta', 'User Meta', 'manage_options', $parentSlug, array( $this, 'fields_editor_init' ), $userMeta->assetsUrl . 'images/ump-icon.png' ); 
        
        $pages  = $userMeta->adminPages();
        $isPro  = $userMeta->isPro();
        foreach( $pages as $key => $page ) {
            $menuTitle  = ( ! $isPro && ! $page['is_free'] ) ? '<span style="opacity:.5;filter:alpha(opacity=50);">' . $page['menu_title'] . '</span>' : $page['menu_title'];
            $callBack   = ! empty( $page['callback'] ) ? $page['callback'] : array( $this, $key . '_init' );
            $hookName   = add_submenu_page( $parentSlug, $page['page_title'], $menuTitle, 'manage_options', $page['menu_slug'], $callBack );
            add_action( 'load-' . $hookName, array( $this, 'onLoadUmAdminPages' ) );
            $pages[$key]['hookname'] = $hookName;
        }
        
        $umAdminPages = $pages;
        
        add_filter( 'plugin_action_links_' . $userMeta->pluginSlug, array( &$this, 'pluginSettingsMenu' ) );
    }
    
    
    function onLoadUmAdminPages() {
        do_action( 'user_meta_load_admin_pages' );
    }
    
    
    function pluginSettingsMenu( $links ) {
        global $userMeta;
        
        $settings_link = '<a href="'. get_admin_url(null, 'admin.php?page=user-meta-settings') .'">' . __( 'Settings', $userMeta->name ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
    
    
    function umAdminNotices() {
        global $current_screen;
        
        if( $current_screen->parent_base == 'usermeta' ) 
            do_action( 'user_meta_admin_notices' );
    }
    
    
    function fields_editor_init() {
        global $userMeta;        
        
         $userMeta->enqueueScripts( array(
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-mouse',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
             
            'user-meta',
            'user-meta-admin',
            'validationEngine',
        ) );                      
        $userMeta->runLocalization();     
  
        $fields = $userMeta->getData( 'fields' );   
        $userMeta->render( 'fieldsEditorPage', array(
            'fields'    => $fields
        ) );       
    }
    
    
    function forms_editor_init() {
        global $userMeta;     
        
         $userMeta->enqueueScripts( array(
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-mouse',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
             
            'user-meta',
            'user-meta-admin',
            'validationEngine',
        ) );                      
        $userMeta->runLocalization();
        
        $forms  = $userMeta->getData( 'forms' );           
        $fields = $userMeta->getData( 'fields' );   
        $userMeta->render( 'formsEditorPage', array(
            'forms'     => $forms,
            'fields'    => $fields
        ) );        
    }
    
    
    function email_notification_init() {
        global $userMeta;
        
        $userMeta->enqueueScripts( array(
            'jquery-ui-core',
            'jquery-ui-tabs',
            'jquery-ui-all',

            'user-meta',
            'user-meta-admin',
        ) );                      
        $userMeta->runLocalization();
                
        $data = array(
            'registration'          => $userMeta->getEmailsData( 'registration' ), 
            'profile_update'        => $userMeta->getEmailsData( 'profile_update' ),
            'activation'            => $userMeta->getEmailsData( 'activation' ),
            'deactivation'          => $userMeta->getEmailsData( 'deactivation' ),
            'email_verification'    => $userMeta->getEmailsData( 'email_verification' ),
            'lostpassword'          => $userMeta->getEmailsData( 'lostpassword' ),
            'reset_password'        => $userMeta->getEmailsData( 'reset_password' ),
        );
        
        $userMeta->renderPro( 'emailNotificationPage', array(
            'data'      => $data,
            'roles'     => $userMeta->getRoleList(),
        ), 'email' );         
    }
    
    
    function export_import_init() {
        global $userMeta;     
        
        $userMeta->enqueueScripts( array( 
            'jquery-ui-core',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
            'jquery-ui-datepicker',
            'jquery-ui-dialog',
            'jquery-ui-progressbar',
            
            'user-meta',  
            'user-meta-admin',
            'jquery-ui-all',
            'fileuploader',            
        ) );                      
        $userMeta->runLocalization();
        
        $cache = $userMeta->getData( 'cache' );            
        $csvCache = @$cache['csv_files'];
                           
        //importPage            
        $userMeta->renderPro( 'importExportPage', array(
            'csvCache'  => $csvCache,
            'maxSize'   => (20 * 1024 * 1024), //20M
        ), 'exportImport' );          
    }
    
    
    function settings_init() {
        global $userMeta;
        
        self::moreExecution();
        
        $userMeta->enqueueScripts( array(
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-mouse',
            'jquery-ui-sortable',
            'jquery-ui-draggable',
            'jquery-ui-droppable',
            'jquery-ui-accordion',
            'jquery-ui-tabs',
            'jquery-ui-all',

            'user-meta',
            'user-meta-admin',
            'validationEngine',
        ) );                      
        $userMeta->runLocalization();
        
        $settings   = $userMeta->getData( 'settings' );
        $forms      = $userMeta->getData( 'forms' );
        $fields     = $userMeta->getData( 'fields' );
        $default    = $userMeta->defaultSettingsArray();                        
        
        $userMeta->render( 'settingsPage', array(
            'settings'  => $settings,
            'forms'     => $forms,
            'fields'    => $fields,
            'default'   => $default,
        ));    
        
        
    }
    
    
    function advanced_init() {
        global $userMeta;
        
        $userMeta->enqueueScripts( array(
            'jquery-ui-core',
            'jquery-ui-tabs',
            'jquery-ui-all',

            'user-meta',
            'user-meta-admin',
            'bootstrap',
            'bootstrap-multiselect',
            'multiple-select'
        ) );                      
        $userMeta->runLocalization();                  
        
        $userMeta->renderPro( 'advancedPage', array(
            'advanced' => $userMeta->getData( 'advanced' )
        ), 'advanced' );
    }
    
    
    function moreExecution() {
        $actionType = ! empty( $_GET['action_type'] ) ? $_REQUEST['action_type'] : false;
        if ( $actionType == 'notice' ){
            if ( ! empty($_GET['action_name'] ) )
                $_GET['action_name'] == 'dismiss_translation_notice' ? delete_option( 'user_meta_show_translation_update_notice' ) : false;
        }
    }
    

}
endif;