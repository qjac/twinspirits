<?php
class WP_Splash_Page_Admin {
	
	private $options;
	private $page_hook;
	
	public function __construct() {

		$this->upgrade();

		$this->config	= get_option( 'wp_splash_page_config' );
		$this->options	= get_option( 'wp_splash_page_options' );
		
		add_action( 'admin_menu', array( $this, 'add_to_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		if ( $this->config['show_info'] )
			add_action( 'admin_notices', array( $this, 'print_notice' ) );
			
		add_filter( 'plugin_action_links_' . plugin_basename( WP_SPLASH_PAGE_ROOT_PATH . 'init.php' ), array( $this, 'settings_link' ) );
		add_filter( 'gettext', array( $this, 'replace_thickbox_text' ), 1, 3 );
		
	}
	
	public function add_to_menu() {

		$this->page_hook	= add_options_page( 'WP Splash Page', 'WP Splash Page', 'manage_options', 'wp_splash_page_options.php', array( $this, 'do_page' ) );

	}
	
	public function register_settings() {
	
		register_setting( 'wpsp_options', 'wp_splash_page_options', array( $this, 'options_validate' ) );
		
		//Add General settings section **********************************************************************************************************************************
		
		add_settings_section( 'general_section', __( 'General Settings' ), array( $this, 'display_section' ), 'wp_splash_page_options.php' );
		
		$field_args = array(
			'type'      => 'checkbox',
			'id'		=> 'enable_splash_page',
			'name'      => 'enable_splash_page',
			'desc'      => __( 'Enable Splash Page', 'wp-splash-page-domain' ),
			'checked'	=> '1',
		);
		
		add_settings_field( 'enable_splash_page', 'WP Splash Page', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'general_section', $field_args );
		
		$field_args = array(
			'type'      => 'checkbox',
			'id'        => 'show_in_all',
			'name'      => 'show_in_all',
			'desc'      => __( 'Show on all page and posts', 'wp-splash-page-domain' ),
			'checked'	=> '1'
		);
		
		add_settings_field( 'show_in_all', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'general_section', $field_args );
		
		$field_args = array(
			'type'      => 'checkbox',
			'id'        => 'show_on_mobile',
			'name'      => 'show_on_mobile',
			'desc'      => __( 'Show on mobile browsers', 'wp-splash-page-domain' ),
			'checked'	=> '1'
		);
		
		add_settings_field( 'show_on_mobile_id', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'general_section', $field_args );
		
		$field_args = array(
			'type'     		=> 'number',
			'id'        	=> 'expiration_time',
			'name'      	=> 'expiration_time',
			'desc'      	=> __( 'Days until it shown again. If 0 is selected, Splash Page will be displayed once per session.', 'wp-splash-page-domain' ),
			'min'			=> 0,
			'max'			=> 9999,
			'size'			=> 4,
			'maxlength'		=> 4,
			'placeholder'	=> __( 'Days', 'wp-splash-page-domain' ),
		);
		
		add_settings_field( 'expiration_time', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'general_section', $field_args );
		
		$field_args = array(
			'type'     		=> 'select-template',
			'id'        	=> 'template',
			'name'      	=> 'template',
			'desc'      	=> '',
			'label_for' 	=> 'template',
			'class'     	=> 'wp-splash-page-templates',

		);
		
		add_settings_field( 'template', __( 'Template' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'general_section', $field_args );
		
		//Add Appearence settings section ************************************************************************************************************************
		
		add_settings_section( 'appearence_section', __( 'Appearence', 'wp-splash-page-domain' ), array( $this, 'display_section' ), 'wp_splash_page_options.php' );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'page_title',
			'name'      	=> 'page_title',
			'desc'      	=> '',
			'label_for' 	=> 'page_title',
			'size'			=> 55,
			'placeholder'	=> __( 'Title of the Page inside, of <Title> Tag', 'wp-splash-page-domain' ),
			'class'			=> 'regular-text'
		);
		
		add_settings_field( 'page_title', __( 'Page Title', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'title',
			'name'      	=> 'title',
			'desc'      	=> '',
			'label_for' 	=> 'title',
			'size'			=> 55,
			'placeholder'	=> __( 'Title Text', 'wp-splash-page-domain' ),
			'class'			=> 'regular-text'
		);
		
		add_settings_field( 'title', __( 'Title', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'color',
			'id'        	=> 'title_color',
			'name'      	=> 'title_color',
			'desc'      	=> '',
			'label_for' 	=> 'title_color',
			'size'			=> 55,
			'placeholder'	=> __( 'Title Color', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'title_color', __( 'Title Color', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'text-area',
			'id'        	=> 'text',
			'name'      	=> 'text',
			'desc'      	=> '',
			'label_for' 	=> 'text',
			'rows'			=> 5,
			'cols'			=> 115,
			'placeholder'	=> __( 'Text', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'text', __( 'Text', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'color',
			'id'        	=> 'text_color',
			'name'      	=> 'text_color',
			'desc'      	=> '',
			'label_for' 	=> 'text_color',
			'size'			=> 55,
			'placeholder'	=> __( 'Text Color', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'text_color', __( 'Text Color', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'continue_button_text',
			'name'      	=> 'continue_button_text',
			'desc'      	=> '',
			'label_for' 	=> 'continue_button_text',
			'size'			=> 55,
			'placeholder'	=> __( 'Continue Button Text', 'wp-splash-page-domain' ),
			'class'			=> 'regular-text'
		);
		
		add_settings_field( 'continue_button_text', __( 'Continue Button', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
	
		$field_args = array(
			'type'      	=> 'color',
			'id'        	=> 'continue_button_text_color',
			'name'      	=> 'continue_button_text_color',
			'desc'      	=> '',
			'label_for' 	=> 'continue_button_text_color',
		);
		
		add_settings_field( 'continue_button_text_color', __( 'Continue Button Text Color', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'color',
			'id'        	=> 'continue_button_bg_color',
			'name'      	=> 'continue_button_bg_color',
			'desc'      	=> '',
			'label_for' 	=> 'continue_button_bg_color',
		);
		
		add_settings_field( 'continue_button_bg_color', __( 'Continue Button Background', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'select-image',
			'id'        	=> 'image_url',
			'name'      	=> 'image_url',
			'desc'      	=> '',
			'label_for' 	=> 'image_url',
			'placeholder'	=> __( 'Image Background URL', 'wp-splash-page-domain' ),
			'button_text'	=> __( 'Select a Image', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'image_url', __( 'Page Background Image', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'checkbox',
			'id'        	=> 'repeat_image',
			'name'      	=> 'repeat_image',
			'desc'      	=> __( 'Repeat Image', 'wp-splash-page-domain' ),
			'checked'		=> 'repeat'

		);
		
		add_settings_field( 'repeat_image', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
	
		$field_args = array(
			'type'      	=> 'checkbox',
			'id'        	=> 'center_image',
			'name'      	=> 'center_image',
			'desc'      	=> __( 'Center Image', 'wp-splash-page-domain' ),
			'checked'		=> 'center top',

		);
		
		add_settings_field( 'center_image', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
	
		$field_args = array(
			'type'      	=> 'color',
			'id'        	=> 'background_color',
			'name'      	=> 'background_color',
			'desc'      	=> '',
			'label_for' 	=> 'background_color',
		);
		
		add_settings_field( 'background_color', __( 'Page Background Color', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'appearence_section', $field_args );
		
		//Add Video settings section *************************************************************************************************************************
		
		add_settings_section( 'video_section', __( 'Video', 'wp-splash-page-domain' ), array( $this, 'display_section' ), 'wp_splash_page_options.php' );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'youtube_id',
			'name'      	=> 'youtube_id',
			'desc'      	=> __( 'Video ID. In "v=Q8Tiz6INF7I" the id is "Q8Tiz6INF7I"', 'wp-splash-page-domain' ),
			'label_for' 	=> 'youtube_id',
			'size'			=> 13,
			'placeholder'	=> __( 'Video ID', 'wp-splash-page-domain' ),
			'class'			=> ''
		);
		
		add_settings_field( 'youtube_id', __( 'YouTube Video', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'video_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'checkbox',
			'id'        	=> 'video_autoplay',
			'name'      	=> 'video_autoplay',
			'desc'      	=> __( 'Autoplay', 'wp-splash-page-domain' ),
			'checked'		=> '1'
		);
		
		add_settings_field( 'video_autoplay', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'video_section', $field_args );
	
		$field_args = array(
			'type'     		=> 'number',
			'id'        	=> 'video_width',
			'name'      	=> 'video_width',
			'desc'      	=> '',
			'min'			=> 1,
			'max'			=> 9999,
			'size'			=> 4,
			'maxlength'		=> 4,
			'placeholder'	=> __( 'Width' ),
			'label_for'		=> 'video_width',
		);
		add_settings_field( 'video_width', __( 'Video Width', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'video_section', $field_args );
		
		$field_args = array(
			'type'     		=> 'number',
			'id'        	=> 'video_height',
			'name'      	=> 'video_height',
			'desc'      	=> '',
			'min'			=> 1,
			'max'			=> 9999,
			'size'			=> 4,
			'maxlength'		=> 4,
			'placeholder'	=> __( 'Height' ),
			'label_for'		=> 'video_height',
			'span'			=> __( 'Recommended Height for the chosen Width ( ', 'wp-splash-page-domain' ) . round( ($this->options['video_width'] / 16) * 9 ) . ' )',
			
		);
		add_settings_field( 'video_height', __( 'Video Height', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'video_section', $field_args );
		
		//Add Age Validation settings section *****************************************************************************************************************
		
		add_settings_section( 'age_validatin_section', __( 'Age Validation', 'wp-splash-page-domain' ), array( $this, 'display_section' ), 'wp_splash_page_options.php' );
		
		$field_args = array(
			'type'      	=> 'checkbox',
			'id'        	=> 'enable_age_confirmation',
			'name'      	=> 'enable_age_confirmation',
			'desc'      	=> __( 'Enable Age Confirmation', 'wp-splash-page-domain' ),
			'checked'		=> '1'
		);
		
		add_settings_field( 'enable_age_confirmation', __( 'Settings', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'age_validatin_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'number',
			'id'        	=> 'min_age',
			'name'      	=> 'min_age',
			'desc'      	=> __( 'Minimun Age Required', 'wp-splash-page-domain' ),
			'min'			=> 1,
			'max'			=> 999,
			'size'			=> 2,
			'maxlength'		=> 2,
			'placeholder'	=> __( 'Age', 'wp-splash-page-domain' ),
		
		);
		
		add_settings_field( 'min_age', '', array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'age_validatin_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'reject_text',
			'name'      	=> 'reject_text',
			'desc'      	=> '',
			'label_for' 	=> 'reject_text',
			'class'     	=> 'regular-text',
			'size'			=> 55,
			'placeholder'	=> __( 'Text displayed when a user is rejected', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'reject_text', __( 'Warning', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'age_validatin_section', $field_args );
		
		//Add Opt-In settings section *****************************************************************************************************************
		
		add_settings_section( 'opt_in_section', __( 'Opt-In', 'wp-splash-page-domain' ), array( $this, 'display_section' ), 'wp_splash_page_options.php' );
		
		$field_args = array(
			'type'      	=> 'checkbox',
			'id'        	=> 'enable_opt_in',
			'name'      	=> 'enable_opt_in',
			'desc'      	=> __( 'Enable Opt-In', 'wp-splash-page-domain' ),
			'checked'		=> '1'
		);
		
		add_settings_field( 'enable_opt_in', __( 'Settings', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'opt_in_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'opt_in_text',
			'name'      	=> 'opt_in_text',
			'desc'      	=> '',
			'label_for' 	=> 'opt_in_text',
			'class'     	=> 'regular-text',
			'size'			=> 55,
			'placeholder'	=>  __( 'Agreement/Disclaimer text', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'opt_in_text', __( 'Text', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'opt_in_section', $field_args );
		
		$field_args = array(
			'type'      	=> 'text',
			'id'        	=> 'opt_in_reject_text',
			'name'      	=> 'opt_in_reject_text',
			'desc'      	=> '',
			'label_for' 	=> 'opt_in_reject_text',
			'class'     	=> 'regular-text',
			'size'			=> 55,
			'placeholder'	=> __( 'Text displayed when a user doesn\'t accept the terms or conditions', 'wp-splash-page-domain' )
		);
		
		add_settings_field( 'opt_in_reject_text', __( 'Warning', 'wp-splash-page-domain' ), array( $this, 'display_setting' ), 'wp_splash_page_options.php', 'opt_in_section', $field_args );
	}
	
	public function do_page() {
	
		?>
		<div class="wrap">
		
			<div id="icon-tools" class="icon32"></div>
			
			<p><h2><?php echo esc_html( 'WP Splash Page' ); ?></h2></p>
			
			<form method="post" id="wp-splash-page-options-form" action="options.php">
			
				<?php
				settings_fields( 'wpsp_options' );
				do_settings_sections( 'wp_splash_page_options.php' );	
				?>
				
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
				</p>
				
				<a href="#" id="wp-splash-page-preview" class="button" title="<?php esc_attr_e( 'See a preview' ); ?>" ><?php esc_html_e( 'Preview', 'wp-splash-page-domain' ); ?></a>
				<a href="<?php echo esc_url( WP_SPLASH_PAGE_ROOT_URL . 'documentation/' ); ?>" class="button" target="_blank" title="<?php esc_attr_e( 'Read the documentation.' ); ?>" ><?php esc_html_e( 'Documentation', 'wp-splash-page-domain' ); ?></a>
				
			</form>
			
		</div>
		<?php
			
	}
	
	public function display_section( ){
	
		//echo '<span class="description">Description in each section</span>';
		
	}
	
	public function display_setting( $args ){
	
		extract( $args );
		$option_name	= 'wp_splash_page_options';
		$options		= get_option( $option_name );
		$options[$id]	= stripslashes( $options[$id] );
		$options[$id]	= esc_attr( $options[$id] );
		$html			= '';
		
		switch ( $type ) {
		
			case 'text':

				$html	.= '<input class="' .  esc_attr( $class ) . '" type="text" id="' . esc_attr ( $id ) . '" name="' . esc_attr( $option_name ) . '[' . $id . ']" value="' . esc_attr( $options[ $id ] ) . '" size="' . esc_attr( $size ) . '" placeholder="' . esc_attr( $placeholder ) . '"/>';
				$html	.= ( $desc != '' ) ? '<label for="' . esc_attr( $id ) . '" class="description">' .esc_html( $desc ) . '</label>' : '';
				echo $html;
				
				break;
				
			case 'checkbox':

				$html	.= '<input type="checkbox"  id="' . esc_attr( $id ) . '" name="' . esc_attr( $option_name ) . '[' . $id . ']" value="1" ' . checked( $checked , $options[$id], false ) . ' />';
				$html	.= ( $desc != '' ) ? '<label for="' . esc_attr( $id ) . '" >' . esc_html( $desc ) . '</label>' : ''; 
				echo $html;
				
				break;
			
			case 'number':
				$span	= isset( $span ) ? $span: '';
			
				$html	.= '<input type="number" id="' . esc_attr( $id ) . '" name="' . esc_attr( $option_name ) . '[' . $id . ']" value="' . esc_attr( $options[ $id ] ) . '" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" size="' . esc_attr( $size ) . '" placeholder="' . esc_attr( $placeholder ) . '" />';
				$html	.= ( $desc != '' ) ? '<label for="' . esc_attr( $id ) . '" >' . esc_html( $desc ) . '</label>' : '';
				$html	.= ( $span != '' ) ? '<span class="description" id="span-number">' . esc_html( $span ) . '</span>': '';
				echo $html;
				
				break;
				
			case 'select-template':
			
				$directory 					= WP_SPLASH_PAGE_ROOT_PATH . 'templates';
				$directories 				= glob( $directory . '/*' , GLOB_ONLYDIR );
				$templates					= array();
		
				foreach ( $directories as $dir ){
					$templates[] = basename( $dir );
				}
				
				$html	.= '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $option_name ) . '[' . $id . ']" >';
				foreach ( $templates as $template_name ){
					$html	.= '<option value="' . esc_attr( $template_name ) . '" ' .  selected( $options[ $id ], $template_name, false ) . '>' . esc_html( $template_name )  . '</option>';
				}
				$html	.= '</select>';
				
				echo $html;
				
				break;
				
			case 'color':
		
				if ( version_compare( get_bloginfo( 'version' ), '3.5', '>=' ) ) {
				
					$html	.= '<input type="text" id="' . esc_attr( $id ) . '" size="7" min="7" max="7" maxlength="7" name="' . esc_attr( $option_name ) . '[' . $id . ']" value="#' . esc_attr( $options[ $id ] ) . '" class="wpsp-input-color"/>';
				
				} else {
					
					$html	.= '<input size="8" min="7" max="7" maxlength="7" type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $option_name ) . '[' . $id . ']" value="#' . esc_attr( $options[ $id ] ) . '" class="wpsp-input-color"/>';
					$html	.= '<div class="wpsp-colorpicker" id="wpsp-colorpicker-' . esc_attr( $id ) .'" style="display: none;"></div>';
					
				}
				echo $html;
				break;
			
			case 'text-area':
			
				$html	.= '<textarea rows="' . esc_attr( $rows ) . '" cols="' . esc_attr( $cols ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $option_name ) . '[' . $id . ']" placeholder="' . esc_attr( $placeholder ) . '">' . esc_html( $options[ $id ] ) . '</textarea>';
				echo $html;
				
				break;
				
			case 'select-image':
			
				$html	.= '<input class="button" type="button" id="wp-select-image-button" value="' . esc_attr( $button_text ). '"/>';
				$html	.= '<input type="text" id="' . esc_attr( $id ). '" name="' . esc_attr( $option_name ) . '[' . $id . ']" size="75" value="' . esc_attr( $options[ $id ] ) . '" placeholder="' . esc_attr( $placeholder ) . '"/>';
				$html	.= '<input class="button" type="button" id="remove_image" size="80" value="X"/>';
				echo $html;
				
				break;
				
		}
		
	}
	
	public function enqueue_scripts( $hook ) {
		
		if( $hook != $this->page_hook ) 
			return;
 		
		if ( version_compare( get_bloginfo( 'version' ), '3.5', '>=' ) ) {

			wp_enqueue_script( 'wp-splash-page-color-picker', WP_SPLASH_PAGE_ROOT_URL . 'js/wpsp-color-picker.js', array( 'wp-color-picker' ), false, true );
			wp_enqueue_script( 'wp-splash-page-new-media-upload', WP_SPLASH_PAGE_ROOT_URL . 'js/wpsp-new-media-upload.js' );
			wp_enqueue_media();
			
			wp_enqueue_style( 'wp-color-picker' );
			
		} else {

			wp_enqueue_script( 'wp-splash-page-color-picker', WP_SPLASH_PAGE_ROOT_URL . 'js/wpsp-farbtastic.js', array( 'farbtastic' ), false, true );
			wp_enqueue_script( 'wp-splash-page-media-upload', WP_SPLASH_PAGE_ROOT_URL . 'js/wpsp-media-upload.js', array( 'media-upload', 'thickbox', 'jquery' ), false, true );
			
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_style( 'thickbox' );
			
		}
		
		wp_register_script( 'wp-splash-page-admin-javascript', WP_SPLASH_PAGE_ROOT_URL . 'js/admin.js', array( 'jquery' ), '2.0' );	
		wp_enqueue_script( 'wp-splash-page-admin-javascript' );
		
		wp_register_style( 'wp-splash-page-admin-style', WP_SPLASH_PAGE_ROOT_URL . 'inc/admin/style.css' );	
		wp_enqueue_style( 'wp-splash-page-admin-style' );
		
		wp_localize_script( 
			'wp-splash-page-admin-javascript', 
			'wp_splash_page_data', 
			array( 
				'ajaxurl'			=> admin_url( 'admin-ajax.php' ),
				'previewSecurity'	=> wp_create_nonce( WP_SPLASH_PAGE_PREVIEW_NONCE )
			) 
		);
			
	}
	
	public function replace_thickbox_text( $translated_text, $text, $domain ) { 
	
		if ( 'Insert into Post' == $text ) { 
		
			$referer = strpos( wp_get_referer(), 'wp-splash-page-settings' );
			
			if ( $referer != '' ) 
				return __( 'Set as Background', 'wp-splash-page-domain' );

		} 
		
		return $translated_text;
	
	}
	
	public function print_notice() {

		$message	= sprintf( __( 'Please <a href="%s">update your settings</a> to complete installation of WP Splash Page.', 'wp-splash-page-domain' ), admin_url( 'options-general.php?page=wp_splash_page_options' ) );
		
		echo '<div class="updated"><p>' . $message . '</p></div>';
		
		$this->config['show_info']	= false;
		update_option( 'wp_splash_page_config', $this->config );
		
	}

	public function settings_link( $links ) {

		$links[] = '<a href="options-general.php?page=wp_splash_page_options">' . __( 'Settings' ) . '</a>'; 
		return $links;
  
	}
	
	public function options_validate( $input ) {
		
		$input['enable_splash_page']			= ( 1 == $input['enable_splash_page'] ) ? 1 : 0;
		$input['show_in_all']					= ( 1 == $input['show_in_all'] ) ? 1 : 0;
		$input['show_on_mobile']				= ( 1 == $input['show_on_mobile'] ) ? 1 : 0 ;
		$input['enable_age_confirmation']		= ( 1 == $input['enable_age_confirmation'] ) ? 1 : 0;
		$input['video_autoplay']				= ( 1 == $input['video_autoplay'] ) ? 1 : 0;
		$input['enable_opt_in']					= ( 1 == $input['enable_opt_in'] ) ? 1 : 0;
		$input['reject_text']					= strip_tags( $input['reject_text'] );
		$input['opt_in_reject_text']			= strip_tags( $input['opt_in_reject_text'] );
		$input['template']						= strip_tags( $input['template'] );
		$input['title']							= balanceTags( $input['title'] );
		$input['text']							= balanceTags( $input['text'] );
		$input['opt_in_text']					= balanceTags( $input['opt_in_text'] );
		$input['page_title']					= ( !empty ( $input['page_title'] ) ) ? strip_tags( $input['page_title'] ) : get_bloginfo('name');
		$input['continue_button_text']			= ( !empty( $input['continue_button_text'] ) ) ? strip_tags( $input['continue_button_text'] ) : strip_tags( 'Continue to Web Site' );
		$input['background_color']				= ( !empty( $input['background_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '', $input['background_color'] ) ) ) ? str_replace( '#', '', $input['background_color'] ): $this->options['background_color'];
		$input['title_color']					= ( !empty( $input['title_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '', $input['title_color'] ) ) ) ? str_replace( '#', '', $input['title_color'] ): $this->options['title_color'];
		$input['text_color']					= ( !empty( $input['text_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '', $input['text_color'] ) ) ) ? str_replace( '#', '', $input['text_color'] ): $this->options['text_color'];
		$input['continue_button_bg_color']		= ( !empty( $input['continue_button_bg_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $input['continue_button_bg_color'] ) ) ) ? str_replace( '#', '', $input['continue_button_bg_color'] ): $this->options['continue_button_bg_color'];
		$input['continue_button_text_color']	= ( !empty( $input['continue_button_text_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $input['continue_button_text_color'] ) ) ) ? str_replace( '#', '', $input['continue_button_text_color'] ): $this->options['continue_button_text_color'];
		$input['image_url']						= esc_url_raw( $input['image_url'] );
		$input['youtube_id']					= strip_tags( $input['youtube_id'] );	
		$input['repeat_image']					= ( 1 == $input['repeat_image'] ) ? 'repeat' : 'no-repeat';
		$input['center_image']					= ( 1 == $input['center_image'] ) ? 'center top' : 'left top';
		$input['expiration_time']				= ( ( $input['expiration_time'] <= 9999 && $input['expiration_time'] >= 0 && ctype_digit( $input['expiration_time'] ) ) )? intval( $input['expiration_time'] ) : 30;
		$input['min_age']						= ( ( $input['min_age'] <= 999 && $input['min_age'] >= 1 && ctype_digit( $input['min_age'] ) ) ) ? intval( $input['min_age'] ) : 18;
		$input['video_width']					= ( ( $input['video_width'] >= 1 && $input['video_width'] <= 9999 && ctype_digit( $input['video_width'] ) ) ) ? intval( $input['video_width'] ): 480;
		$input['video_height']					= ( ( $input['video_height'] >= 1 && $input['video_height'] <= 9999 && ctype_digit( $input['video_height'] ) ) ) ? intval( $input['video_height'] ) : 360;
		
		$config					= get_option( 'wp_splash_page_config' );
		$config['cookie_name']	= 'wpsp_cookie_' . wp_create_nonce( 'wpsp_cookie_name' . current_time( 'timestamp' ) );
		update_option( 'wp_splash_page_config', $config );
		
		update_option( 'wp_splash_page_options_preview', $input );
		
		return $input;
		
	}
	
	function upgrade() {
	
		$config				= get_option( 'wp_splash_page_config' );
		$current_version	= $config['version'];

		if ( version_compare( $current_version, WP_SPLASH_PAGE_VERSION, '==' ) )
			return false;
			
		if ( version_compare( $current_version, '1.1', '<' ) ) {
			
			$options	= get_option( 'wp_splash_page_options' );
			$options['enable_opt_in']		= false;
			$options['opt_in_text']			= strip_tags( __( 'I agree with the terms and conditions.', 'wp-splash-page-domain' ) );
			$options['opt_in_reject_text']	= strip_tags( __( 'You aren\'t agree with conditions.', 'wp-splash-page-domain' ) );

			update_option( 'wp_splash_page_options', $options );
		
		} // END < 1.1
		
		$config['version']	= WP_SPLASH_PAGE_VERSION;
		update_option( 'wp_splash_page_config', $config );
		
	}

}

$wpsp_admin	= new WP_Splash_Page_Admin();	
?>