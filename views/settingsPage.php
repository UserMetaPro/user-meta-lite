<?php
global $userMeta; 
// Expected: $settings, $forms, $fields, $default
?>

<div class="wrap">
    <?php screen_icon( 'options-general' ); ?>
    <h2><?php _e( 'User Meta Settings', $userMeta->name ); ?></h2>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <div id="um_admin_content">
                <?php if($userMeta->isPro) $userMeta->renderPro( "activationForm", null, "settings" ); ?>
                <?php
                $isPro = $userMeta->isPro();
                $title = array(
                    'general'       => __( 'General', $userMeta->name ),
                    'login'         => $isPro ? __( 'Login', $userMeta->name ) : '<span class="pf_blure">'.__( 'Login', $userMeta->name ).'</span>',
                    'registration'  => $isPro ? __( 'Registration', $userMeta->name ) : '<span class="pf_blure">'.__( 'Registration', $userMeta->name ).'</span>',
                    'redirection'   => $isPro ? __( 'Redirection', $userMeta->name ) : '<span class="pf_blure">'.__( 'Redirection', $userMeta->name ).'</span>',
                    'profile'       => $isPro ? __( 'Backend Profile', $userMeta->name ) : '<span class="pf_blure">'.__( 'Backend Profile', $userMeta->name ).'</span>',
                );
                ?>
            
                <form id="um_settings_form" action="" method="post" onsubmit="umUpdateSettings(this); return false;" >
                <div id="um_settings_tab">
                	<ul>
                        <li><a href="#um_settings_general"><?php echo $title['general']; ?></a></li>     
                        <li><a href="#um_settings_login"><?php echo $title['login']; ?></a></li>
                        <li><a href="#um_settings_registration"><?php echo $title['registration']; ?></a></li>
                        <li><a href="#um_settings_redirection"><?php echo $title['redirection']; ?></a></li>
                        <li><a href="#um_settings_backend_profile"><?php echo $title['profile']; ?></a></li>
                        <li><a href="#um_settings_text"><?php _e( 'Text', $userMeta->name ); ?></a></li>
                        <!--<li><a href="#um_settings_misc"><?php //_e( 'Misc', $userMeta->name ); ?></a></li>-->
                        <?php do_action( 'user_meta_settings_tab' ); ?>
                	</ul>  
                                                               
                    <div id="um_settings_general">
                        <?php
                        echo $userMeta->renderPro( "generalSettings", array(
                            'general' => isset( $settings[ 'general' ] ) ? $settings[ 'general' ] : $default[ 'general' ],
                        ), "settings" );  
                         
                        echo $userMeta->renderPro( "generalProSettings", array(
                            'general' => isset( $settings[ 'general' ] ) ? $settings[ 'general' ] : $default[ 'general' ],
                        ), "settings" );                                                  
                        ?>                        
                    </div> 
                    
                    <div id="um_settings_login">
                        <?php
                        echo $userMeta->renderPro( "loginSettings", array(
                            'login' => isset( $settings[ 'login' ] ) ? $settings[ 'login' ] : $default[ 'login' ],
                        ), "settings" );                            
                        ?>
                    </div> 
                    
                    <div id="um_settings_registration">
                        <?php
                        echo $userMeta->renderPro( "registrationSettings", array(
                            'registration' => isset( $settings[ 'registration' ] ) ? $settings[ 'registration' ] : $default[ 'registration' ],
                        ), "settings" );                            
                        ?>
                    </div>                     
                    
                    <div id="um_settings_redirection">
                        <?php
                        echo $userMeta->renderPro( "redirectionSettings", array(
                            'redirection' => isset( $settings[ 'redirection' ] ) ? $settings[ 'redirection' ] : $default[ 'redirection' ],
                        ), "settings" );                               
                        ?>
                    </div> 
                        
                    <div id="um_settings_backend_profile">
                        <?php
                        echo $userMeta->renderPro( "backendProfile", array(
                            'backend_profile'   => isset( $settings[ 'backend_profile' ] ) ? $settings[ 'backend_profile' ] : $default[ 'backend_profile' ],
                            'forms'             => $forms,
                            'fields'            => $fields
                        ), "settings" );                            
                        ?>
                    </div> 
                    
                    <div id="um_settings_text">
                        <?php
                        echo $userMeta->renderPro( "textSettings", array(
                            'text' => isset( $settings[ 'text' ] ) ? $settings[ 'text' ] : array(),
                        ), "settings" );                               
                        ?>
                    </div> 
                    
                    <?php do_action( 'user_meta_settings_tab_details' ); ?>
                    
                    <!--<div id="um_settings_misc">
                        <?php/*
                        echo $userMeta->renderPro( "miscSettings", array(
                            'misc'   => isset( $settings[ 'misc' ] ) ? $settings[ 'misc' ] : $default[ 'misc' ],
                        ), "settings" );    */                
                        ?>
                    </div>-->                 
                                                                                                          
                </div>
                
                <?php
                echo $userMeta->nonceField();
                
                echo $userMeta->createInput( "save_field", "submit", array(
                    "value" => __( "Save Changes", $userMeta->name ),
                    "id"    => "update_settings",
                    "class" => "button-primary",
                    "enclose"   => "p",
                ) );                    
                ?>                
                
                </form>

            </div>
            
            <div id="um_admin_sidebar">                            
                <?php
                echo $userMeta->metaBox( __( '3 steps to get started', $userMeta->name ),  $userMeta->boxHowToUse());               
                if( !@$userMeta->isPro )
                    echo $userMeta->metaBox( __( 'User Meta Pro', $userMeta->name ),   $userMeta->boxGetPro());
                echo $userMeta->metaBox( 'Shortcodes',   $userMeta->boxShortcodesDocs());
                //echo $userMeta->metaBox( __( 'Tips', $userMeta->name ),   $userMeta->boxTips(), false, false);
                ?>
            </div>
        </div>
    </div>     
</div>


<script>
jQuery(function() {
    jQuery('.um_dropme').sortable({
        connectWith: '.um_dropme',
        cursor: 'pointer'
    }).droppable({
        accept: '.button',
        activeClass: 'um_highlight'
    });   

    jQuery("#um_settings_tab").tabs();
    jQuery("#loggedin_profile_tabs").tabs();
    jQuery("#redirection_tabs").tabs();
    
    umSettingsToggleCreatePage();
    umSettingsToggleError();
    jQuery('#um_login_login_page, #um_login_resetpass_page, #um_registration_email_verification_page').change(function() {
        umSettingsToggleCreatePage();
        umSettingsToggleError();
    });
    
    umSettingsRegistratioUserActivationChange(); 
   
});
</script>