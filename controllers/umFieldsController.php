<?php

if ( ! class_exists( 'umFieldsController' ) ) :
class umFieldsController {
    
    function __construct() {      
        add_action( 'wp_ajax_um_add_field',     array($this, 'ajaxAddField' ) ); 
        add_action( 'wp_ajax_um_change_field',  array($this, 'ajaxChangeField' ) ); 
        add_action( 'wp_ajax_um_update_field',  array($this, 'ajaxUpdateField' ) );                
    }
    

    function ajaxAddField() {
        global $userMeta;
        $userMeta->verifyNonce();
                  
        if ( isset( $_REQUEST['field_type'] ) ) {
            unset( $_REQUEST['action'] );
            $userMeta->render( 'field', $_REQUEST );
        }
        
        die();
    }
    
    
    function ajaxChangeField() {
        global $userMeta;
        $userMeta->verifyNonce();
        
        if ( ! isset( $_POST['fields'] ) ) return;
        
        $data       =  $_POST['fields'] ;
        $fieldID    = key( $data );
                   
        $fieldData       = $data[$fieldID];
        $fieldData['id'] = $fieldID;          
        
        $userMeta->render( 'field', $fieldData );
        
        die();            
    }
    
    
    function ajaxUpdateField( ) {
        global $userMeta;                        
        $userMeta->verifyNonce();            
             
        $data = array();
        if ( isset( $_POST['fields'] ) )
            $data = $userMeta->arrayRemoveEmptyValue( $_POST['fields'] );
 
        $data = apply_filters( 'user_meta_pre_configuration_update', $data, 'fields_editor' );
        
        $userMeta->updateData( 'fields', $data );
        
        echo $userMeta->showMessage( __( 'Fields successfully saved.', $userMeta->name ) );
        die();
    }
           
}
endif;
