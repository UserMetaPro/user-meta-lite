<?php
global $userMeta; 
// Expected: $actionTypes, $formsList, $roles
?>
<div id="um_shortcode_popup" style="display:none;">
    <?php

    echo $userMeta->createInput( 'action_type', 'select', array(
        'value'         => '',
        'label'         => __( 'Action Type', $userMeta->name ),
        'id'            => 'um_action_type',
        'class'         => 'um_input',
        'label_class'   => 'pf_label',
        'after'         => ' <span>(' . __( 'Required', $userMeta->name ) . ')</span>' ,
        'enclose'       => 'p'
    ), $actionTypes );   

    echo $userMeta->createInput( 'form_name', 'select', array(
        'value'         => '',
        'label'         => __( 'Form Name', $userMeta->name ),
        'id'            => 'um_form_name',
        'class'         => 'um_input',
        'label_class'   => 'pf_label',
        'after'         => ' <span id="um_is_form_required"></span>' ,
        'enclose'       => 'p'
    ), $formsList );      
    
    echo '<div id="um_rolebased_container" style="display:none">';
    
        echo $userMeta->createInput( 'um_rolebased_link', 'checkbox', array( 
            'label'     => '<strong>' . __( 'Use role based user profile (advanced)', $userMeta->name ) . '</strong>',
            'id'        => 'um_rolebased_link',
            'enclose'   => 'p',
        ) );     

        echo '<div id="um_rolebased_content" style="display:none">';   
            echo '<p><em>(' . __( 'Assign form to user role. Leave blank for using default form', $userMeta->name ) . ')</em></p>';
            foreach( $roles as $roleName => $roleTitle ){
                echo $userMeta->createInput( "rolebased[$roleName]", 'select', array(
                    'value'         => '',
                    'label'         => $roleTitle,
                    'id'            => 'um_rolebased_' . $roleName,
                    'class'         => 'um_rolebased',
                    'label_class'   => 'um_label_left',
                    'enclose'       => 'div'
                ), $formsList );        
            }       
        echo '</div>';       
   echo '</div>';

    echo $userMeta->createInput( '', 'button', array(
        'value'         => __( 'Insert Shortcode', $userMeta->name ),
        'id'            => 'um_generator_button',
        'class'         => 'button-primary',
        'enclose'       => 'p'
    ) );
    

    ?>
</div>

<?php if ( ! $userMeta->isPro() ){ ?>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery("#um_action_type option").each(function(){
                if( jQuery(this).text() == "login" )
                    jQuery(this).attr("disabled","disabled");
            });
        });
    </script>
<?php } ?>

    
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#um_action_type").change(function(){
            if( jQuery(this).val() == '' )
                jQuery("#um_is_form_required").text("");
            else if( jQuery(this).val() == 'login' )
                jQuery("#um_is_form_required").text("(<?php _e( 'Optional', $userMeta->name ); ?>)");
            else
                jQuery("#um_is_form_required").text("(<?php _e( 'Required', $userMeta->name ); ?>)");
            
            if( jQuery(this).val() == 'profile' || jQuery(this).val() == 'profile-registration' || jQuery(this).val() == 'public' )
                jQuery("#um_rolebased_container").fadeIn();
            else
                jQuery("#um_rolebased_container").fadeOut();
        });
        
        jQuery("#um_rolebased_link").click(function(){
            if( jQuery(this).is(":checked") )
                jQuery("#um_rolebased_content").fadeIn();
            else
                jQuery("#um_rolebased_content").fadeOut();
        })
             
        jQuery("#um_generator_button").click(function(){
            if( !jQuery("#um_action_type").val() ){
                alert( 'Action Type is required!' );return;
            }

            if( !(jQuery("#um_action_type").val() == 'login') ){
                if( !jQuery("#um_form_name").val() ){
                    alert( 'Form Name is required for ' + jQuery("#um_action_type").val() + '!' );return;
                }
            }
                
            
            shortcode = '[user-meta type="' + jQuery("#um_action_type").val() + '"';
            if( jQuery("#um_form_name").val() )
                shortcode += ' form="' + jQuery("#um_form_name").val() + '"';
            
            var diff = '';
            if( jQuery("#um_action_type").val() == 'profile' || jQuery("#um_action_type").val() == 'profile-registration' || jQuery("#um_action_type").val() == 'public' ){
                if( jQuery("#um_rolebased_link").is(":checked") ){
                    jQuery(".um_rolebased").each(function(){
                        if( jQuery(this).val() ){
                            diff += jQuery(this).attr("id").replace("um_rolebased_", '') + '=' + jQuery(this).val() + ', ';
                        }
                    });
                }
            }
            
            if( diff )
                shortcode += ' diff="' + diff.trim().replace(/,$/, '') + '"';

            shortcode += ']';
            tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode);
            //tinyMCE.execInstanceCommand("elm1","mceInsertContent",false,shortcode);
            tb_remove();
        });
    });
</script>