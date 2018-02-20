<?php
/**
 * Responsible for loading the WPRM assets.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.22.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Responsible for loading the WPRM assets.
 *
 * @since      1.22.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Assets {

	/**
	 * Register actions and filters.
	 *
	 * @since    1.22.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 1 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin' ), 1 );
		add_action( 'amp_post_template_css', array( __CLASS__, 'amp_style' ) );

		add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    1.22.0
	 */
	public static function enqueue() {
		wp_enqueue_style( 'wprm-public', WPRM_URL . 'dist/public.css', array(), WPRM_VERSION, 'all' );
		wp_enqueue_script( 'wprm-public', WPRM_URL . 'dist/public.js', array( 'jquery' ), WPRM_VERSION, true );

		wp_localize_script( 'wprm-public', 'wprm_public', array(
			'settings' => array(
				'features_comment_ratings' => WPRM_Settings::get( 'features_comment_ratings' ),
			),
			'home_url' => home_url( '/' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'wprm' ),
		));
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    2.0.0
	 */
	public static function enqueue_admin() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wprm-admin', WPRM_URL . 'dist/admin.css', array(), WPRM_VERSION, 'all' );

		wp_enqueue_script( 'wprm-admin', WPRM_URL . 'dist/admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), WPRM_VERSION, true );

		wp_localize_script( 'wprm-admin', 'wprm_temp_admin', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'wprm' ),
			'addons' => array(
				'premium' => WPRM_Addons::is_active( 'premium' ),
			),
			'settings' => array(
				'features_comment_ratings' => WPRM_Settings::get( 'features_comment_ratings' ),
			),
			'manage' => array(
				'tooltip' => array(
					'recipes' => apply_filters( 'wprm_manage_datatable_tooltip', '<div class="tooltip-header">&nbsp;</div><a href="#" class="wprm-manage-recipes-actions-edit">Edit Recipe</a><a href="#" class="wprm-manage-recipes-actions-delete">Delete Recipe</a>', 'recipes' ),
					'ingredients' => apply_filters( 'wprm_manage_datatable_tooltip', '<div class="tooltip-header">&nbsp;</div><a href="#" class="wprm-manage-ingredients-actions-rename">Rename Ingredient</a><a href="#" class="wprm-manage-ingredients-actions-link">Edit Ingredient Link</a><a href="#" class="wprm-manage-ingredients-actions-merge">Merge into Another Ingredient</a><a href="#" class="wprm-manage-ingredients-actions-delete">Delete Ingredient</a>', 'ingredients' ),
					'taxonomies' => apply_filters( 'wprm_manage_datatable_tooltip', '<div class="tooltip-header">&nbsp;</div><a href="#" class="wprm-manage-taxonomies-actions-rename">Rename Term</a><a href="#" class="wprm-manage-taxonomies-actions-merge">Merge into Another Term</a><a href="#" class="wprm-manage-taxonomies-actions-delete">Delete Term</a>', 'taxonomies' ),
				),
			),
			'modal' => array(
				'text' => array(
					'modal_close_confirm' => __( 'Are you sure? You will lose any unsaved changes.', 'wp-recipe-maker' ),
					'action_button_insert' => __( 'Insert & Close', 'wp-recipe-maker' ),
					'action_button_update' => __( 'Update & Close', 'wp-recipe-maker' ),
					'media_title' => __( 'Select or Upload Image', 'wp-recipe-maker' ),
					'media_button' => __( 'Use Image', 'wp-recipe-maker' ),
					'shortcode_remove' => __( 'Are you sure you want to remove this recipe?', 'wp-recipe-maker' ),
					'import_text_reset' => __( 'Are you sure you want to start over with importing from text?', 'wp-recipe-maker' ),
					'edit_recipe' => __( 'Edit Recipe', 'wp-recipe-maker' ),
					'first_recipe_on_page' => __( 'First recipe on page', 'wp-recipe-maker' ),
					'medium_editor_placeholder' => __( 'Type here (select text for advanced styling options)', 'wp-recipe-maker' ),
				),
			),
		));
	}

	/**
	 * Enqueue template style on AMP pages.
	 *
	 * @since    2.1.0
	 */
	public static function amp_style() {
		// Get AMP specific CSS.
		ob_start();
		include( WPRM_DIR . 'dist/amp.css' );
		$css = ob_get_contents();
		ob_end_clean();

		// Get custom recipe styling.
		$css .= ' ' . self::get_custom_css( 'recipe' );

		// Get rid of !important flags.
		$css = str_ireplace( ' !important', '', $css );
		$css = str_ireplace( '!important', '', $css );

		echo $css;
	}

	/**
	 * Output custom CSS from the options.
	 *
	 * @since    1.10.0
	 * @param	 mixed $type Type of recipe to output the custom CSS for.
	 */
	public static function custom_css( $type = 'recipe' ) {
		if ( WPRM_Settings::get( 'features_custom_style' ) ) {
			$selector = 'print' === $type ? ' html body.wprm-print' : ' html body .wprm-recipe-container';

			$output = '<style type="text/css">' . self::get_custom_css( $type ) .  '</style>';

			echo $output;
		}
	}

	/**
	 * Get custom CSS from the options.
	 *
	 * @since    2.1.0
	 * @param	 mixed $type Type of recipe to get the custom CSS for.
	 */
	public static function get_custom_css( $type = 'recipe' ) {
		if ( ! WPRM_Settings::get( 'features_custom_style' ) ) {
			return '';
		}

		$selector = 'print' === $type ? ' html body.wprm-print' : ' html body .wprm-recipe-container';

		$output = '';

		// Recipe Snippets appearance.
		if ( WPRM_Settings::get( 'recipe_snippets_automatically_add' ) ) {
			$output .= ' .wprm-automatic-recipe-snippets a.wprm-jump-to-recipe-shortcode, .wprm-automatic-recipe-snippets a.wprm-print-recipe-shortcode {';
			$output .= ' background-color: ' . WPRM_Settings::get( 'recipe_snippets_background_color' ) . ';';
			$output .= ' color: ' . WPRM_Settings::get( 'recipe_snippets_text_color' ) . ';';
			$output .= '}';
		}

		// Template Appearance.
		if ( WPRM_Settings::get( 'template_font_size' ) ) {
			$output .= $selector . ' .wprm-recipe { font-size: ' . WPRM_Settings::get( 'template_font_size' ) . 'px; }';
		}
		if ( WPRM_Settings::get( 'template_font_regular' ) ) {
			$output .= $selector . ' .wprm-recipe { font-family: ' . WPRM_Settings::get( 'template_font_regular' ) . '; }';
			$output .= $selector . ' .wprm-recipe p { font-family: ' . WPRM_Settings::get( 'template_font_regular' ) . '; }';
			$output .= $selector . ' .wprm-recipe li { font-family: ' . WPRM_Settings::get( 'template_font_regular' ) . '; }';
		}
		if ( WPRM_Settings::get( 'template_font_header' ) ) {
			$output .= $selector . ' .wprm-recipe .wprm-recipe-name { font-family: ' . WPRM_Settings::get( 'template_font_header' ) . '; }';
			$output .= $selector . ' .wprm-recipe .wprm-recipe-header { font-family: ' . WPRM_Settings::get( 'template_font_header' ) . '; }';
		}

		$output .= $selector . ' { color: ' . WPRM_Settings::get( 'template_color_text' ) . '; }';
		$output .= $selector . ' a.wprm-recipe-print { color: ' . WPRM_Settings::get( 'template_color_text' ) . '; }';
		$output .= $selector . ' a.wprm-recipe-print:hover { color: ' . WPRM_Settings::get( 'template_color_text' ) . '; }';
		$output .= $selector . ' .wprm-recipe { background-color: ' . WPRM_Settings::get( 'template_color_background' ) . '; }';
		$output .= $selector . ' .wprm-recipe { border-color: ' . WPRM_Settings::get( 'template_color_border' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-border { border-color: ' . WPRM_Settings::get( 'template_color_border' ) . '; }';
		$output .= $selector . ' a { color: ' . WPRM_Settings::get( 'template_color_link' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-header { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' h1 { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' h2 { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' h3 { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' h4 { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' h5 { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' h6 { color: ' . WPRM_Settings::get( 'template_color_header' ) . '; }';
		$output .= $selector . ' svg path { fill: ' . WPRM_Settings::get( 'template_color_icon' ) . '; }';
		$output .= $selector . ' svg rect { fill: ' . WPRM_Settings::get( 'template_color_icon' ) . '; }';
		$output .= $selector . ' svg polygon { stroke: ' . WPRM_Settings::get( 'template_color_icon' ) . '; }';
		$output .= $selector . ' .wprm-rating-star-full svg polygon { fill: ' . WPRM_Settings::get( 'template_color_icon' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent { background-color: ' . WPRM_Settings::get( 'template_color_accent' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent { color: ' . WPRM_Settings::get( 'template_color_accent_text' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent a.wprm-recipe-print { color: ' . WPRM_Settings::get( 'template_color_accent_text' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent a.wprm-recipe-print:hover { color: ' . WPRM_Settings::get( 'template_color_accent_text' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent2 { background-color: ' . WPRM_Settings::get( 'template_color_accent2' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent2 { color: ' . WPRM_Settings::get( 'template_color_accent2_text' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent2 a.wprm-recipe-print { color: ' . WPRM_Settings::get( 'template_color_accent2_text' ) . '; }';
		$output .= $selector . ' .wprm-recipe .wprm-color-accent2 a.wprm-recipe-print:hover { color: ' . WPRM_Settings::get( 'template_color_accent2_text' ) . '; }';

		// Instruction image alignment.
		$output .= $selector . ' .wprm-recipe-instruction-image { text-align: ' . WPRM_Settings::get( 'template_instruction_image_alignment' ) . '; }';

		// List style.
		if ( 'checkbox' === WPRM_Settings::get( 'template_ingredient_list_style' ) ) {
			$output .= $selector . ' li.wprm-recipe-ingredient { list-style-type: none; }';
		} else {
			$output .= $selector . ' li.wprm-recipe-ingredient { list-style-type: ' . WPRM_Settings::get( 'template_ingredient_list_style' ) . '; }';
		}
		if ( 'checkbox' === WPRM_Settings::get( 'template_instruction_list_style' ) ) {
			$output .= $selector . ' li.wprm-recipe-instruction { list-style-type: none; }';
		} else {
			$output .= $selector . ' li.wprm-recipe-instruction { list-style-type: ' . WPRM_Settings::get( 'template_instruction_list_style' ) . '; }';
		}

		// Comment ratings.
		$output .= ' .wprm-comment-rating svg path, .comment-form-wprm-rating svg path { fill: ' . WPRM_Settings::get( 'template_color_comment_rating' ) . '; }';
		$output .= ' .wprm-comment-rating svg polygon, .comment-form-wprm-rating svg polygon { stroke: ' . WPRM_Settings::get( 'template_color_comment_rating' ) . '; }';

		// Allow add-ons to hook in.
		$output = apply_filters( 'wprm_custom_css', $output, $type, $selector );

		// Custom recipe CSS.
		if ( 'print' !== $type ) {
			$output .= WPRM_Settings::get( 'recipe_css' );
		}

		return $output;
	}
}

WPRM_Assets::init();
