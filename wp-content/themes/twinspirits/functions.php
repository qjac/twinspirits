<?php

// Register parent theme CSS.
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twinspirits_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
  if ( 'post-thumbnail' === $size ) {
    is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
    ! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1400px) 88vw, 1400px';
  }
  return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twinspirits_post_thumbnail_sizes_attr', 20 , 3 );

/**
 * Add a body class if there's a Featured Image.
 */
add_action('body_class', 'twinspirits_if_featured_image_class' );
function twinspirits_if_featured_image_class($classes) {
  if (has_post_thumbnail()) {
    array_push($classes, 'has-featured-image');
  }
  return $classes;
}

/**
 * Don't show the default Author search block with the user listing.
 */
function twinspirits_remove_search(){
  global $simple_user_listing;
  remove_action( 'simple_user_listing_before_loop', array( $simple_user_listing, 'add_search' ) );
}
add_action( 'wp_head', 'twinspirits_remove_search' );

/**
 * Require cropping of the featured (header) image.
 */
// @TODO - this doesn't actually work yet
$header_img = array(
  'default-image'          => '',
  'width'                  => 1400,
  'height'                 => 475,
  'flex-height'            => true,
  'flex-width'             => true,
  'uploads'                => true
);
add_theme_support( 'custom-header', $header_img );
