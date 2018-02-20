/*global node:true */

module.exports = function( grunt ) {
	'use strict';

	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.initConfig({

		makepot: {
			plugin: {
				options: {
					mainFile: 'simmer.php',
					potHeaders: {
						poedit: true,
						'report-msgid-bugs-to': 'http://wordpress.org/support/plugin/simmer',
						'last-translator': 'Simmer for Recipes <hi@simmerwp.com>',
						'language-team': 'Simmer for Recipes <hi@simmerwp.com>'
					},
					type: 'wp-plugin',
					updateTimestamp: false
				}
			}
		},

	});

};
