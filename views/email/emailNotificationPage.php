

<?php
global $userMeta; 
// Expected: $data, $roles
?>

<div class="wrap">
    <?php screen_icon( 'options-general' ); ?>
    <h2><?php _e( 'E-mail Notification', $userMeta->name ); ?></h2>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <div id="um_admin_content">
                <?php echo $userMeta->proDemoImage( 'email-notification.png' ); ?>
            </div>
            
            <div id="um_admin_sidebar">                            
                <?php
                $variable = null;
                $variable .= "<strong>" . __( 'Site Placeholder', $userMeta->name ) . "</strong><p>";                         
                $variable .= "%site_title%, ";
                $variable .= "%site_url%, ";
                $variable .= "%login_url%, ";
                $variable .= "%logout_url%, ";
                $variable .= "%activation_url%, ";
                $variable .= "%email_verification_url%";
                $variable .= "</p>";
                
                $variable .= "<strong>" . __( 'User Placeholder', $userMeta->name ) . "</strong><p>";
                $variable .= "%ID%, ";
                $variable .= "%user_login%, ";
                $variable .= "%user_email%, ";
                $variable .= "%password%, ";
                $variable .= "%display_name%, ";
                $variable .= "%first_name%, ";
                $variable .= "%last_name%";
                $variable .= "</p>";    
                
                $variable .= "<strong>" . __( 'Custom Field', $userMeta->name ) . "</strong><p>";      
                $variable .= "%your_custom_user_meta_key%</p>";                     

                $variable .= "<p><em>(" . __( "Placeholder will be replaced with the relevant value when used in email subject or body.", $userMeta->name ) . ")</em></p>";                
                
                echo $userMeta->metaBox( __( 'Placeholder', $userMeta->name ), $variable );                
                
                echo $userMeta->metaBox( __( '3 steps to get started', $userMeta->name ),  $userMeta->boxHowToUse(), false, false );               
                if( !@$userMeta->isPro )
                    echo $userMeta->metaBox( __( 'User Meta Pro', $userMeta->name ),   $userMeta->boxGetPro() );
                //echo $userMeta->metaBox( __( 'Shortcode', $userMeta->name ),   $userMeta->boxShortcodesDocs() );
                ?>
            </div>
        </div>
    </div>     
</div>
