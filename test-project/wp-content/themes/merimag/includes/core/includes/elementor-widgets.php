<?php
class Merimag_Elementor_about extends \Elementor\Widget_Base
    {
    private $shortcode = "about";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
if( class_exists('WPMDM') ) {
 class Merimag_Elementor_demo_ad extends \Elementor\Widget_Base
    {
        private $shortcode = "demo_ad";public function get_name()
        {
            return "merimag-" . $this->shortcode;
        }
        public function get_title()
        {
            return merimag_get_shortcode_title($this->shortcode);
        }
        public function get_icon()
        {
            return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
        }
        public function get_categories()
        {
            return ["merimag"];
        }
        protected function register_controls()
        {
            $settings_function = "merimag_get_" . $this->shortcode . "_options";
            if (function_exists($settings_function))
            {
                $settings = $settings_function();
    foreach( $settings as $setting_id => $setting ) {
        if( $setting['type'] != 'tab' ) {
            $settings = array(
                'general-settings' => array(
                    'type' => 'tab',
                    'title' => __('General', 'merimag'),
                    'options' =>  $settings,
                ),
            );
            break;
        }
    }   
                merimag_unyson_settings_to_elementor($this, $settings);
            }
        }
        protected function render()
        {
            $atts = $this->get_settings_for_display();
    $element_id = $this->get_id();
    $atts['block_id'] = 'merimag-element-' .$element_id;
    $shortcode = str_replace( '_', '-', $this->shortcode );
    merimag_get_shortcode_html( $shortcode, $atts );
        }
    }   
}

class Merimag_Elementor_contact_infos extends \Elementor\Widget_Base
    {
    private $shortcode = "contact_infos";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
            foreach( $settings as $setting_id => $setting ) {
                if( $setting['type'] != 'tab' ) {
                    $settings = array(
                        'general-settings' => array(
                            'type' => 'tab',
                            'title' => __('General', 'merimag'),
                            'options' =>  $settings,
                        ),
                    );
                    break;
                }
            }   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_tabbed_widget extends \Elementor\Widget_Base
    {
    private $shortcode = "tabbed_widget";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function( false );
            foreach( $settings as $setting_id => $setting ) {
                if( $setting['type'] != 'tab' ) {
                    $settings = array(
                        'general-settings' => array(
                            'type' => 'tab',
                            'title' => __('General', 'merimag'),
                            'options' =>  $settings,
                        ),
                    );
                    break;
                }
            }   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_popular_categories extends \Elementor\Widget_Base
    {
    private $shortcode = "popular_categories";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
            foreach( $settings as $setting_id => $setting ) {
                if( $setting['type'] != 'tab' ) {
                    $settings = array(
                        'general-settings' => array(
                            'type' => 'tab',
                            'title' => __('General', 'merimag'),
                            'options' =>  $settings,
                        ),
                    );
                    break;
                }
            }   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_features extends \Elementor\Widget_Base
    {
    private $shortcode = "features";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_wp_menu extends \Elementor\Widget_Base
    {
    private $shortcode = "wp_menu";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_search extends \Elementor\Widget_Base
    {
    private $shortcode = "search";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_accordion extends \Elementor\Widget_Base
{
    private $shortcode = "accordion";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_image_box extends \Elementor\Widget_Base
{
    private $shortcode = "image_box";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_action extends \Elementor\Widget_Base
{
    private $shortcode = "action";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_author extends \Elementor\Widget_Base
{
    private $shortcode = "author";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
            foreach( $settings as $setting_id => $setting ) {
                if( $setting['type'] != 'tab' ) {
                    $settings = array(
                        'general-settings' => array(
                            'type' => 'tab',
                            'title' => __('General', 'merimag'),
                            'options' =>  $settings,
                        ),
                    );
                    break;
                }
            }   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}

class Merimag_Elementor_authors extends \Elementor\Widget_Base
{
    private $shortcode = "authors";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_button extends \Elementor\Widget_Base
{
    private $shortcode = "button";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_alert extends \Elementor\Widget_Base
{
    private $shortcode = "alert";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}

class Merimag_Elementor_custom_list extends \Elementor\Widget_Base
{
    private $shortcode = "custom_list";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}


class Merimag_Elementor_divider extends \Elementor\Widget_Base
{
    private $shortcode = "divider";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_dropcap extends \Elementor\Widget_Base
{
    private $shortcode = "dropcap";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_gallery extends \Elementor\Widget_Base
{
    private $shortcode = "gallery";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_gallery_tiles extends \Elementor\Widget_Base
{
    private $shortcode = "gallery_tiles";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_gallery_carousel extends \Elementor\Widget_Base
{
    private $shortcode = "gallery_carousel";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_gallery_tilesgrid extends \Elementor\Widget_Base
{
    private $shortcode = "gallery_tilesgrid";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_heading extends \Elementor\Widget_Base
{
    private $shortcode = "heading";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_special_heading extends \Elementor\Widget_Base
{
    private $shortcode = "special_heading";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_icon_box extends \Elementor\Widget_Base
{
    private $shortcode = "icon_box";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}

class Merimag_Elementor_instagram extends \Elementor\Widget_Base
{
    private $shortcode = "instagram";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_mailchimp extends \Elementor\Widget_Base
{
    private $shortcode = "mailchimp";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_embed extends \Elementor\Widget_Base
{
    private $shortcode = "embed";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_video extends \Elementor\Widget_Base
{
    private $shortcode = "video";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_image extends \Elementor\Widget_Base
{
    private $shortcode = "image";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_member extends \Elementor\Widget_Base
{
    private $shortcode = "member";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_menu extends \Elementor\Widget_Base
{
    private $shortcode = "menu";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_multi_buttons extends \Elementor\Widget_Base
{
    private $shortcode = "multi_buttons";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_ticker extends \Elementor\Widget_Base
{
    private $shortcode = "ticker";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_posts_block extends \Elementor\Widget_Base
{
    private $shortcode = "posts_block";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_posts_carousel extends \Elementor\Widget_Base
{
    private $shortcode = "posts_carousel";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_posts_grid extends \Elementor\Widget_Base
{
    private $shortcode = "posts_grid";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $render_function = "merimag_shortcode_" . $this->shortcode;
        if (function_exists($render_function))
        {
            $atts = $this->get_settings_for_display();
            $element_id = $this->get_id();
            $atts['block_id'] = 'merimag-element-' .$element_id;
            $shortcode = str_replace( '_', '-', $this->shortcode );
            merimag_get_shortcode_html( $shortcode, $atts );
        }
    }
}
class Merimag_Elementor_simple_posts_grid extends \Elementor\Widget_Base
{
    private $shortcode = "simple_posts_grid";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $render_function = "merimag_shortcode_" . $this->shortcode;
        if (function_exists($render_function))
        {
            $atts = $this->get_settings_for_display();
            $element_id = $this->get_id();
            $atts['block_id'] = 'merimag-element-' .$element_id;
            $shortcode = str_replace( '_', '-', $this->shortcode );
            merimag_get_shortcode_html( $shortcode, $atts );
        }
    }
}
class Merimag_Elementor_posts_list extends \Elementor\Widget_Base
{
    private $shortcode = "posts_list";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_posts_slider_thumbs extends \Elementor\Widget_Base
{
    private $shortcode = "posts_slider_thumbs";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_posts_slider extends \Elementor\Widget_Base
{
    private $shortcode = "posts_slider";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_products_carousel extends \Elementor\Widget_Base
{
    private $shortcode = "products_carousel";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_products_grid extends \Elementor\Widget_Base
{
    private $shortcode = "products_grid";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_products_list extends \Elementor\Widget_Base
{
    private $shortcode = "products_list";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_quotation extends \Elementor\Widget_Base
{
    private $shortcode = "quotation";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_review extends \Elementor\Widget_Base
{
    private $shortcode = "review";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_social_icons extends \Elementor\Widget_Base
{
    private $shortcode = "social_icons";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_spacing extends \Elementor\Widget_Base
{
    private $shortcode = "spacing";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_tabs extends \Elementor\Widget_Base
{
    private $shortcode = "tabs";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_category_carousel extends \Elementor\Widget_Base
{
    private $shortcode = "category_carousel";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_category_grid extends \Elementor\Widget_Base
{
    private $shortcode = "category_grid";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_product_category_carousel extends \Elementor\Widget_Base
{
    private $shortcode = "product_category_carousel";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}
class Merimag_Elementor_product_category_grid extends \Elementor\Widget_Base
{
    private $shortcode = "product_category_grid";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}

class Merimag_Elementor_video_playlist extends \Elementor\Widget_Base
{
    private $shortcode = "video_playlist";public function get_name()
    {
        return "merimag-" . $this->shortcode;
    }
    public function get_title()
    {
        return merimag_get_shortcode_title($this->shortcode);
    }
    public function get_icon()
    {
        return 'merimag-elementor-icon ' . merimag_get_shortcode_icon($this->shortcode);
    }
    public function get_categories()
    {
        return ["merimag"];
    }
    protected function register_controls()
    {
        $settings_function = "merimag_get_" . $this->shortcode . "_options";
        if (function_exists($settings_function))
        {
            $settings = $settings_function();
foreach( $settings as $setting_id => $setting ) {
    if( $setting['type'] != 'tab' ) {
        $settings = array(
            'general-settings' => array(
                'type' => 'tab',
                'title' => __('General', 'merimag'),
                'options' =>  $settings,
            ),
        );
        break;
    }
}   
            merimag_unyson_settings_to_elementor($this, $settings);
        }
    }
    protected function render()
    {
        $atts = $this->get_settings_for_display();
$element_id = $this->get_id();
$atts['block_id'] = 'merimag-element-' .$element_id;
$shortcode = str_replace( '_', '-', $this->shortcode );
merimag_get_shortcode_html( $shortcode, $atts );
    }
}