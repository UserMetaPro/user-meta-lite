<?php

if ( ! class_exists( 'umSupportArray' ) ) :
class umSupportArray {
    
    function controllersOrder() {
        return array(); // This array causes to load the file twice.
        return array(
            'umPreloadsController',
            'umPreloadsProController',
            'umBackendProfileController',
            'umShortcodesController',
            'umFieldsController',
            'umFormsController',
            'umEmailNotificationController',
            'umExportImportController',
            'umSettingsController'          
        );
    }
    
    function adminPages() {
        global $userMeta;

        $pages = array(
            'fields_editor' => array(
                'menu_title'    => __( 'Fields Editor', $userMeta->name ),
                'page_title'    => __( 'User Meta Fields Editor', $userMeta->name ),
                'menu_slug'     => 'usermeta',
                'position'      => 0,
                'is_free'       => true,
            ),
            'forms_editor'  => array(
                'menu_title'    => __( 'Forms Editor', $userMeta->name ),
                'page_title'    => __( 'User Meta Forms Editor', $userMeta->name ),
                'menu_slug'     => 'user-meta-form-editor',
                'position'      => 1,
                'is_free'       => true,
            ),
            'email_notification' => array(
                'menu_title'    => __( 'Email Notification', $userMeta->name ),
                'page_title'    => __( 'Email Notification', $userMeta->name ),
                'menu_slug'     => 'user-meta-email',
                'position'      => 2,
                'is_free'       => false,
            ),
            'export_import' => array(
                'menu_title'    => __( 'Export & Import', $userMeta->name ),
                'page_title'    => __( 'Export & Import', $userMeta->name ),
                'menu_slug'     => 'user-meta-import-export',
                'position'      => 3,
                'is_free'       => false,
            ),  
            'settings'      => array(
                'menu_title'    => __( 'Settings', $userMeta->name ),
                'page_title'    => __( 'User Meta Settings', $userMeta->name ),
                'menu_slug'     => 'user-meta-settings',
                'position'      => 4,
                'is_free'       => true,
            ),            
        );

        $pages = apply_filters( 'user_meta_admin_pages', $pages );
        uasort( $pages, array( $userMeta, 'sortByPosition' ) );
        
        return $pages;
    }
    
    function hooksList() {
        return array(          
            'group_login'               => 'Login', 
            'login_form_login'          => false,   //action
            'login_redirect'            => false,   //filter
            'wp_login_errors'           => false,   //filter
            'login_form'                => false,   //action
            'login_form_logout'         => false,   //action
            
            'group_lostpassword'        => 'Lost Password',
            'login_form_lostpassword'   => false,   //action
            'login_form_retrievepassword' => false, //action
            'lostpassword_post'         => false,   //action
            'retrieve_password'         => false,   //action
            'allow_password_reset'      => false,   //filter
            'retrieve_password_key'     => false,   //action
            'retrieve_password_title'   => false,   //filter
            'retrieve_password_message' => false,   //filter 
            'lostpassword_redirect'     => false,   //filter
            'lost_password'             => false,   //action
            'lostpassword_form'         => false,   //action
            
            'group_resetpass'           => 'Reset Password',
            'login_form_resetpass'      => false,   //action
            'login_form_rp'             => false,   //action
            'password_reset_key_expired'=> false,   //filter
            'validate_password_reset'   => false,   //action
            'password_reset'            => false,   //action
            'resetpass_form'            => false,   //action
            
            'group_register'            => 'User Registration',
            'login_form_register'       => false,   //action
            //'wp_signup_location'        => false,   //filter for multisite found in wp-login.php
            'registration_redirect'     => false,   //filter
            'register_form'             => false,   //action
            'user_registration_email'   => false,   //filter
            'register_post'             => false,   //action
            'registration_errors'       => false,   //filter
        );
    }
    
    /**
     * 
     * @param type $hookName
     * @param type $hookType: action | filter
     * @return boolean
     */
    function isHookEnable( $hookName, $args = array() ) {
        global $userMetaCache;
        
        if( empty( $userMetaCache->hooksList ) )
            $userMetaCache->hooksList = self::hooksList();
        
        $enable = ! empty( $userMetaCache->hooksList[ $hookName ] ) ? true : false;
 
        return apply_filters( "user_meta_wp_hook", $enable, $hookName, $args );
    }
    
    // Not in use since 1.1.6rc2
    function isFilterEnable( $hookName ) {
        $list = array( 
            'login_redirect'    => false,   //Commit
            'logout_redirect'   => true,    //Commit changed to user_meta_logout_redirect
            'registration_redirect' => true, // commit
        );
        
        $list = apply_filters( 'user_meta_filter_list', $list );
        
        if ( ! empty( $list[$hookName] ) )
            return true;
        return false;
    }
    
    // Not in use since 1.1.6rc2
    function isActionEnable( $hookName ) {
        $list = array( 
            'lostpassword_form'    => false,
        );
        
        $list = apply_filters( 'user_meta_action_list', $list );
        
        if ( ! empty( $list[$hookName] ) )
            return true;
        return false;
    }
    
    function enqueueScripts( $scripts=array() ) {
        global $userMeta;
        
        $jsUrl  = $userMeta->assetsUrl . 'js/';
        $cssUrl = $userMeta->assetsUrl . 'css/';
               
        $list = array(  
            'user-meta' => array(
                'user-meta.js' => '',
                'user-meta.css' => ''
            ),   
            'user-meta-admin' => array(
                'user-meta-admin.js' => ''
            ),   
            'jquery-ui-all' => array(
                'jquery.ui.all.css' => 'jqueryui/',
            ),
            'fileuploader' => array(
                'fileuploader.js'   => 'jquery/',
                'fileuploader.css'  => 'jquery/',
            ),
            'wysiwyg' => array(
                'jquery.wysiwyg.js' => 'jquery/',
                'wysiwyg.image.js'  => 'jquery/',
                'wysiwyg.link.js'   => 'jquery/',
                'wysiwyg.table.js'  => 'jquery/',
                'jquery.wysiwyg.css'=> 'jquery/',
            ),
            'timepicker' => array(
                'jquery-ui-timepicker-addon.js' => 'jqueryui/',
            ),
            'validationEngine' => array(
                'jquery.validationEngine-en.js' => 'jquery/',
                'jquery.validationEngine.js'    => 'jquery/',
                'validationEngine.jquery.css'   => 'jquery/',
            ),
            'password_strength' => array(
                'jquery.password_strength.js'   => 'jquery/',
            ),
            'placeholder' => array(
                'jquery.placeholder.js'   => 'jquery/',
            ),
            'multiple-select' => array(
                'jquery.multiple.select.js'  => 'jquery/',
                'multiple-select.css' => 'jquery/',
            ),
        );
        
        $list = apply_filters( 'user_meta_scripts', $list );
        
        $version = $userMeta->version;
    
        foreach ( $scripts as $script ) {
            if ( isset( $list[ $script ] ) ) {
                foreach ( $list[$script]  as $key => $val ) {
                    $file = $userMeta->fileinfo( $key );
                    if ( $file->ext == 'js' )
                        wp_enqueue_script( $file->name, $jsUrl . $val . $key, array( 'jquery' ), $version );  
                    elseif ( $file->ext == 'css' )
                        wp_enqueue_style( $file->name, $cssUrl . $val . $key, array(), $version );  
                }
            } else
                wp_enqueue_script( $script );
                       
        }      
                
    }    
    
    
    function umFields() {
        global $userMeta;
        
        $fieldsList = array(
        
            //WP default fields
            'user_login' => array(
                'title'         => __( 'Username', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true, 
            ),
            'user_email' => array(
                'title'         => __( 'Email', $userMeta->name ),
                'field_group'   => 'wp_default',
                'is_free'       => true,
            ),   
            'user_pass' => array(
                'title'         => __( 'Password', $userMeta->name ),
                'field_group'   => 'wp_default',
                'is_free'       => true,
            ),   
            /*'user_nicename' => array(
                'title'         => 'Nicename',
                'field_group'     => 'wp_default', 
            ), */            
            'user_url' => array(
                'title'         => __( 'Website', $userMeta->name ),
                'field_group'   => 'wp_default',
                'is_free'       => true,
            ),   
            'display_name' => array(
                'title'         => __( 'Display Name', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true, 
            ),   
            'nickname' => array(
                'title'         => __( 'Nickname', $userMeta->name ),
                'field_group'   => 'wp_default',   
                'is_free'       => true,
            ),   
            'first_name' => array(
                'title'         => __( 'First Name', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true,
            ),   
            'last_name' => array(
                'title'         => __( 'Last Name', $userMeta->name ),
                'field_group'   => 'wp_default',   
                'is_free'       => true,
            ),   
            'description' => array(
                'title'         => __( 'Biographical Info', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true, 
            ),   
            'user_registered' => array(
                'title'         => __( 'Registration Date', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true,
            ),   
            'role' => array(
                'title'         => __( 'Role', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true,
            ),   
            'jabber' => array(
                'title'         => __( 'Jabber', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true,
            ),   
            'aim' => array(
                'title'         => __( 'Aim', $userMeta->name ),
                'field_group'   => 'wp_default', 
                'is_free'       => true, 
            ),   
            'yim' => array(
                'title'         => __( 'Yim', $userMeta->name ),
                'field_group'   => 'wp_default',
                'is_free'       => true,
            ),   
            'user_avatar' => array(
                'title'         => __( 'Avatar', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => true,
            ),                       
            
            'blogname'  => is_multisite() ? array(
                'title'         => __( 'New Blog', $userMeta->name ),
                'field_group'   => 'wp_default',  
                'is_free'       => false,
            ) : null,
         
            //Standard Fields
            'text' => array(
                'title'         => __( 'Textbox', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,   
            ),   
            'textarea' => array(
                'title'         => __( 'Paragraph', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,   
            ),   
            'rich_text' => array(
                'title'         => __( 'Rich Text', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,   
            ),  
            'hidden' => array(
                'title'         => __( 'Hidden Field', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,   
            ),                       
            'select' => array(
                'title'         => __( 'Drop Down', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,   
            ), 
            'radio' => array(
                'title'         => __( 'Select One (radio)', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,  
            ), 
            'checkbox' => array(
                'title'         => __( 'Checkbox', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => true,  
            ),      
            'multiselect' => array(
                'title'         => __( 'Multi Select', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => false,  
            ),
            'datetime' => array(
                'title'         => __( 'Date / Time', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => false,
            ),                      
            'password' => array(
                'title'         => __( 'Password', $userMeta->name ),
                'field_group'   => 'standard', 
                'is_free'       => false,
            ),    
            'email' => array(
                'title'         => __( 'Email', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => false,
            ),             
            'file' => array(
                'title'         => __( 'File Upload', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => false,
            ), 
            'image_url' => array(
                'title'         => __( 'Image URL', $userMeta->name ),
                'field_group'   => 'standard',  
                'is_free'       => false,
            ),                   
            'phone' => array(
                'title'         => __( 'Phone Number', $userMeta->name ),
                'field_group'   => 'standard',  
                'is_free'       => false,
            ), 
            'number' => array(
                'title'         => __( 'Number', $userMeta->name ),
                'field_group'   => 'standard', 
                'is_free'       => false, 
            ), 
            'url' => array(
                'title'         => __( 'URL', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => false,
            ),                          
            'country' => array(
                'title'         => __( 'Country', $userMeta->name ),
                'field_group'   => 'standard',
                'is_free'       => false,
            ),      
            'custom' => array(
                'title'         => 'Custom Field',
                'field_group'   => 'standard',
                'is_free'       => false,
            ),       
            
            
            //Formating Fields
            'page_heading' => array(
                'title'         => __( 'Page Heading', $userMeta->name ),
                'field_group'   => 'formatting',  
                'is_free'       => false,
            ),                                     
            'section_heading' => array(
                'title'         => __( 'Section Heading', $userMeta->name ),
                'field_group'   => 'formatting',  
                'is_free'       => false,
            ),                                                                        
            'html' => array(
                'title'         => __( 'HTML', $userMeta->name ),
                'field_group'   => 'formatting',  
                'is_free'       => false,
            ),                                     
            'captcha' => array(
                'title'         => __( 'Captcha', $userMeta->name ),
                'field_group'   => 'formatting',  
                'is_free'       => false,
            ),                                                         
        );        
        return $fieldsList;                    
    }    
    
    /**
     * Supported action type
     * @return (array) if type=null || (bool) check for valid action type
     */
    function validActionType( $type=null ) {
        $types = array(
            'profile', 'registration', 'profile-registration', 'public', 'login'
        );  
        
        if ( empty( $type ) )
            return $types;
        
        return in_array( $type , $types ) ? true : false;
    }
    
    function loginByArray() {
        global $userMeta;
        return array( 
            'user_login' => __( 'Username', $userMeta->name ),
            'user_email' => __( 'Email', $userMeta->name ),
            'user_login_or_email' => __( 'Username or Email', $userMeta->name ),
        );
    }
    
    function defaultSettingsArray( $key=null ) {
        $settings = array(
        
            'general' => array(),
        
            'login' => array(
                'login_by'          => 'user_login',
                'login_form'        => "%login_form%\n%lostpassword_form%",
                'loggedin_profile' => array(
                    'administrator' => "<p>Hello %user_login%</p>\n<p>%avatar%</p>\n<p><a href=\"%admin_url%\">Admin Section</a></p>\n<p><a href=\"%logout_url%\">Logout</a></p>",
                    'subscriber'    => "<p>Hello %user_login%</p>\n<p>%avatar%</p>\n<p><a href=\"%logout_url%\">Logout</a></p>",
                ),
            ),  
            
            'registration' => array(
                'user_activation'    => 'auto_active',
            ), 
              
            'redirection'   => array(
                'login'     => array(
                    'administrator' => 'dashboard',
                    'subscriber'    => 'default',
                ),
                'logout'    => array(
                    'administrator' => 'default',
                    'subscriber'    => 'default',
                ),
                'registration'  => array(
                    'administrator' => 'default',
                    'subscriber'    => 'default',
                ),                
            ),
            
            'backend_profile'   => array(), 
            
            'misc'  => array(),
            
                
        );
        
        if ( $key )
            return @$settings[ $key ];
        return $settings;        
    }
    
    function defaultEmailsArray( $key = null ) {
        global $userMeta;

        $emails = array(
        
            'registration'  => array(
                'user_email'    => array(
                    'subject'   => '[%site_title%] Your username and password',
                    'body'      => "Username: %user_login% \r\nE-mail: %user_email% \r\nPassword: %password% \r\n\r\nLogin Url: %login_url%",

                ),
                'admin_email'    => array(
                    'subject'   => '[%site_title%] New User Registration',
                    'body'      => "Username: %user_login% \r\nEmail: %user_email% \r\n",
                ),                
            ),
            
            'activation'  => array(
                'user_email'    => array(
                    'subject'   => '[%site_title%] User Activated',
                    'body'      => "Congratulations! \r\n\r\nYour account is activated. You can login with your username and password. \r\n\r\nLogin Url: %login_url%",
                ),               
            ),
            
            'deactivation'  => array(
                'user_email'    => array(
                    'subject'   => '[%site_title%] User Deactivated',
                    'body'      => "Your account is deactivated by administrator. You can not login anymore to [%site_title%].",
                ),               
            ),
            
            'email_verification'  => array(
                'user_email'    => array(
                    'subject'   => '[%site_title%] Email verified',
                    'body'      => "Your email [%user_email%] is successfully verified on [%site_title%].",
                ),  
                'admin_email'    => array(
                    'subject'   => '[%site_title%] Email verified',
                    'body'      => "User email [%user_email%] is successfully verified on [%site_title%].",
                ),                                                
            ),                                    
            
            'lostpassword'  => array(
                'user_email'    => array(
                    'subject'   => "[%site_title%] Password Reset",
                    'body'      => "Someone requested that the password be reset for the following account:\r\n\r\n%site_url% \r\n\r\nUsername: %user_login% \r\n\r\nIf this was a mistake, just ignore this email and nothing will happen. \r\n\r\nTo reset your password, visit the following address: \r\n\r\n%reset_password_link% \r\n",
                ),                
            ), 
            
            'reset_password'  => array(
                //'user_email'    => array(
                    //'subject'   => '[%site_title%] Password Lost/Changed',
                    //'body'      => "Password Lost and Changed for user: %user_login% \r\n",
                    //'um_disable'=> true,
                //),
                'admin_email'    => array(
                    'subject'   => '[%site_title%] Password Lost/Changed',
                    'body'      => "Password Lost and Changed for user: %user_login% \r\n",
                    'um_disable'=> false,
                ),                
            ),  
            
            'profile_update'  => array(
                'user_email'    => array(
                    'subject'   => '[%site_title%] Profile Updated',
                    'body'      => "Hi %display_name%,\r\n\r\nYour profile have updated on site: %site_url%",
                    'um_disable'=> true,
                ),
                'admin_email'    => array(
                    'subject'   => '[%site_title%] Profile Updated',
                    'body'      => "Profile updated for Username: %user_login% ",
                    'um_disable'=> true,
                ),                
            ),            
            
        );
        
        if ( $key )
            return @$emails[ $key ];
        return $emails;         
    }
    
    function runLocalization() {
        global $userMeta, $userMetaCache;
        
        if ( empty( $userMetaCache->localizedStrings ) ) {                  
            $userMetaCache->localizedStrings = array(
                'user-meta' => array(
                    'get_pro_link'=> sprintf( __( 'Get pro version from %s to use this feature.', $userMeta->name ), $userMeta->website ),
                    'please_wait'=> __( 'Please Wait...', $userMeta->name ),
                ),
                'fileuploader' => array(
                    'upload'        => __( 'Upload', $userMeta->name ),
                    'drop'          => __( 'Drop files here to upload', $userMeta->name ),
                    'cancel'        => __( 'Cancel', $userMeta->name ),
                    'failed'        => __( 'Failed', $userMeta->name ),
                    'invalid_extension' => sprintf( __( '%1$s has invalid extension. Only %2$s are allowed.', $userMeta->name ), '{file}', '{extensions}' ),
                    'too_large'         => sprintf( __( '%1$s is too large, maximum file size is %2$s.', $userMeta->name ), '{file}', '{sizeLimit}' ),
                    'empty_file'        => sprintf( __( '%s is empty, please select files again without it.', $userMeta->name ), '{file}' ),
                ),
                'jquery.password_strength' => array(
                    'too_weak'      => __( 'Too weak', $userMeta->name ),
                    'weak'          => __( 'Weak password', $userMeta->name ),
                    'normal'        => __( 'Normal strength', $userMeta->name ),
                    'strong'        => __( 'Strong password', $userMeta->name ),
                    'very_strong'   => __( 'Very strong password', $userMeta->name ),
                ),
                'jquery.validationEngine-en' => array(
                    'required_field'    => __( '* This field is required', $userMeta->name ),
                    'required_option'   => __( '* Please select an option', $userMeta->name ),
                    'required_checkbox' => __( '* This checkbox is required', $userMeta->name ),
                    'min'               => __( '* Minimum ', $userMeta->name ),
                    'max'               => __( '* Maximum ', $userMeta->name ),
                    'char_allowed'      => __( ' characters allowed', $userMeta->name ),
                    'min_val'           => __( '* Minimum value is ', $userMeta->name ),
                    'max_val'           => __( '* Maximum value is ', $userMeta->name ),
                    'past'              => __( '* Date prior to ', $userMeta->name ),
                    'future'            => __( '* Date past ', $userMeta->name ),
                    'options_allowed'   => __( ' options allowed', $userMeta->name ),
                    'please_select'     => __( '* Please select ', $userMeta->name ),
                    'options'           => __( ' options', $userMeta->name ),
                    'not_equals'        => __( '* Fields do not match', $userMeta->name ),
                    'invalid_phone'     => __( '* Invalid phone number', $userMeta->name ),
                    'invalid_email'     => __( '* Invalid email address', $userMeta->name ),
                    'invalid_integer'   => __( '* Not a valid integer', $userMeta->name ),
                    'invalid_number'    => __( '* Not a valid number', $userMeta->name ),
                    'invalid_date'      => __( '* Invalid date, must be in YYYY-MM-DD format', $userMeta->name ),
                    'invalid_time'      => __( '* Invalid time, must be in hh:mm:ss format', $userMeta->name ),
                    'invalid_datetime'  => __( '* Invalid datetime, must be in YYYY-MM-DD hh:mm:ss format', $userMeta->name ),
                    'invalid_ip'        => __( '* Invalid IP address', $userMeta->name ),
                    'invalid_url'       => __( '* Invalid URL', $userMeta->name ),
                    'invalid_field'     => __( '* Invalid field', $userMeta->name ),
                    'numbers_only'      => __( '* Numbers only', $userMeta->name ),
                    'letters_only'      => __( '* Letters only', $userMeta->name ),
                    'no_special_char'   => __( '* No special characters allowed', $userMeta->name ),
                    'user_exists'       => __( '* This user is already taken', $userMeta->name ),
                    
                    'customRules'       => $userMeta->getCustomFieldRegex()
                ),

            );
        }
        
        foreach ( $userMetaCache->localizedStrings as $scriptName => $data ) {
            $objectName = str_replace( array( '.', '-' ), '_', $scriptName );
            wp_localize_script( $scriptName, $objectName, $data );
        }
     
    } 
    
    
    function getMsg( $key, $arg1=null, $arg2=null ) {
        global $userMeta;
        
        $msgs = self::msgs();
             
        if ( isset( $msgs[ $key ] ) ) {
            $msg = __( $msgs[ $key ] , $userMeta->name );
            
            if ( ! ( strpos($msg, '%s' ) === false ) )
                    $msg = sprintf( $msg, $arg1 );
            //elseif( ! (strpos($msg, '%2$s') === false ) )
                    //$msg = sprintf( $msg, $arg1, $arg2 );
            
            return apply_filters( 'user_meta_msg', $msg, $key, $arg1, $arg2 );
        }
            
        return false;
    }
    
    
    function msgs() {
        global $userMeta;

        $msgs = array(
            
            'group_1'                   => __( 'Login', $userMeta->name ),
            'login_pass_label'          => __( 'Password', $userMeta->name ),
            'login_remember_label'      => __( 'Remember me', $userMeta->name ),
            'login_button'              => __( 'Login', $userMeta->name ),
            'login_email_required'      => __( 'Both username and email are required', $userMeta->name ),
            'invalid_login'             => __( '<strong>ERROR</strong>: Invalid %s.', $userMeta->name ),
            'login_success'             => __( 'Login successfuly', $userMeta->name ),
            'registration_link'         => __( "Don't have an account? <a href=\"%s\">Sign up</a>", $userMeta->name ),
            
            'group_2'                   => __( 'Lost Password', $userMeta->name ),
            'lostpassword_link'         => __( 'Lost your password?', $userMeta->name ),
            'lostpassword_intro'        => __( 'Please enter your username or email address. You will receive a link to reset your password via email.', $userMeta->name ),
            'lostpassword_label'        => __( 'Username or E-mail', $userMeta->name ),
            'lostpassword_button'       => __( 'Get New Password', $userMeta->name ),
            
            'group_3'                   => __( 'Reset Password', $userMeta->name ),
            'resetpassword_heading'     => __( 'Reset Password', $userMeta->name ),
            'resetpassword_intro'       => __( 'Enter your new password below.', $userMeta->name ),
            'resetpassword_pass1_label' => __( 'New password', $userMeta->name ),
            'resetpassword_pass2_label' => __( 'Confirm new password', $userMeta->name ),
            'resetpassword_button'      => __( 'Reset Password', $userMeta->name ),
            'password_reset_mismatch'   => __( 'The passwords do not match.', $userMeta->name),
            'password_reseted'          => __( 'Your password has been reset.', $userMeta->name ),
            
            'group_4'                   => __( 'Profile', $userMeta->name ),
            'profile_required_loggedin' => __( 'Please login to access your profile.', $userMeta->name ),
            'public_non_lggedin_msg'    => __( 'Please login to access your profile.', $userMeta->name ),
            'profile_updated'           => __( 'Profile successfully updated.', $userMeta->name ),
            
            'group_5'                   => __( 'Registration', $userMeta->name ),
            'sent_verification_link'    => __( 'We have sent you a verification link to your email. Please complete your registration by clicking the link.', $userMeta->name ),
            'sent_link_wait_for_admin'  => __( 'We have sent you a verification link to your email. Please verify your email by clicking the link and wait for admin approval.', $userMeta->name ),
            'email_verified_pending_admin' => __( 'Your email is successfully verified. Please wait for admin approval.', $userMeta->name ),
            'wait_for_admin_approval'   => __( 'Please wait until an admin approves your account.', $userMeta->name ),
            'email_verified'            => __( 'Your email is successfully verified and the account has been activated. <a href="%s">Login now</a>', $userMeta->name ),
            'registration_completed'    => __( 'Registration successfully completed.', $userMeta->name ),

            'group_6'                   => __( 'Validation', $userMeta->name ),
            'validate_default'          => __( 'Invalid %s', $userMeta->name ),
            'validate_required'         => __( '%s is required', $userMeta->name ),
            'validate_email'            => __( 'Invalid email address', $userMeta->name ),
            'validate_equals'           => __( '%s does not match', $userMeta->name ),
            'validate_current_password' => __( 'Please provide valid current password', $userMeta->name ),
            'validate_current_required' => __( 'Current %s is required', $userMeta->name ),
            //'validate_unique'           => __( '%1$s: "%2$s" already takenM', $userMeta->name ),
            
            'group_7'                   => __( 'Misc', $userMeta->name ),
            'not_member_of_blog'        => __( 'User is not member of this site.', $userMeta->name ),
            'user_already_activated'    => __( 'User already activated', $userMeta->name ),
            'account_inactive'          => __( '<strong>ERROR:</strong> your account is inactive', $userMeta->name ),
            'account_pending'           => __( '<strong>ERROR:</strong> your account is not yet activated.', $userMeta->name ),
            'check_email_for_link'      => __( 'Check your e-mail for the confirmation link.', $userMeta->name ),
            'email_not_found'           => __( 'Email not found', $userMeta->name ),
            'incorrect_captcha'         => __( 'Incorrect captcha code', $userMeta->name ),
            'invalid_key'               => __( 'Sorry, that key does not appear to be valid.', $userMeta->name ),
            'expired_key'               => __( 'Sorry, that key has expired. Please try again.', $userMeta->name ),
            'invalid_parameter'         => __( 'Invalid parameter', $userMeta->name ),                          
        );
        
        $text = $userMeta->getSettings( 'text' );
        if ( is_array( $text ) ) {
            foreach ( $msgs as $key => $msg ) {
                if ( ! empty( $text[ $key ] ) )
                    $msgs[ $key ] = $text[ $key ];
            }
        }
        
        return apply_filters( 'user_meta_messages', $msgs );
    }
    

    function getExecutionPageConfig( $key ) {
        global $userMeta;
        
        $settings = $userMeta->getData( 'settings' );
        
        $lostPassTitle = 'Lost password';
        if ( ! empty( $settings['login']['resetpass_page'] ) )
            $lostPassTitle = get_the_title( (int) $settings['login']['resetpass_page'] );
          
        $emailVerifyTitle = 'Email verification';
        if ( !empty( $settings['registration']['email_verification_page'] ) ) {
            $emailVerifyTitle = get_the_title( (int) $settings['registration']['email_verification_page'] );
            if ( $emailVerifyTitle == 'Lost password' )
                $emailVerifyTitle = 'Email verification';
        }
            
        $configs = array(
            'lostpassword' => array(
                'title' => __( $lostPassTitle, $userMeta->name ),
            ),
            'resetpass' => array(
                'title' => __( 'Reset password', $userMeta->name ),
            ),
            'email_verification' => array(
                'title' => __( $emailVerifyTitle, $userMeta->name ),
            ), 
        );
        
        if ( ! empty( $configs[ $key ] ) )
            $config = apply_filters( 'user_meta_execution_page_config', $configs[ $key ], $key );
        
        switch ( $key ) {
            case 'lostpassword':
                return apply_filters( 'user_meta_lostpassword_form', $config );
            case 'resetpass':
                return apply_filters( 'user_meta_resetpass_form', $config );
            case 'email_verification':
                return apply_filters( 'user_meta_email_verification_form', $config );
        }
        
        return false;
    }
    
}
endif;
