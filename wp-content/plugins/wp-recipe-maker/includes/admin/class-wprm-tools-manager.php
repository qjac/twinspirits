<?php
/**
 * Responsible for handling the WPRM tools.
 *
 * @link       http://bootstrapped.ventures
 * @since      2.1.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin
 */

/**
 * Responsible for handling the WPRM tools.
 *
 * @since      2.1.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Tools_Manager {

	/**
	 * Only to be enabled when debugging the tools.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      boolean    $debugging    Wether or not we are debugging the tools.
	 */
	private static $debugging = false;

	/**
	 * Register actions and filters.
	 *
	 * @since    2.1.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 16 );
		add_action( 'wp_ajax_wprm_finding_parents', array( __CLASS__, 'ajax_finding_parents' ) );
	}

	/**
	 * Add the import submenu to the WPRM menu.
	 *
	 * @since    2.1.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( null, __( 'Finding Parents', 'wp-recipe-maker' ), __( 'Finding Parents', 'wp-recipe-maker' ), 'manage_options', 'wprm_finding_parents', array( __CLASS__, 'finding_parents' ) );
	}

	/**
	 * Get the template for the finding parents page.
	 *
	 * @since    2.1.0
	 */
	public static function finding_parents() {
		$args = array(
			'post_type' => array( 'post', 'page' ),
			'post_status' => array( 'publish', 'future', 'draft', 'private' ),
			'posts_per_page' => -1,
			'fields' => 'ids',
		);

		$posts = get_posts( $args );

		// Only when debugging.
		if ( self::$debugging ) {
			$result = self::find_parents( $posts ); // Input var okay.
			var_dump( $result );
			die();
		}

		// Handle via AJAX.
		wp_localize_script( 'wprm-admin', 'wprm_tools', array(
			'posts' => $posts,
		));

		require_once( WPRM_DIR . 'templates/admin/menu/tools/finding-parents.php' );
	}

	/**
	 * Parse ingredients submitted through AJAX.
	 *
	 * @since    2.1.0
	 */
	public static function ajax_finding_parents() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$posts = isset( $_POST['posts'] ) ? json_decode( wp_unslash( $_POST['posts'] ) ) : array(); // Input var okay.

			$posts_left = array();
			$posts_imported = array();

			if ( count( $posts ) > 0 ) {
				$posts_left = $posts;
				$posts_imported = array_map( 'intval', array_splice( $posts_left, 0, 10 ) );

				$result = self::find_parents( $posts_imported );

				if ( is_wp_error( $result ) ) {
					wp_send_json_error( array(
						'redirect' => add_query_arg( array( 'sub' => 'advanced' ), admin_url( 'admin.php?page=wprm_settings' ) ),
					) );
				}
			}

			wp_send_json_success( array(
				'posts_left' => $posts_left,
			) );
		}

		wp_die();
	}

	/**
	 * Find recipes in posts to link parents.
	 *
	 * @since	2.1.0
	 * @param	array $posts IDs of posts to search.
	 */
	public static function find_parents( $posts ) {
		foreach ( $posts as $post_id ) {
			$post = get_post( $post_id );
			$content = WPRM_Shortcode::replace_imported_shortcodes( $post->post_content );

			$update_content = array(
				'ID' => $post_id,
				'post_content' => $content,
			);
			wp_update_post( $update_content );
		}
	}
}

WPRM_Tools_Manager::init();
