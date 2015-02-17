<?php

if ( ! class_exists( 'umFormsController' ) ) :
class umFormsController {
    
    function __construct() {      
        add_action( 'wp_ajax_um_add_form',      array( $this, 'ajaxAddForm' ) ); 
        add_action( 'wp_ajax_um_update_forms',  array( $this, 'ajaxUpdateForms' ) );                
    }
    
    
    function ajaxAddForm() {
        global $userMeta;
        $userMeta->verifyNonce(); 
        
        $fields = $userMeta->getData( 'fields' );             
        $userMeta->render( 'form', array( 'id'=>$_POST['id'], 'fields'=>$fields ) );
        die();
    }
    
    
    function ajaxUpdateForms() {
        global $userMeta;
        $userMeta->verifyNonce();
              
        $error = null;
        $data = array();
        if ( isset( $_POST['forms'] ) ) {
            foreach ( $_POST['forms'] as $formID => $formData ) {                
                if ( is_array( $formData['fields'] ) ) {
                    foreach ( $formData['fields'] as $fieldID => $fieldKey ) {
                        if ( $fieldID >= $formData['field_count'] )
                            unset( $formData['fields'][ $fieldID ] );
                    }                    
                }                 
                
                /*if( $formData['field_count'] ) {
                    foreach( $formData['fields'] as $fieldID => $fieldKey ){
                        if( $fieldID >= $formData['field_count'] )
                            unset( $formData['fields'][$fieldID] );
                    }
                } */    
                unset( $formData['field_count'] );
                
                if ( !$formData['form_key'] )
                    $error[] = __( 'All form keys are required!', $userMeta->name );
                if ( isset( $data[ $formData['form_key'] ] ) )
                    $error[] = sprinft( __( 'Form key should be unique. "%s" is duplicated!', $userMeta->name ), $formData['form_key'] );              
                    
                $data[ $formData['form_key'] ] = $formData;               
            }
        }           
        
        if ( $error ) {
            echo $userMeta->showError( $error );
            die();
        }
        
        $data = $userMeta->arrayRemoveEmptyValue( $data );
        
        $data = apply_filters( 'user_meta_pre_configuration_update', $data, 'forms_editor' );
        
        $userMeta->updateData( 'forms', $data );   
        
        echo $userMeta->showMessage( __( 'Form Successfully saved.', $userMeta->name ) );
        die();
    }
            
}
endif;      
