<?php defined( 'ABSPATH' ) || exit( 'No Access!' );

class Initializer
{
    

    public static function enqueue_scripts_backend()
    {
        wp_enqueue_media();
        wp_enqueue_style('uikit.min',  THEME_ASSETS_CSS.'uikit.min.css', false, THEME_VERSION );
        wp_enqueue_style('uikit-rtl.min',  THEME_ASSETS_CSS.'uikit-rtl.min.css', false, THEME_VERSION );
        wp_enqueue_style('admin-styles',  THEME_ASSETS_CSS.'admin-styles.css', false, THEME_VERSION );
        /********* scripts *********/
        wp_enqueue_script('uikit.min', THEME_ASSETS_JS . 'uikit.min.js', false, THEME_VERSION, true);
        wp_enqueue_script('uikit-icons.min', THEME_ASSETS_JS . 'uikit-icons.min.js', false, THEME_VERSION, true);
  
    }


}
