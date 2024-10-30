<?php
/**
 * Plugin Name: LH Simple Facebook Pixel
 * Plugin URI: https://lhero.org/portfolio/lh-simple-facebook-pixel/
 * Description: Add facebook pixel simply
 * Version: 1.02
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com/
 * Tags: OGP, Open Graph, facebook, Meta, html, head, sharing, social media, tag
*/


if (!class_exists('LH_simple_facebook_pixel_plugin')) {
    
    


class LH_simple_facebook_pixel_plugin {
    
    
    var $filename;
    var $options;
    var $opt_name = 'lh_simple_facebook_pixel-options';
    var $hidden_field_name = 'lh_simple_facebook_pixel-submit_hidden';
    var $enable_pixel_field = 'lh_simple_facebook_pixel-enable_pixel';
    var $pixel_id_field = 'lh_simple_facebook_pixel-pixel_id';
    var $namespace = 'lh_simple_facebook_pixel';
    var $plugin_version = '1.02';
    
    
    
     /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @name    The    ID to register with WordPress
     * @file_path        The path to the actual file
     * @is_script        Optional argument for if the incoming file_path is a JavaScript source file.
     */
    private function load_file( $name, $file_path, $is_script = false, $deps = array(), $in_footer = true, $atts = array() ) {
        $url  = plugins_url( $file_path, __FILE__ );
        $file = plugin_dir_path( __FILE__ ) . $file_path;
        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $deps, $this->plugin_version, $in_footer ); 
                wp_enqueue_script( $name );
            }
            else {
                wp_register_style( $name, $url, $deps, $this->plugin_version );
                wp_enqueue_style( $name );
            } // end if
        } // end if
	  
	  if (isset($atts) and is_array($atts) and isset($is_script)){
		
		
  $atts = array_filter($atts);

if (!empty($atts)) {

  $this->script_atts[$name] = $atts; 
  
}

		  
	 add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	   

	   
if (isset($this->script_atts[$handle][0]) and !empty($this->script_atts[$handle][0])){
  
$atts = $this->script_atts[$handle];

$implode = implode(" ", $atts);
  
unset($this->script_atts[$handle]);

return str_replace( ' src', ' '.$implode.' src', $tag );

unset($atts);
usent($implode);

		 

	 } else {
	   
 return $tag;	   
	   
	   
	 }
	

}, 10, 2 );
 

	
	  
	}
		
    } // end load_file
    
private function getDomain() {
    $sURL    = site_url(); // WordPress function
    $asParts = parse_url( $sURL ); // PHP function

    if ( ! $asParts )
      wp_die( 'ERROR: Path corrupt for parsing.' ); // replace this with a better error result

    $sScheme = $asParts['scheme'];
    $nPort   = $asParts['port'];
    $sHost   = $asParts['host'];
    $nPort   = 80 == $nPort ? '' : $nPort;
    $nPort   = 'https' == $sScheme AND 443 == $nPort ? '' : $nPort;
    $sPort   = ! empty( $sPort ) ? ":$nPort" : '';
    $sReturn = $sHost . $sPort;

    return $sReturn;
}
    
    
    private function register_scripts_and_styles() {

if (!is_user_logged_in() and ($this->options[$this->enable_pixel_field] == 1)){
    
    if (is_array($this->options[ $this->pixel_id_field ])){ $pixel_var = implode (',', $this->options[ $this->pixel_id_field ]); } else { $pixel_var = $this->options[ $this->pixel_id_field ];  } 

// include the add-to-home-screen-js library
$this->load_file( $this->namespace.'-init_tracking-js', '/assets/lh-simple-facebook-pixel.js', true, array(), true, array('id="'.$this->namespace.'-init_tracking"', 'data-lh_simple_facebook_pixel-pixel_id="'.$pixel_var.'"' ));

}


}
    
public function general_init() {
  
          // Load JavaScript and stylesheets
        $this->register_scripts_and_styles();
  
  

}

public function wp_footer(){
    
    if (!is_user_logged_in() and ($this->options[$this->enable_pixel_field] == 1)){
        
  foreach( $this->options[ $this->pixel_id_field ] as $pixel_id ) {      
        
        
        ?>

<noscript><img height='1' width='1' style='display: none;' src='https://www.facebook.com/tr?id=<?php echo $pixel_id; ?>&ev=PageView&noscript=1&cd[domain]=<?php echo $this->getDomain(); ?>' alt='facebook_pixel'></noscript>

        
        <?php
    
}
 
    }
    
}



public function plugin_menu() {
add_options_page('LH Simple Facebook Pixel Options', 'Facebook Pixel', 'manage_options', $this->filename, array($this,"plugin_options"));

}

public function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
 // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'

if( isset($_POST[ $this->hidden_field_name ]) && $_POST[ $this->hidden_field_name ] == 'Y' ) {
    
    if (($_POST[$this->enable_pixel_field] == "0") || ($_POST[$this->enable_pixel_field] == "1")){
$options[$this->enable_pixel_field] = $_POST[ $this->enable_pixel_field ];
}

if (isset($_POST[ $this->pixel_id_field ]) and ($_POST[ $this->pixel_id_field ] != "")){
    
$options[$this->pixel_id_field] = explode (',', sanitize_text_field($_POST[ $this->pixel_id_field ]));
    
}


if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);


?>
<div class="updated"><p><strong><?php _e('Settings saved', $this->namespace ); ?></strong></p></div>
<?php

} 

}

    // Now display the settings editing screen

include ('partials/option-settings.php');
    

}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}


  
public function __construct() {

$this->filename = plugin_basename( __FILE__ );
$this->options = get_option($this->opt_name);


//register required styles and scripts
add_action('init', array($this,"general_init"));

//add the noscript elements to the footer
add_action( 'wp_footer', array($this,"wp_footer"));


//add the menu item
add_action('admin_menu', array($this,"plugin_menu"));

//add a link from the pugins listing]
add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);


}
    
    
    
}

$lh_simple_facebook_pixel_instance = new LH_simple_facebook_pixel_plugin();



}