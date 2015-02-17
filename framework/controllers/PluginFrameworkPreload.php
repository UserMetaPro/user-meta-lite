<?php
if ( ! class_exists( 'PluginFrameworkPreload' ) ) :
class PluginFrameworkPreload {
    
    function __construct() {
        global $pluginFramework;
                    
        //enque scripts/style
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueCommonScripts' ) );
        add_action( 'wp_enqueue_scripts',    array( $this, 'enqueCommonScripts' ) );           
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueAdminScripts' ) );
        add_action( 'wp_enqueue_scripts',    array( $this, 'enqueFrontScripts' ) );  
        
        add_action( 'admin_print_scripts',  array( $this, 'setVariable' ) );
        add_action( 'wp_print_scripts',     array( $this, 'setVariable' ) );
        
        //enque conditional script/style for shortcode
        //add_filter( 'the_posts', array( $this, 'enqueShortcodeScripts' ));                                                                 
    }                    
    
       
    function setVariable() {
        global $pluginFramework;            
        $ajaxurl    = admin_url( 'admin-ajax.php' );
        $nonceText  = $pluginFramework->settingsArray( 'nonce' );
        $nonce      = wp_create_nonce( $nonceText );
        
        if ( is_admin() )
            echo "<script type='text/javascript'>pf_nonce='$nonce';</script>";
        else
            echo "<script type='text/javascript'>ajaxurl='$ajaxurl';pf_nonce='$nonce';</script>";
    }
    
    /**
     * Enqueing common scripts/style
     * Called twice by add_action( 'admin_enqueue_scripts', array( $this, 'enqueCommonScripts' ) );
     * and add_action( 'wp_enqueue_scripts', array( $this, 'enqueCommonScripts' ) );
     */
    function enqueCommonScripts() {
        global $userMeta;  
        if ( ! isset( $userMeta->scripts['common'] ) ) return;
                    
        foreach ( $userMeta->scripts['common'] as $data ) {
            if ( $data['type'] == 'js' )
                wp_enqueue_script( $data['handle'], $data['url'], array( 'jquery' ) );
            elseif ( $data['type'] == 'css' )
                wp_enqueue_style( $data['handle'], $data['url'] );
        }                
                                 
    }        
    
    /**
     * Enqueing admin side script/style
     * load all scripts for admin or conditional loading
     * called once at add_action( 'admin_enqueue_scripts', array( $this, 'enqueAdminScripts' ) );
     */
    function enqueAdminScripts( $hook ) {
        if ( ! is_admin() ) return;
        
        global $userMeta;  
        if ( ! isset( $userMeta->scripts['admin'] ) ) return;     
        
        foreach ( $userMeta->scripts['admin'] as $data ) { 
            $loadScript = true;
            if ( $data['depends'] ) {
                if ( $data['depends'] != $hook )
                    $loadScript = false;
            }
            
            if ($loadScript ) {
                if ( $data['type'] == 'js' )
                    wp_enqueue_script( $data['handle'], $data['url'], array('jquery') );
                elseif ( $data['type'] == 'css' )
                    wp_enqueue_style( $data['handle'], $data['url'] );                    
            }
        }                    
    }
    
    /**
     * Enquing front side script/style.
     * Loading all or condional by post id
     * called once by add_action( 'wp_enqueue_scripts', array( $this, 'enqueFrontScripts' ) );
     */
    function enqueFrontScripts() {
        if( is_admin() ) return;
        
        global $userMeta, $post;  
        if ( ! isset( $userMeta->scripts['front'] ) ) return;        
        
        foreach ( $userMeta->scripts['front'] as $data ) { 
            $loadScript = true;
            if ( $data['depends'] ) {
                if ( $data['depends'] != $post->ID )
                    $loadScript = false;
            }
            
            if ( $loadScript ) {
                if ( $data['type'] == 'js' )
                    wp_enqueue_script( $data['handle'], $data['url'], array( 'jquery' ) );
                elseif ( $data['type'] == 'css' )
                    wp_enqueue_style( $data['handle'], $data['url'] );                    
            }
        }                  
    }
    

    
    
    /**
     * Find shortcodes in post
     * If found then enque related script/style
     * calling once by filter : add_filter( 'the_posts', array( $this, 'enqueShortcodeScripts' ));
     */
    function enqueShortcodeScripts($posts) {
    	if ( empty( $posts ) ) return $posts;
        
        global $userMeta;  
        if ( ! isset( $userMeta->scripts['shortcode'] ) )
            return $posts;
        
        //searching for shortcode in post             
        $found_shortcode = array();             
        foreach ( $userMeta->scripts['shortcode'] as $shortcode => $val ) {
        	foreach ($posts as $post) {
        		if ( stripos( $post->post_content, "[$shortcode") !== false ) {
        			$found_shortcode[] = $shortcode; 
        			break;
        		}
        	}                     
        }
        
        //enque script/style 
        foreach ( array_unique( $found_shortcode ) as $shortcode ) {
            foreach ( $userMeta->scripts['shortcode'][$shortcode] as $data ) {
                if ( $data['type'] == 'js' )
                    wp_enqueue_script( $data['handle'], $data['url'], array( 'jquery' ) );
                elseif ( $data['type'] == 'css' )
                    wp_enqueue_style( $data['handle'], $data['url'] );
            }
        }

    	return $posts;            
    }
                
}
endif;
