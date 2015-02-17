<?php global $userMeta; ?>

<div class="wrap">
    <div id="icon-edit-pages" class="icon32 icon32-posts-page"><br /></div>  
    <h2><?php _e('Fields Editor', $userMeta->name );?></h2>  
    <?php do_action( 'um_admin_notice' ); ?>
    <p> <?php _e('Click field from right side panel for creating a new field', $userMeta->name );?></p> 
    <div id="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <div id="um_admin_content">
                <form id="um_fields_form" action="" method="post" onsubmit="umUpdateField(this); return false;" >
                    <?php 
                    echo $userMeta->createInput( 'save_field', 'submit', array(
                        'value' =>  __( 'Save Changes', $userMeta->name ),
                        'class' => 'button-primary pf_save_button' 
                    ) ); 
                    ?>                 
                    <br /><br />
                    <div id="um_fields_container">                 
                        <?php
                        if( $fields ){
                            $n = 0;
                            foreach( $fields as $fieldID => $fieldData ){
                                $n++;
                                $fieldData['id'] = $fieldID;
                                $fieldData['n']  = $n;
                                $userMeta->render( 'field', $fieldData );
                            }
                        }   
                        ?>                                     
                    </div>
                    <?php 
                    echo $userMeta->nonceField();
                    
                    echo $userMeta->createInput( 'save_field', 'submit', array(
                        'value' => __( 'Save Changes', $userMeta->name ),
                        'class' => 'button-primary pf_save_button' 
                    ) ); 
                    ?>                 
                </form>
                <?php $maxKey      = $userMeta->maxKey( $fields ); ?>
                <?php $last_id     = $maxKey ? $maxKey : 0 ?>
                <input type="hidden" id="last_id" value="<?php echo $last_id; ?>"/>
            </div>
            

            <?php
            $fieldSelection = $userMeta->renderPro( 'fieldSelector' );
            ?>
            
            <div id="um_admin_sidebar">                            
                <?php 
                echo $userMeta->metaBox( __( 'WordPress Default Fields', $userMeta->name ),  $fieldSelection['wp_default'] );
                echo $userMeta->metaBox( __( 'Extra Fields', $userMeta->name ),              $fieldSelection['standard'] );
                echo $userMeta->metaBox( __( 'Formatting Fields', $userMeta->name ),          $fieldSelection['formatting'] );
                if( !@$userMeta->isPro )
                    echo $userMeta->metaBox( __('User Meta Pro', $userMeta->name ),   $userMeta->boxGetPro());
                echo $userMeta->metaBox( __( '3 steps to get started', $userMeta->name ),  $userMeta->boxHowToUse(), false, false);
                echo $userMeta->metaBox( 'Shortcodes',   $userMeta->boxShortcodesDocs(), false, false);
                //echo $userMeta->metaBox( __( 'Tips', $userMeta->name ),   $userMeta->boxTips(), false, false);
                ?>
            </div>
        </div>
    </div>     
</div>


<script>
jQuery(function() {

    jQuery('#um_fields_container').sortable({
        connectWith: '#um_fields_container',
        handle: '.hndle'
    }).droppable({
        accept: '.um_field_selecor',
        activeClass: 'um_highlight',
        drop: function(event, ui) {
            var $li = jQuery('<div>').html('List ' + ui.draggable.html());
            $li.appendTo(this);
        }
    });  
   
    jQuery( "#um_admin_sidebar" ).sortable({
        handle: '.hndle'
    });

});
</script>