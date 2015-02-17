<?php
global $userMeta;

$isPro = $userMeta->isPro();

$html = null;

$html .= "<h4>$userMeta->title " . sprintf( __( "Version: %s", $userMeta->name ), $userMeta->version) . "</h4>";
$html .= "<div class='pf_divider'></div>"; 

if( $isPro ){    
    $html .= "<p><strong>" .  sprintf( __( "Your license is validated. %s is Installed and active", $userMeta->name ), $userMeta->title) . "</strong> ";
    $html .= $userMeta->createInput( "", "button", array(
        "value" => __( 'Update Credentials', $userMeta->name ),
        "id"    => "um_activation_link",
        "class" => "button",
    ) );    
    $html .="</p>";
       
    $formStyle  = 'style="display:none"';
    $formMsg    = __( 'Enter your email and password for %s', $userMeta->name );
    $formMsg .= $userMeta->createInput( "", "button", array(
        "value"     => __( 'Withdraw License', $userMeta->name ),
        "id"        => "um_deactivation_link",
        "class"     => "button-secondary pf_right",
    ) );    
    
}else{
    $formStyle  = '';
    if( $userMeta->isPro )
        $formMsg    = __( 'Enter your email and password for %s to activate the pro version.', $userMeta->name );
    else
        $formMsg    = __( 'Enter your email and password for %s for upgrade to pro version.', $userMeta->name );    
}


$html .= "<form id=\"um_activation_form\" method=\"post\" $formStyle onsubmit=\"umAuthorizePro(this); return false;\" >";

$html .= '<p> '. sprintf( $formMsg, make_clickable( $userMeta->website ) ) . '</p>';

if( !$isPro )
    $html .= $getLicense = "<a href=\"{$userMeta->website}/registration/\" class=\"button-primary pf_right\">" . __( 'Get License', $userMeta->name ) . '</a>';

$html .= $userMeta->createInput( 'account_email', 'text', array(
    'id'            => 'account_email',
    'label'        => '<strong>' . __( 'Email', $userMeta->name ) . '</strong>',
    'class'         => 'validate[required,custom[email]]',
    'label_class'   => 'um_label_left',
    'style'         => 'width:200px;',
    'enclose'       => 'p',
) );    

$html .= $userMeta->createInput( 'account_pass', 'password', array(
    'id'            => 'account_pass',
    'label'         => '<strong>' . __( 'Password', $userMeta->name ) . '</strong>',
    'class'         => 'validate[required]',
    'label_class'   => 'um_label_left',
    'style'         => 'width:200px;',
    'enclose'       => 'p',
) );   

$html .= "<input type=\"hidden\" name=\"action_type\" value=\"authorize_pro\">";    

$html .= $userMeta->nonceField();

if( $isPro ){
    $html .= $userMeta->createInput( "save_field", "button", array(
        "value" => __( 'Cancel', $userMeta->name ),
        "id"    => "um_cancel_link",
        "class" => "button-secondary",
        "style" => "margin-left:150px;",
        "after" => "&nbsp;&nbsp;",
    ) );   
}

$html .= $userMeta->createInput( "save_field", "submit", array(
    "value" => $isPro ? __( 'Update', $userMeta->name ) : __( 'Validate', $userMeta->name ),
    "id"    => "authorize_pro",
    "class" => "button-secondary",
    "style" => !$isPro ? "margin-left:150px;" : "",
) );


if( !$userMeta->isPro && $userMeta->isLicenceValidated() )
    $html .= " <strong><a href='" . $userMeta->pluginUpdateUrl() . "'>". __( 'Click to upgrade to Pro!', $userMeta->name ) ."</a></strong> ";

$html .= "</form>";

if( is_multisite() ){
	if( is_super_admin() )
		$confirmMsg = __( 'This will withdraw license from all sites under the network. Are you sure you want to withdraw pro license from all sites?', $userMeta->name );
}else
	$confirmMsg = __( 'Are you sure you want to withdraw pro license from this site?', $userMeta->name );

$html .= "\n\r" . '<script type="text/javascript">';
$html .= 'jQuery(document).ready(function(){';
    $html .= 'jQuery("#um_activation_link").click(function(){jQuery("#um_activation_form").fadeToggle();});';
    $html .= 'jQuery("#um_cancel_link").click(function(){jQuery("#um_activation_form").fadeOut();});';
    $html .= 'jQuery("#um_deactivation_link").click(function(){if(confirm("' . $confirmMsg . '")){umWithdrawLicense(this);}});';
$html .= '});';
$html .= '</script>' . "\n\r";    


echo $userMeta->metaBox( __( 'User Meta Pro Account Information', $userMeta->name ), $html );
?>