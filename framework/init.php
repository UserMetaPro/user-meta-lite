<?php
/**
 * Framework for wordpress plugin
 * Primary framework class is pluginFramework
 * global varaiable $pluginFramework are available as instance of pluginFramework class
 * all classes from models directory are preloaded with $pluginFramework
 * any method of models class can be access as $pluginFramework->methodNmae(); 
 */
 
 
 /**
  * ====== Convension =====
  * Plugin instance should be able to retrieve all method of framework models and plugins models
  * Use plugin instance (eg. $userMeta) for accessing framework/plugins model's' method
  */

if ( realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    exit( 'Please don\'t access this file directly.' );
}

if ( ! class_exists( 'pluginFramework' ) ) :
class pluginFramework {
    public $version = '1.0.0';
    public $prefix  = 'pf_';
    public $name    = 'plugin-framework';
    
    public $frameworkPath;
    public $modelsPath;
    public $controllersPath;
    public $viewsPath;
    public $pluginPath;
    
    public $frameworkUrl;
    public $pluginUrl;
    public $assetsUrl;
           
    public $scripts = array();
    
    
    function __construct() {                          
        $this->frameworkPath   = dirname( __FILE__ );
        $this->modelsPath      = $this->frameworkPath . '/models/';
        $this->controllersPath = $this->frameworkPath . '/controllers/';
        $this->viewsPath       = $this->frameworkPath . '/views/';
        
        $this->loadModels( $this->modelsPath );
        $this->pluginPath      = $this->directoryUp( $this->frameworkPath );            
        $this->frameworkUrl    = plugins_url( '' , __FILE__ );                                         
        $this->pluginUrl       = $this->directoryUp( $this->frameworkUrl );
        $this->assetsUrl       = $this->pluginUrl . '/assets/';                                      
    }
        
    function init() {        
        add_action( 'wp_ajax_pf_ajax_request',                   array( $this, 'pfAjaxRequest' ) );
        add_action( 'wp_ajax_nopriv_pf_ajax_request',            array( $this, 'pfAjaxRequest' ) );     
        
        $this->pluginInit();        
    }
    
    function pfAjaxRequest() {  
        $methodName = @$_REQUEST['method_name'];
        if ( $methodName ) {                        
            $methodName = 'ajax' . ucwords( $methodName );
            $this->$methodName();
        }
        die();          
    }   
    
    /**
     * get data from option table with or without cache
     * @param $key      : field key without prefix.
     */
    function getData( $key, $networkwide = false ) {
        $cacheName = $this->cacheName;
        global $$cacheName;
        
        if ( ! isset( $$cacheName->$key ) ) {
			if ( $networkwide && is_multisite() )
				$$cacheName->$key = get_site_option( $this->prefixLong . $key );
			else
				$$cacheName->$key = get_option( $this->prefixLong . $key );
		}
              
        return $$cacheName->$key;
    }
    
    /**
     * Update Data to option table and set cache if triggered
     * @param $key      : field key without prefix.
     * @param $data     : Data to be set
     */
    function updateData( $key, $data, $networkwide = false ) {
        $cacheName = $this->cacheName;
        global $$cacheName;        
        
        $$cacheName->$key   = $data;

		if ( $networkwide && is_multisite() )
			return update_site_option( $this->prefixLong . $key, $data );
		else
        	return update_option( $this->prefixLong . $key, $data );
    }      

    /**
     * Load all class from models directory
     */
    function loadModels( $dir, $enc = false ) {
        $classes = ! $enc ? $this->loadDirectory( $dir ) : $this->loadEncDirectory( $dir );
        if ( ! is_array( $classes ) ) return;
        foreach ( $classes as $class )
            $this->objects[] = $class;
    }
             
    
    /**
     * Add js or css for enque
     * @param string $scriptName : script/style name with extension
     * @param string $type: where that script should loaded, arg: admin, front, shortcode, common
     * Default: common
     * @param string|int  $depends: for conditional loading, arg: name of shortcode, admin page hook, post/page id
     */
    function addScript( $scriptName, $type = null, $depends = null, $subdir = null ) {    
        $scriptData = $this->fileinfo( $scriptName );
        $handle     = $scriptData->name;      
        $scriptType = $scriptData->ext;
        $subdir     = $subdir ? "$subdir/" : null;
        $url        = $this->assetsUrl . $scriptType. '/' . $subdir . $scriptName;  
        
        $scripts = $this->scripts;  
        $type    = $type ? $type : 'common';
        
        //for enque wp script
        if ( !$scriptType ) {
            $handle = $scriptName;
            $url    = null;
            $scriptType = 'js';
        }

        if ( $type == 'shortcode' ):
            $scripts[$type][$depends][] = array(
                'handle' => $handle,
                'url'    => $url,
                'type'   => $scriptType,
            );                   
        else:
            $scripts[$type][] = array(
                'handle' => $handle,
                'url'    => $url,
                'type'   => $scriptType,
                'depends'=> $depends
            );            
        endif;
     
        $this->scripts = $scripts;
    }
    
    //sleep
    /**
     * Enque all scripts/styles those are added by addScript method
     * Should call this method after all clntroller loaded
     */
    function loadScript() {
        $scripts = $this->scripts;
        if ( empty( $scripts ) ) return;
        
        $load = false;
        foreach ( $scripts as $key => $data ) {
            switch ( $key ) {
                case 'all' :
                    $load = true;
                break;     
                
                case 'admin' :  
                    if( is_admin() )
                        $load = true;
                break;    
                
                case 'front' :
                    if( !is_admin() )
                        $load = true;
                break;    
                
                case 'post' :
                
                break;    
            }
        }
        
    }

    /**
     * Include all file from directory
     * Create instence of each class and add return all instance as an array
     */  
    function loadDirectory( $dir ) {
        if ( ! file_exists( $dir ) ) return false;
        foreach ( scandir( $dir ) as $item) {
            if ( preg_match( "/.php$/i" , $item ) ) {
                require_once( $dir . $item );
                $className = str_replace( ".php", "", $item );
                if ( class_exists( $className ) )
                    $classes[] = new $className;
            }      
        }
        return isset( $classes ) ? $classes : false;
    }                
    
    /**
     * Render view file
     * @param string $viewName: name of view file without extension
     */
    function render( $viewName, $parameter = array() ) {
        if ( $parameter ) extract( $parameter );            
        include( $this->viewsPath . $viewName . '.php' );
        if ( isset( $html ) ) return $html;
    }        
         
    /**
     * Dynamicaly call any  method from models class
     * by pluginFramework instance
     */
    function __call( $name, $args ) {
        if ( ! is_array( @$this->objects ) ) return;
        
        global $pfInstance;
        $pfInstance = $this;
        
        foreach( $this->objects as $object ) {
            if ( method_exists( $object, $name ) ) {
                $count = count( $args );
                if ( $count == 0 )
                    return $object->$name();
                elseif ( $count == 1 )
                    return $object->$name( $args[0] );
                elseif ( $count == 2 )
                    return $object->$name( $args[0], $args[1] );     
                elseif ( $count == 3 )
                    return $object->$name( $args[0], $args[1], $args[2] );      
                elseif ( $count == 4 )
                    return $object->$name( $args[0], $args[1], $args[2], $args[3] );  
                elseif ( $count == 5 )
                    return $object->$name( $args[0], $args[1], $args[2], $args[3], $args[4] );         
                elseif ( $count == 6 )
                    return $object->$name( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5] );                                                                                             
            }
        }
        return false;
    }                 
    
}
endif;

global $pluginFramework;
if ( ! is_object( $pluginFramework ) )
    $pluginFramework = new pluginFramework;
    
$pluginFramework->loadDirectory( $pluginFramework->controllersPath );