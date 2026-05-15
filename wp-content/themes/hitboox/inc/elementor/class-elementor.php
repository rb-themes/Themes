<?php

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Hitboox_Elementor')) :

    /**
     * The Hitboox Elementor Integration class
     */
    class Hitboox_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('wp_enqueue_scripts', [$this, 'add_styles'], 9);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);
            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/controls_registered', [$this, 'add_icons']);

            if (!hitboox_is_elementor_pro_activated()) {
                require get_theme_file_path( 'inc/elementor/custom-css.php');
                require get_theme_file_path( 'inc/elementor/sticky-section.php');
                if (is_admin()) {
                    add_action('manage_elementor_library_posts_columns', [$this, 'admin_columns_headers']);
                    add_action('manage_elementor_library_posts_custom_column', [$this, 'admin_columns_content'], 10, 2);
                }
                require get_theme_file_path('inc/elementor/motion-fx/controls-group.php');
                require get_theme_file_path('inc/elementor/motion-fx/module.php');
            }

            require get_theme_file_path('inc/elementor/modules/page-settings.php');
            if (function_exists('hfe_init')) {
                require get_theme_file_path('inc/elementor/modules/header-settings.php');
            }

            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('hitboox-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts['General Sans'] = 'system';
            $fonts['Realce'] = 'system';
            return $fonts;
        }

        public function admin_columns_headers($defaults) {
            $defaults['shortcode'] = esc_html__('Shortcode', 'hitboox');

            return $defaults;
        }

        public function admin_columns_content($column_name, $post_id) {
            if ('shortcode' === $column_name) {
                ob_start();
                ?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr($post_id); ?>']"/>
                <?php
                ob_get_contents();
            }
        }

        public function add_js() {

            wp_enqueue_script('hitboox-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), [], HITBOOX_VERSION);
        }

        public function add_style_editor() {

            wp_enqueue_style('hitboox-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], HITBOOX_VERSION);
        }

        public function add_styles() {
            wp_enqueue_style('e-swiper');
        }

        public function add_scripts() {

            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('hitboox-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', HITBOOX_VERSION);
            wp_style_add_data('hitboox-elementor', 'rtl', 'replace');

            // Add Scripts

            $e_swiper_latest     = Plugin::$instance->experiments->is_feature_active('e_swiper_latest');
            $e_swiper_asset_path = $e_swiper_latest ? 'assets/lib/swiper/v8/' : 'assets/lib/swiper/';
            $e_swiper_version    = $e_swiper_latest ? '8.4.5' : '5.3.6';
            wp_register_script(
                'swiper',
                plugins_url('elementor/' . $e_swiper_asset_path . 'swiper.js', 'elementor'),
                [],
                $e_swiper_version,
                true
            );
        }

        public function register_auto_scripts_frontend() {
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_register_script('hitboox-elementor-swiper', get_theme_file_uri('/assets/js/elementor-swiper' . $suffix . '.js'), array('jquery', 'elementor-frontend'), HITBOOX_VERSION, true);
            // Register auto scripts frontend

            $files  = glob(get_theme_file_path('/assets/js/elementor/*' . $suffix . '.js'));
            foreach ($files as $file) {
                $file_name = wp_basename($file);
                $handle    = str_replace($suffix.".js", '', $file_name);
                $scr       = get_theme_file_uri('/assets/js/elementor/' . $file_name);
                if (file_exists($file)) {
                    wp_register_script('hitboox-elementor-' . $handle, $scr, ['jquery', 'elementor-frontend'], HITBOOX_VERSION, true);
                }
            }
        }

        public function register_widget_category($this_cat) {
            $this_cat->add_category(
                'hitboox-addons',
                [
                    'title' => esc_html__('Hitboox Addons', 'hitboox'),
                    'icon'  => 'fa fa-plug',
                ]
            );
            return $this_cat;
        }

        public function add_animations_scroll($animations) {
            $animations['Hitboox Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            require 'base-swiper-widget.php';
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        public function add_icons( $manager ) {
            $new_icons = json_decode( '{"hitboox-icon-chat":"chat","hitboox-icon-chevron-down":"chevron-down","hitboox-icon-chevron-left":"chevron-left","hitboox-icon-chevron-right":"chevron-right","hitboox-icon-chevron-up":"chevron-up","hitboox-icon-coding":"coding","hitboox-icon-console":"console","hitboox-icon-excellence":"excellence","hitboox-icon-eye":"eye","hitboox-icon-handshake":"handshake","hitboox-icon-help":"help","hitboox-icon-hologram":"hologram","hitboox-icon-low-price":"low-price","hitboox-icon-pencil":"pencil","hitboox-icon-play":"play","hitboox-icon-puzzle":"puzzle","hitboox-icon-quote":"quote","hitboox-icon-search-plus":"search-plus","hitboox-icon-strategy":"strategy","hitboox-icon-swords":"swords","hitboox-icon-360":"360","hitboox-icon-arrow-down":"arrow-down","hitboox-icon-arrow-left":"arrow-left","hitboox-icon-arrow-right":"arrow-right","hitboox-icon-arrow-up":"arrow-up","hitboox-icon-arrows-rotate":"arrows-rotate","hitboox-icon-bars":"bars","hitboox-icon-cart-empty":"cart-empty","hitboox-icon-check-square":"check-square","hitboox-icon-checked":"checked","hitboox-icon-circle":"circle","hitboox-icon-cloud-download-alt":"cloud-download-alt","hitboox-icon-comment":"comment","hitboox-icon-comments":"comments","hitboox-icon-contact":"contact","hitboox-icon-credit-card":"credit-card","hitboox-icon-dot-circle":"dot-circle","hitboox-icon-edit":"edit","hitboox-icon-envelope":"envelope","hitboox-icon-expand-alt":"expand-alt","hitboox-icon-expand":"expand","hitboox-icon-external-link-alt":"external-link-alt","hitboox-icon-file-alt":"file-alt","hitboox-icon-file-archive":"file-archive","hitboox-icon-folder-open":"folder-open","hitboox-icon-folder":"folder","hitboox-icon-frown":"frown","hitboox-icon-gift":"gift","hitboox-icon-grid":"grid","hitboox-icon-grip-horizontal":"grip-horizontal","hitboox-icon-heart-fill":"heart-fill","hitboox-icon-heart":"heart","hitboox-icon-history":"history","hitboox-icon-home":"home","hitboox-icon-info-circle":"info-circle","hitboox-icon-instagram":"instagram","hitboox-icon-level-up-alt":"level-up-alt","hitboox-icon-list":"list","hitboox-icon-map-marker-check":"map-marker-check","hitboox-icon-meh":"meh","hitboox-icon-minus-circle":"minus-circle","hitboox-icon-minus":"minus","hitboox-icon-mobile-android-alt":"mobile-android-alt","hitboox-icon-money-bill":"money-bill","hitboox-icon-office":"office","hitboox-icon-pencil-alt":"pencil-alt","hitboox-icon-phone":"phone","hitboox-icon-plus":"plus","hitboox-icon-random":"random","hitboox-icon-reply-all":"reply-all","hitboox-icon-reply":"reply","hitboox-icon-search":"search","hitboox-icon-shield-check":"shield-check","hitboox-icon-shopping-basket":"shopping-basket","hitboox-icon-sign-out-alt":"sign-out-alt","hitboox-icon-smile":"smile","hitboox-icon-spinner":"spinner","hitboox-icon-square":"square","hitboox-icon-star":"star","hitboox-icon-sync":"sync","hitboox-icon-tachometer-alt":"tachometer-alt","hitboox-icon-thumbtack":"thumbtack","hitboox-icon-times-circle":"times-circle","hitboox-icon-times-square":"times-square","hitboox-icon-times":"times","hitboox-icon-trophy-alt":"trophy-alt","hitboox-icon-adobe":"adobe","hitboox-icon-amazon":"amazon","hitboox-icon-android":"android","hitboox-icon-angular":"angular","hitboox-icon-apper":"apper","hitboox-icon-apple":"apple","hitboox-icon-atlassian":"atlassian","hitboox-icon-behance":"behance","hitboox-icon-bitbucket":"bitbucket","hitboox-icon-bitcoin":"bitcoin","hitboox-icon-bity":"bity","hitboox-icon-bluetooth":"bluetooth","hitboox-icon-btc":"btc","hitboox-icon-centos":"centos","hitboox-icon-chrome":"chrome","hitboox-icon-codepen":"codepen","hitboox-icon-cpanel":"cpanel","hitboox-icon-discord":"discord","hitboox-icon-dochub":"dochub","hitboox-icon-docker":"docker","hitboox-icon-dribbble":"dribbble","hitboox-icon-dropbox":"dropbox","hitboox-icon-drupal":"drupal","hitboox-icon-ebay":"ebay","hitboox-icon-facebook-c":"facebook-c","hitboox-icon-facebook-f":"facebook-f","hitboox-icon-facebook":"facebook","hitboox-icon-figma":"figma","hitboox-icon-firefox":"firefox","hitboox-icon-google-plus":"google-plus","hitboox-icon-google":"google","hitboox-icon-grunt":"grunt","hitboox-icon-gulp":"gulp","hitboox-icon-html5":"html5","hitboox-icon-ios":"ios","hitboox-icon-joomla":"joomla","hitboox-icon-link-brand":"link-brand","hitboox-icon-linkedin":"linkedin","hitboox-icon-mailchimp":"mailchimp","hitboox-icon-nintendo-switch":"nintendo-switch","hitboox-icon-opencart":"opencart","hitboox-icon-paypal":"paypal","hitboox-icon-pinterest-p":"pinterest-p","hitboox-icon-playstation":"playstation","hitboox-icon-reddit":"reddit","hitboox-icon-skype":"skype","hitboox-icon-slack":"slack","hitboox-icon-snapchat":"snapchat","hitboox-icon-spotify":"spotify","hitboox-icon-steam":"steam","hitboox-icon-trello":"trello","hitboox-icon-twitter-x":"twitter-x","hitboox-icon-twitter":"twitter","hitboox-icon-vimeo":"vimeo","hitboox-icon-whatsapp":"whatsapp","hitboox-icon-wordpress":"wordpress","hitboox-icon-xbox":"xbox","hitboox-icon-yoast":"yoast","hitboox-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native($tabs) {

            $tabs['opal-custom'] = [
                'name'          => 'hitboox-icon',
                'label'         => esc_html__('Hitboox Icon', 'hitboox'),
                'prefix'        => 'hitboox-icon-',
                'displayPrefix' => 'hitboox-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => HITBOOX_VERSION,
                'fetchJson'     => get_theme_file_uri('/inc/elementor/icons.json'),
                'native'        => true,
            ];

            return $tabs;
        }
    }

endif;

return new Hitboox_Elementor();
