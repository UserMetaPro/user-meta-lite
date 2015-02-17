<?php
global $userMeta; 
// Expected $csvCache, $maxSize
?>


<div class="wrap">
    <div id="icon-users" class="icon32 icon32-posts-page"><br /></div>  
    <h2><?php _e( 'Export & Import', $userMeta->name ); ?></h2>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <div id="um_admin_content">
                <?php echo $userMeta->proDemoImage( 'export-import.png' ); ?>                                          
            </div>                       
            
            <div id="um_admin_sidebar">                            
                <?php
                echo $userMeta->metaBox( __( '3 steps to get started', $userMeta->name ),  $userMeta->boxHowToUse());               
                if( !@$userMeta->isPro )
                    echo $userMeta->metaBox( __( 'User Meta Pro', $userMeta->name ),   $userMeta->boxGetPro());
                echo $userMeta->metaBox( 'Shortcodes',   $userMeta->boxShortcodesDocs());
                ?>
            </div>
        </div>
    </div>     
</div>
