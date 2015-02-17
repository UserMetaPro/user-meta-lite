<?php
global $userMeta;
/**
 * Expected: $form
 * $form['fields']          : array containing field's id
 * $form['disable_ajax']    : bool (default false)
 * $form['onsubmit']        : full js code for onsubmit
 * $form['form_id']         : Name of the form id
 * $form['form_class']      : Name of the form class
 * $form['form_action']     : Action name for form
 * $form['form_start']      : Just after form starting tag
 * $form['form_end']        : Just before form ending tag
 */
//get veriable by render: $actionType, $fields, $form, $fieldValues

if( empty($form) )
    return $html = $userMeta->ShowError( __( 'Form is not found.', $userMeta->name ) );

if( empty( $form['fields'] ) )
    return $html = $userMeta->ShowError( __( 'Fields are not found.', $userMeta->name ) );

if( !is_array( $form['fields'] ) )
    return $html = $userMeta->ShowError( __( 'Fields were saved incorrectly.', $userMeta->name ) );

$fields = $form['fields'];
if( empty( $userID ) )
    $userID = 0;


$pageCount = 0;
foreach( $fields as $id => $field ){
    
    // Counting page
    if( $field['field_type'] == 'page_heading' )
        $pageCount++;
    
    /// Determine Field Group
    $field_type_data   = $userMeta->getFields( 'key', $field['field_type'] ); 
    $fieldGroup        = $field_type_data['field_group'];

    /// Determine Field Name
    $fieldName = null;
    if( $fieldGroup == 'wp_default' ){
        $fieldName = $field['field_type'];
    }else{
        if( isset($field['meta_key']) )
            $fieldName = $field['meta_key'];
    }  
    
    /// Determine Field Value
    $fieldValue = null;
    if( isset( $field['default_value'] ) ){
        $fieldValue = $userMeta->convertUserContent( new WP_User($userID), $field['default_value']  );
    }       

    if( isset( $fieldValues->$fieldName ) )
        $fieldValue = $fieldValues->$fieldName;
      
    if( ! @$userMeta->showDataFromDB ){
        if( isset( $_REQUEST[$fieldName] ) )  
            $fieldValue = $_REQUEST[$fieldName];                    
    }  
    
    $fields[ $id ]['field_name']  = $fieldName;
    $fields[ $id ]['field_value'] = $fieldValue;    
    
}

/**
 * Applying filter hook. Accept two arg: $form, $formName
 * return $form 
 */
$form = apply_filters( 'user_meta_form_config', $form, $form['form_key'], $userID );

$uniqueID   = sanitize_key( $form['form_key'] );

$formID     = !empty( $form['form_id'] ) ? $form['form_id'] : "um_form_$uniqueID";
$formClass  = !empty( $form['form_class'] ) ? $form['form_class'] : null;
$formAction = !empty( $form['form_action'] ) ? "action=\"" . $form['form_action'] . "\"": null;
$onsubmit   = !empty( $form['onsubmit'] ) ? "onsubmit=\"" . $form['onsubmit'] . "return false;\"": null;

$html = null;

if ( $actionType == 'registration' && $userMeta->isHookEnable( 'login_form_register', array( 'form' => $form['form_key'] ) ) )
    do_action( 'login_form_register' );

if( @$_REQUEST['form_key'] == $form['form_key'] && @$_REQUEST['action_type'] == $actionType ){
    if( isset( $userMeta->um_post_method_status->$methodName ) )
        $html .= $userMeta->um_post_method_status->$methodName;
}

do_action( 'user_meta_before_form', $form['form_key'] );
$html .= "<form method=\"post\" $formAction id=\"$formID\" class=\"$formClass\" $onsubmit enctype=\"multipart/form-data\" > ";
    if( !empty( $form['form_start'] ) )
        $html .= $form['form_start'];

    if( is_array( $fields ) ):
        
        $inPage     = false;
        $inSection  = false;
        $isNext     = false;
        $isPrevious = false;
        $currentPage= 0;
        foreach( $fields as $fieldID => $field ){
   
            if( $field['field_type'] == 'page_heading' ){
                $currentPage++;
                $isNext     = $currentPage >= 2 ? true : false;
                $isPrevious = $currentPage >  2 ? true : false;      
            }                

            /**
             * Filter hook to field config. 
             * Accpt 3 arg: $field, $fieldID, $formName.
             * return: $field
             */
            $field = apply_filters( 'user_meta_field_config', $field, $fieldID, $form['form_key'], $userID );

            $fieldDisplay = $userMeta->renderPro( 'generateField', array( 
                'field'         => $field,
                'form'          => $form,
                'actionType'    => $actionType,
                'userID'        => $userID,
                'inPage'        => $inPage,
                'inSection'     => $inSection,
                'isNext'        => $isNext,
                'isPrevious'    => $isPrevious,
                'currentPage'   => $currentPage,
                'uniqueID'      => $uniqueID,
            ) );
            
            $html .= apply_filters( 'user_meta_field_display', $fieldDisplay, $fieldID, $form['form_key'], $field, $userID );

            if( $field['field_type'] == 'page_heading' ){
                $inPage    = true;
                $inSection = false;
            }

            if( $field['field_type'] == 'section_heading' )
                $inSection = true;                    
                                   
        }
        
        // Similar to generateField: field_type==page_heading
        if( $inSection )
            $html .= "</div>";             
        if( $pageCount >= 2){
            $previousPage = $currentPage - 1 ;
            $html .= $userMeta->createInput( "", "button", array(
                "value"     =>  __( 'Previous', $userMeta->name ),
                "onclick"   => "umPageNavi($previousPage, false, this)",
                "class"     => "previous_button " . !empty( $form['button_class'] ) ? $form['button_class'] : "",
            ) );                        
            //$html .= "<input type='button' onclick='umPageNavi($previousPage)' value='" . __( 'Previous', $userMeta->name ) . "'>";
        }                           
        // End
        
        if ( $actionType == 'registration' && $userMeta->isHookEnable( 'register_form', array( 'form' => $form['form_key'] ) ) ) {
            ob_start();
            do_action( 'register_form' );
            $html .= ob_get_contents();
            ob_end_clean();
        }
        
        $html .= $userMeta->createInput( "form_key", "hidden", array(
            "value"     => $form['form_key'],
        ) );                           
        $html .= $userMeta->createInput( "action_type", "hidden", array(
            "value"     => $actionType,
        ) ); 
        $html .= $userMeta->createInput( "user_id", "hidden", array(
            "value"     => $userID,
        ) ); 
   
        
        /*$html .= $userMeta->nonceField();
        $html .= $userMeta->methodName( $methodName );
        $html .= $userMeta->wp_nonce_field( $methodName, 'um_post_method_nonce', false, false );  */
        
        $html .= $userMeta->methodPack( $methodName );
        

        if( $actionType == 'profile' )
            $buttonValue = __( 'Update', $userMeta->name );
        elseif( $actionType == 'registration' )
            $buttonValue = __( 'Register', $userMeta->name );
        elseif( $actionType == 'login' )
            $buttonValue = __( 'Login', $userMeta->name );        
        
        if( !empty( $form['button_title'] ) )
            $buttonValue = $form['button_title'];        
        
        
        //$html .= "<script>user_id=$userID</script>";        
        
        if( isset( $buttonValue ) ){
            $html .= "<div class=\"um_clear\"></div>";
            $html .= $userMeta->createInput( "um_sibmit_button", "submit", array(
                "value"     => $buttonValue,
                "id"        => "insert_user",
                "class"     => !empty( $form['button_class'] ) ? $form['button_class'] : 'um_button',
                'enclose'   => 'div',
            ) );
        }
        
        
        if( $inPage )
            $html .= "</div>";    
            
    endif;
    
    if( !empty( $form['form_end'] ) )
        $html .= $form['form_end'];    
    
$html .= "</form>";    

do_action( 'user_meta_after_form', $form['form_key'], $userID );


$uploaderPath = $userMeta->pluginUrl . '/framework/helper/uploader.php';


$html .= "\n\r<script type=\"text/javascript\">";
    $html .= "jQuery(document).ready(function(){";
        $html .= "jQuery(\".um_user_form\").validationEngine();";
        $html .= "umPageNavi( 1, false, '$formID' );";
        $html .= "jQuery(\".um_rich_text\").wysiwyg({initialContent:' '});";
        //$html .= "jQuery(\".um_datetime\").datetimepicker({ dateFormat: \"yy-mm-dd\", timeFormat: \"hh:mm:ss\", changeYear: true });";
        //$html .= "jQuery(\".um_date\").datepicker({ dateFormat: \"yy-mm-dd\", changeYear: true });";
        //$html .= "jQuery(\".um_time\").timepicker({timeFormat: \"hh:mm:ss\"});";
        //jQuery('.pass_strength').password_strength();
        $html .= "jQuery(\"input, textarea\").placeholder();";
        $html .= "umFileUploader( \"$uploaderPath\" );";  
    $html .= "});";
$html .= "</script>\n\r";


$html = apply_filters( 'user_meta_form_display', $html, $form['form_key'], $form, $userID );