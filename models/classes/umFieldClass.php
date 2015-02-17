<?php

if ( ! class_exists( 'umField' ) ) :
class umField {
    
    private $data = array();
        
    private $rules = array();
    
    private $errors = array();
    
    private $options = array();
    
    public function __construct( $id, $data = array(), $options = array() ) {
        global $userMeta;
        
        $this->data     = ! empty( $data ) ? $data : $userMeta->getFieldData( $id );
        $this->options  = $options;
    }
    
    public function getConfig( $key = null ) {
        if ( empty( $key ) )
            return $this->data;
        
        if ( isset( $this->data[ $key ] ) )
            return $this->data[ $key ];
        
        return false;
    }
    
    public function addRule( $rule ) {
        $this->rules[] = $rule;
    }
    
    public function validate() {
        
        $this->assignRules();
        
        foreach ( $this->rules as $rule ) {
            $value = isset ( $this->data['field_value'] ) ? $this->data['field_value'] : null;
            $validate = new umValidationRule( $rule, $value, array(
                'field_name'    => isset( $this->data['field_name'] )   ? $this->data['field_name'] : null,
                'user_id'       => isset( $this->options['user_id'] )   ? $this->options['user_id'] : 0,
                'insert_type'   => isset( $this->options['insert_type'] ) ? $this->options['insert_type'] : null,
            ) );
            
            if ( 'custom' == $rule ) {
                $regex = @$this->data['regex'];
                $regex = ! empty( $regex ) ? "/$regex/" : null;
                $validate->setProperty( $regex, @$this->data['error_text'] );
            }
                     
            if ( ! $validate->validate() )
                $this->errors[ $rule ] = $validate->getError();
        }
        
        return empty( $this->errors ) ? true : false;
    }
    
    private function assignRules() {
        if ( isset( $this->data['field_type'] ) ) {
            switch ( $this->data['field_type'] ) {
                case 'user_login':
                    $this->rules[] = 'required';
                    $this->rules[] = 'unique';
                    break;
                case 'user_email':
                    $this->rules[] = 'required';
                    $this->rules[] = 'unique';
                    $this->rules[] = 'email';
                    break;
                case 'email':
                    $this->rules[] = 'email';
                    break;
                case 'url':
                case 'user_url':
                    $this->rules[] = 'url';
                    break;
                case 'user_registered':
                    $this->rules[] = 'datetime';
                    break;
                case 'number':
                    $this->rules[] = 'number';
                    break;
                case 'phone':
                    $this->rules[] = 'phone';
                    break;
                case 'custom':
                    $this->rules[] = 'custom';
                    break;
            }

            if ( ! empty( $this->data['required'] ) ) {
                if( ! in_array( 'required', $this->rules ) )
                    $this->rules[] = 'required';
            }
            
            if ( ! empty( $this->data['unique'] ) ) {
                if( ! in_array( 'unique', $this->rules ) )
                    $this->rules[] = 'unique';
            }
            
        }
    }
    
    public function getErrors() {
        $errors = array();
        foreach ( $this->errors as $rule => $error ) {
            $title = isset( $this->data['field_title'] ) ? $this->data['field_title'] : null;
            $errors["validate_$rule"] = sprintf( $error, $title );
        }
        return $errors;
    }
    
}
endif;