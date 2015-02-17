<?php
global $userMeta;
// Expect: $filepath, $field_name, $avatar, $width, $height, $crop, $readonly

$html = null;

// If avatar
if ( ! empty( $avatar ) ) :
    $html .= $avatar;

// Showing Uploaded file
elseif ( ! empty( $filepath ) ) :
    $uploads    = $userMeta->determinFileDir( $filepath );

    if ( empty( $uploads ) ) return;

    $path       = $uploads['path'];
    $url        = $uploads['url'];
    
    $fileData   = pathinfo( $path );
    $fileName   = $fileData['basename'];      

    // In case of image
    if ( $userMeta->isImage( $url ) ) {
        if ( ! empty( $width ) && ! empty( $height ) ) {
            
            /**
             * image_resize is depreated from version 3.5 
             */
            if ( version_compare( get_bloginfo('version'), '3.5', '>=' ) ) {
                $image = wp_get_image_editor( $path );
                if ( ! is_wp_error( $image ) ) {
                    $image->resize( $width, $height, $crop );
                    $image->save( $path );
                }                
            } else {
                $resizedImage = image_resize( $path, $width, $height, $crop );
                if ( !is_wp_error($resizedImage) )
                    $path = $resizedImage;               
            }     
            
            //$url    = str_replace( $uploads['basedir'], $uploads['baseurl'], $path );
            //$filepath   = str_replace( $uploads['basedir'], '', $path );            
            
        }        
        $html.= "<img src='$url' alt='$fileName' title='$fileName' />";
        
    } else
        $html.= "<a href='$url'>$fileName</a>";           
endif;

// Remove Link
if( ( !empty( $avatar ) || !empty( $filepath ) ) && empty( $readonly ) )
    $html .= "<p><a href='#' onclick='umRemoveFile(this)' name='$field_name'>". __('Remove', $userMeta->name) ."</a><p>";

// Hidden field
if( !empty( $field_name ) AND empty( $readonly ) )
    $html.= "<input type='hidden' name='$field_name' value='$filepath' />";
            
