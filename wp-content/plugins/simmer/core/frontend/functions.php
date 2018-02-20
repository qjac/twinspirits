<?php
/**
 * Define the front-end supporting functions
 *
 * @since 1.2.0
 *
 * @package Simmer/Frontend
 */

/**
 * Retrieve a template part from the appropriate Simmer directory.
 *
 * @since 1.0.0
 *
 * @param  string $slug
 * @param  string $name Optional. Default null.
 * @param  bool   $load Optional. Default true.
 * @return string
 */
function simmer_get_template_part( $slug, $name = null, $load = true ) {

	$template_loader = new Simmer_Template_Loader();

	return $template_loader->get_template_part( $slug, $name, $load );
}
