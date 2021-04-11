if (is_admin()) {
    add_action('wp_loaded', 'Initializer::enqueue_scripts_backend');
	add_action('after_setup_theme', 'Option_Panel::init_option_panel');
}