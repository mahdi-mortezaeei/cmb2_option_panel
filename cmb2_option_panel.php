<?php defined('ABSPATH') || exit('No Access!');

class Option_Panel
{

    /**
     * Default Option key
     * @var string
     */
    private $key = THEME_OPTION;

    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Tab Pages
     * @var array
     */
    protected $options_pages = array();

    /**
     * init option panel
     */
    public static function init_option_panel()
    {
        // Get it started
        $Option_Panel = new Option_Panel();
        $Option_Panel->hooks();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set our title
        $this->title = 'پیکربندی پوسته';
    }

    /**
     * Initiate our hooks
     */
    public function hooks()
    {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_options_page')); //create tab pages
    }

    /**
     * Register our setting tabs to WP
     */
    public function init()
    {
        $option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
            register_setting($option_tab['id'], $option_tab['id']);
        }
    }

    /**
     * Add menu options page
     */
    public function add_options_page()
    {
        $option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
            if ($index == 0) {
                $this->options_pages[] = add_menu_page(
                    $this->title, 
                    $this->title, 
                    'manage_options', 
                    $option_tab['id'], 
                    array($this, 'admin_page_display') , 
                    THEME_ASSETS_IMG.'panel-option-logo.svg'); 

                    //Link admin menu to first tab
                add_submenu_page(
                    $option_tabs[0]['id'], 
                    $this->title, $option_tab['title'],
                    'manage_options', $option_tab['id'], 
                    array($this, 'admin_page_display')); //Duplicate menu link for first submenu page
          
            } else {
                $this->options_pages[] = add_submenu_page(
                    $option_tabs[0]['id'], 
                    $this->title, 
                    $option_tab['title'], 
                    'manage_options', $option_tab['id'], 
                    array($this, 'admin_page_display'));
            }
        }
    }

    /**
     * Admin page markup. Mostly handled by CMB
     */
    public function admin_page_display(){

    $option_tabs = self::option_fields(); //get all option tabs
    $option_forms = array();
    $option_form_deta = array();
    ?>
    <div class=" <?php echo $this->key; ?> wrap">
        <div class="uk-grid-match uk-grid" uk-grid="">
            <div class="yara-side-menu uk-width-1-4@m uk-first-column">
                <div class="uk-card uk-card-default uk-card-body">
                    <ul class="uk-list uk-list-divider">

                        <!-- Options Page Nav Tabs -->
                        <li class="yara-menu-item yara-theme-name">
                        <img src="<?php echo THEME_ASSETS_IMG.'panel-option-logo.svg '; ?>" alt="logo">
                        <strong class="yara-theme-name"><?= YARACODE_NAME ?></strong>
                        <br>
                        <span class="yara-version"><?= THEME_VERSION ?><span>
                        </li>

                        <?php foreach ($option_tabs as $index => $option_tab):
                          $tab_slug = $option_tab['id'];
                          $nav_class = 'nav-tab yara-menu-item';
                          $option_form_deta[$index]= array(
                              'title' => $option_tab['title'],
                              'icon' => $option_tab['icon'],
                              'description' => $option_tab['description'] 
                          ) ; 
                          if ($tab_slug == $_GET['page']) {
                            $nav_class .= ' nav-tab-active yara-menu-active'; //add active class to current tab
                            $option_forms[] = $option_tab; //add current tab to forms to be rendered
                        }
                        ?>
                        <li class="<?php echo $nav_class; ?>">
                            <a href="<?php menu_page_url($tab_slug);?>"  data-toggle="general">
                                <span class="yara-menu-icon uk-icon" uk-icon="<?= $option_tab['icon'] ?>"></span>
                                <div class="yara-menu-title"><?php esc_attr_e($option_tab['title']);?></div>
                                <div class="yara-menu-description"><?php esc_attr_e($option_tab['description']);?></div>
                            </a>
                        </li>                      
                        <?php endforeach;?>
                        <!-- End of Nav Tabs -->

                    </ul>
                </div>
            </div>
        
            <div class="yara-settings-content uk-width-3-4@m">
                <div class="uk-card uk-card-default uk-card-body">
                <?php foreach ($option_forms as $index => $option_form): ?>
                <div class="yara-settings-area yara-settings-styles yara-form-active">
                    <h2 class="yara-settings-title">
                        <span class="yara-title-icon">
                            <span uk-icon="<?= $option_form_deta[$index]['icon'] ?>" class="uk-icon"></span>
                        </span>
                        <strong><?= $option_form_deta[$index]['title'] ?></strong>
                    </h2>
                </div>    
	            <div id="<?php esc_attr_e($option_form['id']);?>" class="group col-md-12">
	            	<?php cmb2_metabox_form($option_form, $option_form['id']);?>
	            </div>
	        <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
    <?php
    }


    /**
     * Defines the theme option metabox and field configuration
     * @since  0.1.0
     */
    public function option_fields()
    {

        // Only need to initiate the array once per page-load
        if (!empty($this->option_metabox)) {
            return $this->option_metabox;
        }      

        $this->option_metabox[] = array(
    
            'id' => 'general_options', //id used as tab page slug, must be unique
            'title' => 'همگانی',
            'description' => 'پیکربندی پایه پوسته',
            'icon' => 'settings',
            'show_on' => array('key' => 'options-page', 'value' => array('general_options')), //value must be same as id
            'show_names' => true,
            'classes'    => 'yara_content_general_options', // Extra cmb2-wrap classes
            'classes_cb' => 'yara_content_option_panel', // Add classes through a callback.

            'fields' => array(
                array(
                    'name' => __('Header Logo', 'theme_textdomain'),
                    'desc' => __('Logo to be displayed in the header menu.', 'theme_textdomain'),
                    'id' => 'header_logo', //each field id must be unique
                    'default' => '',
                    'type' => 'file',
                ),
                array(
                    'name' => __('Login Logo', 'theme_textdomain'),
                    'desc' => __('Logo to be displayed in the login page. (320x120)', 'theme_textdomain'),
                    'id' => 'login_logo',
                    'default' => '',
                    'type' => 'file',
                ),
                array(
                    'name' => __('Favicon', 'theme_textdomain'),
                    'desc' => __('Site favicon. (32x32)', 'theme_textdomain'),
                    'id' => 'favicon',
                    'default' => '',
                    'type' => 'file',
                ),
                array(
                    'name' => __('SEO', 'theme_textdomain'),
                    'desc' => __('Search Engine Optimization Settings.', 'theme_textdomain'),
                    'id' => 'branding_title', //field id must be unique
                    'type' => 'title',
                ),
                array(
                    'name' => __('Site Keywords', 'theme_textdomain'),
                    'desc' => __('Keywords describing this site, comma separated.', 'theme_textdomain'),
                    'id' => 'site_keywords',
                    'default' => '',
                    'type' => 'textarea_small',
                ),
            ),
        );

        $this->option_metabox[] = array(
            'id' => 'social_options',
            'title' => 'سبک‌ها',
            'description' => 'رنگ، فونت‌ها و ...',
            'icon' => 'paint-bucket',
            'show_on' => array('key' => 'options-page', 'value' => array('social_options')),
            'show_names' => true,
            'classes'    => 'yara_content_social_options',
            'classes_cb' => 'yara_content_option_panel', 
            'fields' => array(
                array(
                    'name' => __('Facebook Username', 'theme_textdomain'),
                    'desc' => __('Username of Facebook page.', 'theme_textdomain'),
                    'id' => 'facebook',
                    'default' => '',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Twitter Username', 'theme_textdomain'),
                    'desc' => __('Username of Twitter profile.', 'theme_textdomain'),
                    'id' => 'twitter',
                    'default' => '',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Youtube Username', 'theme_textdomain'),
                    'desc' => __('Username of Youtube channel.', 'theme_textdomain'),
                    'id' => 'youtube',
                    'default' => '',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Flickr Username', 'theme_textdomain'),
                    'desc' => __('Username of Flickr profile.', 'theme_textdomain'),
                    'id' => 'flickr',
                    'default' => '',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Google+ Profile ID', 'theme_textdomain'),
                    'desc' => __('ID of Google+ profile.', 'theme_textdomain'),
                    'id' => 'google_plus',
                    'default' => '',
                    'type' => 'text',
                ),
            ),
        );

        //insert extra tabs here

        return $this->option_metabox;
    }

    /**
     * Returns the option key for a given field id
     * @return array
     */
    public function get_option_key($field_id)
    {
        $option_tabs = $this->option_fields();
        foreach ($option_tabs as $option_tab) { //search all tabs
            foreach ($option_tab['fields'] as $field) { //search all fields
                if ($field['id'] == $field_id) {
                    return $option_tab['id'];
                }
            }
        }
        return $this->key; //return default key if field id not found
    }

    /**
     * Public getter method for retrieving protected/private variables
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get($field)
    {

        // Allowed fields to retrieve
        if (in_array($field, array('key', 'fields', 'title', 'options_pages'), true)) {
            return $this->{$field};
        }
        if ('option_metabox' === $field) {
            return $this->option_fields();
        }

        throw new Exception('Invalid property: ' . $field);
    }

}

/**
 * Wrapper function around cmb_get_option
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function my_option($key = '')
{
    global $Option_Panel;
    return cmb2_get_option($Option_Panel->get_option_key($key), $key);
}

