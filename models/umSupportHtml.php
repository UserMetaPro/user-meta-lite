<?php

if ( ! class_exists( 'umSupportHtml' ) ) :
class umSupportHtml {
    
    function boxHowToUse() {
        global $userMeta;
        
        $html = null;
        $html .= sprintf( __( '<p><strong>Step 1.</strong> Create Field from User Meta %s page.</p>', $userMeta->name ), $userMeta->adminPageUrl('fields_editor') );
        $html .= sprintf( __( '<p><strong>Step 2.</strong> Go to User Meta %s page. Choose a Form Name, drag and drop fields from right to left and save the form.</p>', $userMeta->name ), $userMeta->adminPageUrl('forms_editor') );
        $html .= sprintf( __( '<p><strong>Step 3.</strong> Write shortcode to your page or post. Shortcode (e.g.): %s</p>', $userMeta->name ), '[user-meta-profile form="Form_Name"]'  );
        $html .= "<div><center><a class=\"button-primary\" href=\"" . $userMeta->website .  "\">". __( 'Visit Plugin Site', $userMeta->name ) ."</a></center></div>";
        return $html;
    }
    
    function boxGetPro() {
        global $userMeta;
        
        $html = null;
        $html .= "<div style='padding-left: 10px'>";
        $html .= "<p>Get <strong>User Meta Pro</strong> for : </p>";
        $html .= "<li>Frontend Login shortcode and widget.</li>";
        $html .= "<li>Allow user to login with their Email or Username.</li>";
        $html .= "<li>Add extra fields to backend profile.</li>";
        $html .= "<li>Role based user redirection on login, logout and registratioin.</li>";
        $html .= "<li>User activatation/deactivation, Admin approval on new user registration.</li>";
        $html .= "<li>Customize email notification with including extra field's data.</li>";
        $html .= "<li>Frontend password reset.</li>";
        $html .= "<p></p>";
        $html .= "<li>Advanced fields for creating profile/registration form.</li>";        
        $html .= "<li>Fight against spam by Captcha.</li>";
        $html .= "<li>Brake your form into multiple page.</li>";
        $html .= "<li>Group fields using Section Heading.</li>";
        $html .= "<li>Allow user to upload their file by File Upload.</li>";
        $html .= "<li>Country Dropdown for country selection.</li>";        
        $html .= "<br />";
        $html .= "<center><a class='button-primary' href='http://user-meta.com'>Get User Meta Pro</a></center>";
        $html .= "</div>";
        return $html;
    }    
    
    function boxShortcodesDocs() {
        global $userMeta;
        
        $html = null;
        $html .= "<div style='padding-left: 10px'>";  
        $html .= '<p><div><strong>Profile shortcode</div></strong>[user-meta-profile form="Form_Name"]</p>'; 
        $html .= '<p><div><strong>Registration shortcode</strong></div>[user-meta-registration form="Form_Name"]</p>'; 
        $html .= '<p><div><strong>Login shortcode</strong></div>[user-meta-login] OR [user-meta-login form="Form_Name"]</p>'; 
        $html .= '<p><div><strong>Field shortcode</strong></div>[user-meta-field id=Field_ID]</p>'; 
        $html .= '<p><div><strong>Field content shortcode</strong></div>[user-meta-field-value id=Field_ID] OR [user-meta-field-value key=meta_key]</p>'; 
        $html .= '<p><div><strong>Profile / Registration</strong></div><div>[user-meta type=profile-registration form="Form_Name"]</div><div><em>(To show user profile if user logged in, or showing registration form, if user not logged in.)</em></div></p>'; 
        $html .= '<p><div><strong>Public profile</strong></div><div>[user-meta type=public form="Form_Name"]</div><div><em>(To show public profile if user_id parameter provided as GET request.)</em></div></p>'; 
        $html .= "<p></p>";
        if ( ! $userMeta->isPro() )
            $html .= __( '<p><strong>Note: </strong>user-meta-login, user-meta-field and user-meta-field_value shortcode is only supported for pro version.</p>', $userMeta->name );
        $html .= "<center><a class='button-primary' href='http://user-meta.com/documentation/shortcodes/'>". __( 'Read More', $userMeta->name ) ."</a></center>";
        $html .= "</div>";
        return $html;        
    }
    
    function boxTips() {
        global $userMeta;
        
        $html = "<div style='padding-left: 10px'>";
        $html .= "</div>";
        return $html; 
    }
    
    function getProLink( $label=null ) {
        global $userMeta;
        
        $label = $label ? $label : $userMeta->website;
        return "<a href=\"{$userMeta->website}\">$label</a>";
    }    
    
    function proDemoImage( $img = null ) {
        global $userMeta;
        
        $html = "<p style=\"color:red\"><strong>This feature is only supported in Pro version. Get <a href=\"{$userMeta->website}\">User Meta Pro</a></strong></p>";
        if ( $img )
            $html .= "<img src=\"https://s3.amazonaws.com/user-meta/public/plugin/images/{$img}?ver={$userMeta->version}\" width=\"100%\" onclick=\"umGetProMessage(this)\" />";
            
        return $html;
    }
}
endif;
