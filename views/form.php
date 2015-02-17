<?php
global $userMeta;
//need to set: $id, $form, $fields

$form_key   = isset($form['form_key'])    ? $form['form_key']     : null;

$html = null;

$html .= $userMeta->createInput( "forms[$id][form_key]", "text", array( 
    "value"     => $form_key,
    "label"     =>  __( 'Form Name',$userMeta->name ) . " <span class='um_required'>*</span>",
    "id"        => "um_form_$id",
    "class"     => "validate[required]",
    "label_class" => "pf_label",
    "onkeyup"   => "umChangeFormTitle(this)",
    "after"     => "<br /><em>" . __( '(Give a name to your form)',$userMeta->name ) . "</em>",
    "enclose"   => "div class='um_left'",
     ) );   

$html .= "<div class='clear'></div>";

/**
 * Creating jQuery Tab
 */
/*$html .= "<div class=\"form_tabs\">";
    $html .= "<ul>"
            . "<li><a href=\"#form-$id-tab1\">Fields</a></li>"
            . "<li><a href=\"#form-$id-tab2\">Style</a></li>"
            . "</ul>";
    $html .= "<div id=\"form-$id-tab1\">Data 1 - $id</div>";
    $html .= "<div id=\"form-$id-tab2\">Data 2</div>";
$html .= "</div>";
 */

//$html .= "<div class='um_left'>";
    //$html .= "<div class='um_left'>";
        //$html .= "<h4>Shortcode</h4>";
        //$html .= "<p>Shortcode</p>";
    //$html .= "<div>";
//$html .= "<div>";

$html .= "<div class='clear'></div>";
$html .= "<br /><br /><br />";

$html .= "<div class=\"um_left um_block_title\">" . __( 'Fields in your form (Drag from available fields)', $userMeta->name ) . "</div>";
$html .= "<div class=\"um_right um_block_title\">". __('Available Fields', $userMeta->name ) . "</div>";
$html .= "<div class='clear'></div>";

//Showing selected fields
$html .= "<div class='um_selected_fields um_left um_dropme'>";
if( isset( $form['fields'] ) ) {
    foreach( $form['fields'] as $fieldID ){
        if( isset( $fields[$fieldID] ) ){
            $fieldTitle = isset( $fields[$fieldID]['field_title'] ) ? $fields[$fieldID]['field_title'] : null;
            $html .= "<div class='postbox'>$fieldTitle ({$fields[$fieldID]['field_type']}) ID:$fieldID<input type='hidden' name='forms[$id][fields][]' value='$fieldID' /></div>";
            unset( $fields[$fieldID] );            
        }
    }    
}
$html .= "</div>";


$html .= "<div class='um_availabele_fields um_right um_dropme'>";
if( is_array( $fields ) ){
    foreach( $fields as $fieldID => $fieldData ){
        $fieldTitle = isset( $fieldData['field_title'] ) ? $fieldData['field_title'] : null;
        $html .= "<div class='postbox'>$fieldTitle ({$fieldData['field_type']}) ID:$fieldID<input type='hidden' name='forms[$id][fields][]' value='$fieldID' /></div>";    
    }
}
$html .= "</div>";

$html .= "<div class='clear'></div>";

$html .= "<div class=\"um_block_title\">" . __( 'Drag fields from right block to left block to add them to your form.', $userMeta->name ) . "</div>";


$html .= "<input type='hidden' name='forms[$id][field_count]' id='field_count_$id' value='' />";



$html .= "<div class='pf_divider'></div>"; 

$html .= $userMeta->createInput( "forms[$id][button_title]", "text", array( 
    "value"     => @$form['button_title'],
    "label"     =>  __( 'Submit Button Title',$userMeta->name ),
    "label_class" => "pf_label",
    "after"     => "<br /><em>" . __( 'Keep blank for default value',$userMeta->name ) . "</em>",
    "enclose"   => "div class='um_left pf_width_30'",
) ); 

$html .= $userMeta->createInput( "forms[$id][button_class]", "text", array( 
    "value"     => @$form['button_class'],
    "label"     =>  __( 'Submit Button Class',$userMeta->name ),
    "label_class" => "pf_label",
    "after"     => "<br /><em>" . __( 'Assign class to submit button',$userMeta->name ) . "</em>",
    "enclose"   => "div class='um_left pf_width_30'",
) );

$html .= $userMeta->createInput( "forms[$id][form_class]", "text", array( 
    "value"     => @$form['form_class'],
    "label"     =>  __( 'Form Class',$userMeta->name ),
    "label_class" => "pf_label",
    "after"     => "<br /><em>" . __( 'Assign class to form tag',$userMeta->name ) . "</em>",
    "enclose"   => "div class='um_left pf_width_30'",
) );
 
$html .= "<div class='clear'></div>";

$html .= $userMeta->createInput( "forms[$id][disable_ajax]", "checkbox", array( 
    "value"     => @$form['disable_ajax'],
    "label"     => "<strong>" . __( 'Do not use AJAX submit', $userMeta->name ) ."</strong>",
    "id"        => "um_forms_{$id}_disable_ajax",
    "enclose"   => "p",
) );


$metaBoxOpen = true;
if( isset($id) )
    if( !($id == 1) ) $metaBoxOpen = false;
    
$newFormText = __( 'New Form', $userMeta->name );
    
$metaBoxTitle = ($form_key) ? $form_key : $newFormText;
if( $metaBoxTitle == $newFormText ) $metaBoxOpen = true;

echo $userMeta->metaBox( $metaBoxTitle, $html, true, $metaBoxOpen );
?>
