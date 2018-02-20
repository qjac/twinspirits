<?php
/**
 * Notice about removing the inline metadata.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.25.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin/migrations
 */

$notice = 'We removed inline metadata from our default templates and only use JSON-LD now.<br/>';
$notice .= 'If you are using a custom template you should consider removing the inline metadata there as well.<br/>';
$notice .= 'Those who prefer using both JSON-LD and inline metadata can add it in their own <a href="https://bootstrapped.ventures/wp-recipe-maker/create-your-own-template/" target="_blank">custom template</a>.';

self::$notices[] = $notice;
