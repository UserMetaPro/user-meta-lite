<?php

if ( ! class_exists( 'umInitPlugin' ) ) :
class umInitPlugin {    
        
    function pluginInit() {
        global $userMeta;
        
        $userMeta->checkPro();       
        $userMeta->loadDirectory( $userMeta->modelsPath . 'classes/' );
        
        if ( $userMeta->isPro ) {
            $userMeta->loadModels( $userMeta->modelsPath . 'pro/' );
            $userMeta->loadModels( $userMeta->modelsPath . 'enc/', true );
        }
        
        $this->loadExtension();
        
        $userMeta->loadControllers( $userMeta->controllersPath );
        $userMeta->loadDirectory( $userMeta->pluginPath . '/helper/' );
        $userMeta->loadDirectory( $userMeta->pluginPath . '/addons/' );
        
        if ( ! empty( $userMeta->extensions ) ) {
            foreach ( $userMeta->extensions as $extName => $extPath ) {
                $userMeta->loadModels( $extPath . '/models/' );
                $userMeta->loadModels( $extPath . '/models/pro/' );
                $userMeta->loadControllers( $extPath . '/controllers/' );
                $userMeta->loadDirectory( $extPath . '/helper/' );
            }
        }
    }
    
    function loadExtension() {
        global $userMeta;
        $extensions = apply_filters( 'user_meta_load_extension', array() );
        $userMeta->extensions = ! empty( $extensions ) ? $extensions : array();
    }
    
    function loadControllers( $controllersPath ) {
        global $userMeta;
        $controllersOrder = $userMeta->controllersOrder();   
                                         
        $classes = array();
        foreach ( scandir( $controllersPath ) as $file ) {
            if ( preg_match( "/.php$/i" , $file ) )
                $classes[ str_replace( ".php", "", $file ) ] = $controllersPath . $file;            
        }          
        
        $proClasses = $userMeta->loadProControllers( $classes, $controllersPath );
        if ( is_array($proClasses) )
            $classes = $proClasses;
                
        foreach ( $classes as $className => $classPath ) {
            require_once( $classPath );
            if ( ! in_array( $className, $controllersOrder ) )
                $controllersOrder[] = $className;
        }
                          
        foreach ( $controllersOrder as $className ) {
            if ( class_exists( $className ) )
                $instance[] = new $className;
        }
              
        return $instance;        
    }     

    function renderPro( $viewName, $parameter = array(), $subdir=null, $ob = false ) {
        global $userMeta;        
                
        $viewPath = self::locateView( $viewName, $subdir );
        if ( ! $viewPath ) return;
        
        if ( $parameter ) extract($parameter);    
        
        if ( $ob ) ob_start();
        
        $pageReturn = include $viewPath;
        
        if ( $ob ) {
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
         
        if ( $pageReturn AND $pageReturn <> 1 )
            return $pageReturn;
        
        if ( isset($html ) ) return $html;        
    }   
    
    function locateView( $viewName, $subdir=null ) {
        global $userMeta;
        
        $locations  = array();
        if ( $subdir )
            $subdir = $subdir . '/';
        
        $proLocations = $userMeta->locateProView( $locations );
        if ( is_array( $proLocations ) )
            $locations = $proLocations;
        
        foreach ( $userMeta->extensions as $extName => $extPath )
            $locations[] = $extPath . '/views/';
        $locations[] = $userMeta->viewsPath;

        foreach ( $locations as $path ) {
            $fullPath = $path . $subdir . $viewName . '.php';
            if ( file_exists( $fullPath ) )
                return $fullPath;
        }
        
        return false;
    }
               
}
endif;