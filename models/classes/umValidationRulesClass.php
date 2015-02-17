<?php

if ( ! class_exists( "umValidationRule" ) ) :
class umValidationRule {
    
    /**
     * @var type (string) : Rule name
     */
    private $rule;
    
    /**
     * @var type (string) : Value to check
     */
    private $value;
    
    /**
     * @var type (string) : Regex for certain rule
     */
    private $regex;
    
    /**
     * @var type (string) : Error message while validation return false
     */
    private $message;
    
    /**
     * Extra options
     * 
     * Possible key: field_name, user_id, insert_type
     */
    private $options = array();
    
    function __construct( $rule, $value, $options=array() ) {
        $this->rule     = $rule;
        $this->value    = is_string( $value ) ? trim( $value ) : $value;
        $this->options  = $options;
    }
    
    private function setRegex() {        
		$regexes = Array(
            'required'  => "/[\s\S]/",
            'url'       => "/^((https?|ftp):\/\/)?(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i",            
            'number'    => "/^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/",  // Credit: https://github.com/posabsolute/jQuery-Validation-Engine/blob/master/js/languages/jquery.validationEngine-en.js
            'phone'     => "/^([\+][0-9]{1,3}[\ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9\ \.\-\/]{3,20})((x|ext|extension)[\ ]?[0-9]{1,4})?$/",
            'datetime'  => "/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s?([01][0-9]|[2][0-4]):[0-6][0-9]:[0-6][0-9]\s?$/",
		);
        
        if ( ! empty( $regexes[ $this->rule ] ) )
            $this->regex = $regexes[ $this->rule ];
        
        $this->regex = isset( $this->regex ) ? $this->regex : null;
    }
    
    private function setMessage() {
        global $userMeta;
        
		$messages = Array(
            'required'  => $userMeta->getMsg( 'validate_required', '%s' ),
            'email'     => $userMeta->getMsg( 'validate_email' ),
            'equals'    => $userMeta->getMsg( 'validate_equals', '%s' ),
            'unique'    => sprintf( __( '%1$s: "%2$s" already taken', $userMeta->name ), '%s', $this->value ),
		); 
        
        $default = __( 'Invalid %s', $userMeta->name );
        
        if ( ! empty( $messages[$this->rule] ) )
            $this->message = $messages[ $this->rule ];
        
        $this->message = ! empty( $this->message ) ? $this->message : $default;
    }
    
    public function validate() {   
        $isValid = false;
        
        if ( ( $this->rule <> 'required' ) && empty( $this->value )  )
            return true;
        
        $methodName = 'validate_' . $this->rule;
        if ( method_exists( $this, $methodName ) )
            $isValid = $this->$methodName();  
        else {
            $this->setRegex();
            if ( ! empty( $this->regex ) ) {
                if( is_array( $this->value ) ){
                    foreach( $this->value as $val ){
                        if ( preg_match( $this->regex, $val ) )
                            $isValid = true;
                    }
                }else{
                    if ( preg_match( $this->regex, $this->value ) )
                        $isValid = true;
                }
            }else
                $isValid = true;
        }
        
        if ( ! $isValid )
            $this->setMessage();
        
        return $isValid;
    }
    
    public function getError() {
        return $this->message;
    }
    
    public function setProperty( $regex, $message ) {
        $this->regex = $regex;
        $this->message = $message;
    }
    
    private function validate_email() {
        return is_email( $this->value );
    }
    
    private function validate_equals() {
        if ( $this->options['field_name'] ) {
            $fieldName = $this->options['field_name'];
            $retypeValue = isset( $_REQUEST[ $fieldName . '_retype' ] ) ? trim( $_REQUEST[ $fieldName . '_retype' ] ) : null;
            return $retypeValue == $this->value ? true : false;
        }
        
        return false;
    }
    
    private function validate_unique() {
        global $userMeta;

        $userID = isset( $this->options['user_id'] ) ? $this->options['user_id'] : 0;
        if ( isset( $this->options['insert_type'] ) )
            $userID = 'registration' == $this->options['insert_type'] ? 0 : $userID;
        
        if ( isset( $this->options['field_name'] ) )
            return $userMeta->isUserFieldAvailable( $this->options['field_name'], $this->value, $userID );
        
        return false;
    }
    
    private function validate_current_password() {
        global $userMeta;
        
        $fieldName = $this->options['field_name'] . '_current';
        if ( !empty( $this->value ) && empty( $_REQUEST[$fieldName] ) ) {
            $this->message = $userMeta->getMsg('validate_current_required', '%s');
            return false;
        }
        
        $userID = isset( $this->options['user_id'] ) ? $this->options['user_id'] : 0;
        $user = new WP_User( $userID );
        
        if ( ! empty($user->user_login) ) {
            $user = wp_authenticate( $user->user_login, esc_attr( $_REQUEST[ $fieldName ] ) );
            if ( is_wp_error( $user ) ) {
                $this->message = $userMeta->getMsg( 'validate_current_password' );
                return false;
            }     
        }
        
        return true;
    }
    
}
endif;