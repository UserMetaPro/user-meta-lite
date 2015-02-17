
<?php global $userMeta; ?>

<div class="wrap">
    <div id="icon-edit-pages" class="icon32 icon32-posts-page"><br /></div>  
    <h2><?php _e( 'Forms Editor', $userMeta->name );?> <span class="add-new-h2 um_add_button" onclick="umNewForm(this);"><?php _e( 'New Form', $userMeta->name );?></span> </h2>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <div id="um_admin_content">
                <form id="um_forms_form" action="" method="post" onsubmit="umUpdateForms(this); return false;" >
                    <?php
                    echo $userMeta->createInput( 'save_field', 'submit', array( 
                        'value'=> __( 'Save Changes', $userMeta->name ), 
                        'class'=> 'pf_save_button  button-primary' 
                    ) ); ?>
                    <br /><br />
                    <div id="um_fields_container">                 
                        <?php
                                               
                        //$userMeta->render( 'form', array( 'fields'=>$fields, 'id'=>1 ) );
                        $i = 0;
                        if( $forms ){
                            foreach( $forms as $form ){
                                $i++;
                                $form['id'] = $i;
                                $userMeta->render( "form", array( "id"=>$i, "form"=>$form, "fields"=>$fields ) );
                            }
                        }   
                        ?>                                     
                    </div>
                    <?php 
                    echo $userMeta->nonceField();
                    
                    echo $userMeta->createInput( 'save_field', 'submit', array(
                        'value'=> __( 'Save Changes', $userMeta->name ), 
                        'class'=>'pf_save_button  button-primary' 
                    ) );
                    
                    echo "&nbsp;&nbsp;&nbsp;";
                    
                    echo $userMeta->createInput( 'new_form', 'button', array( 
                        'value'     =>__( 'New Form', $userMeta->name ), 
                        'class'     =>'  button-primary', 
                        'onclick'   =>'umNewForm(this)' 
                    ) ); 
                    ?>
                </form>
                <input type="hidden" id="form_count" value="<?php echo $i; ?>"/>
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
    //jQuery( ".draggable" ).draggable({  revert: "valid" });
    /*jQuery( ".droppable" ).droppable({
			drop: function( event, ui ) {
			     alert(2);
				jQuery( this )
					.addClass( "ui-state-highlight" )
					.find( "p" )
						.html( "Dropped!" );
			}        
    });
    jQuery( ".sortable" ).sortable();
    jQuery( "#um_admin_sidebar" ).sortable();
    jQuery( "#fields_form").validationEngine();*/
    
    
//jQuery(".form_tabs").tabs();  
    
 

jQuery( "#um_fields_container" ).sortable({
    handle: '.hndle'
}); 

jQuery( "#um_admin_sidebar" ).sortable({
    handle: '.hndle'
});   

jQuery('.um_dropme').sortable({
    connectWith: '.um_dropme',
    cursor: 'pointer'
}).droppable({
    accept: '.button',
    activeClass: 'um_highlight',
    drop: function(event, ui) {
        //console.log( jQuery(this).html() );
        //alert( jQuery( this.parents() ) );
       /* var $li = jQuery('<div>').html('List ' + ui.draggable.html());
        $li.appendTo(this);*/
    }
});    

//alert( jQuery(".um_selected_fields > div").size() );
//jQuery(".um_selected_fields").each(function(d){alert( jQuery(this).html );});
   
    
    
});
</script>   
