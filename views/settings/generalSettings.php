<?php
global $userMeta;
//Expected: $general

$html = null;
      
// Start Profile Page Selection  
$html .= "<h4>". __( 'Profile Page Selection', $userMeta->name ) ."</h4>";      
$html .= wp_dropdown_pages(array(
    'name'              => 'general[profile_page]',
    'selected'          => isset( $general[ 'profile_page' ] )? $general[ 'profile_page' ] : null,
    'echo'              => 0,
    'show_option_none'  =>'None ',
));
$html .= $userMeta->createInput( "general[profile_in_admin]", "checkbox", array(
    'value' => isset( $general[ 'profile_in_admin' ] )? $general[ 'profile_in_admin' ] : null,
    'id'    => 'um_general_profile_in_admin',
    'label' => sprintf( __( 'Show profile link to <a href="%s">Users<a> administration page.', $userMeta->name ), admin_url( 'users.php' ) ),
) );             
$html .= "<p>" . sprintf( __( "Profile page should contain shortcode like: %s", $userMeta->name ), "[user-meta-profile form=\"your_form_name\"]") . "</p>";    
// End Profile Page Selection

$html .= "<div class='pf_divider'></div>";  

$emailFormat = array(  
    ''              => null, 
    'text/plain'    => __( 'Plain Text', $userMeta->name ), 
    'text/html'     => __( 'HTML', $userMeta->name ),
);

$html .= "<h4>". __( 'E-mail Sender Setting', $userMeta->name ) ."</h4>"; 

$html .= "<p>". __( 'Set default email sender information', $userMeta->name ) . "</p>";

$html .= $userMeta->createInput("general[mail_from_name]", "text", array(
    'label'     => __('From Name:', $userMeta->name ),
    'value'     => @$general[ 'mail_from_name' ],
    'enclose'   => "p",
    'after'     => ' <em>' . __( '(Leave blank to use default)', $userMeta->name ) . '</em>',
    'style'     => 'width:300px;',
));
    
$html .= $userMeta->createInput("general[mail_from_email]", "text", array(
    'label'     => __('From Email:', $userMeta->name ),
    'value'     => @$general[ 'mail_from_email' ],
    'enclose'   => "p",
    'after'     => ' <em>' . __( '(Leave blank to use default)', $userMeta->name ) . '</em>',
    'style'     => 'width:300px;',
));
    
$html .= $userMeta->createInput("general[mail_content_type]", "select", array(
    'label'     => __('Email Format:', $userMeta->name ),
    'value'     => @$general[ 'mail_content_type' ], //!empty( $general[ 'mail_content_type' ] ) ? $general[ 'mail_content_type' ] : apply_filters( 'wp_mail_content_type', 'text/plain' ),
    'enclose'   => 'p',
    'by_key'    => true,
), $emailFormat );


//echo $userMeta->metaBox( "General Settings", $html );
?>