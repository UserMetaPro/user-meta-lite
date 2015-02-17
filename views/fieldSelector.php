<?php
global $userMeta;

$wpFields = null;
foreach( $userMeta->getFields( 'field_group', 'wp_default', 'title' ) as $fieldKey => $fieldValue ){
    if( $fieldKey == 'blogname' ){
        if( ! $userMeta->isPro() ||  ! is_multisite() ) continue;
    }
    $wpFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";            
}
    
$standardFields = null;
foreach( $userMeta->getFields( 'field_group', 'standard', 'title' ) as $fieldKey => $fieldValue ){
    if( ! $userMeta->isPro() ){
        $fieldData = $userMeta->getFields( 'key', $fieldKey );
        if( $fieldData['is_free'] )
            $standardFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";            
        else
            $standardFields .= "<div field_type='$fieldKey' disabled='disabled' class='button um_field_selecor' onclick='umGetProMessage(this)'>$fieldValue</div>";
    }else{
        $standardFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";
    }
}
            
$formattingFields = null;
foreach( $userMeta->getFields( 'field_group', 'formatting', 'title' ) as $fieldKey => $fieldValue ){
    if( $userMeta->isPro() )
        $formattingFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";
    else
        $formattingFields .= "<div field_type='$fieldKey' disabled='disabled' class='button um_field_selecor' onclick='umGetProMessage(this)'>$fieldValue</div>";
}

return array(
    'wp_default'    => $wpFields,
    'standard'      => $standardFields,
    'formatting'    => $formattingFields,
);

?>