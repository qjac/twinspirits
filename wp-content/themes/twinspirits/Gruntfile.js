module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // CONFIG ===================================/

    // Watch
    // https://github.com/gruntjs/grunt-contrib-watch

    watch: {
      options: {
        livereload: true
      },
      css: {
        files: ['**/*.scss', '!**/node_modules/**'],
        tasks: ['sass:dev', 'autoprefixer', 'combine_mq'],
        options: {
          spawn: false
        }
      }
    },

    // Spritesmith
    // https://github.com/Ensighten/grunt-spritesmith

    sprite: {
      all: {
        engine: "phantomjssmith",
        // Select all images to be sprited in various folders
        src: ['images/sprites/**/*.png'],
        // This will filter out @2x images ... for our retina spritesheet
        retinaSrcFilter: ['images/sprites/**/*@2x.png'],
        dest: 'images/sprite.png',
        retinaDest: 'images/sprite@2x.png',
        destCss: 'sass/_sprites.scss',
        cssTemplate: 'sass/spritesmith-retina-mixins.template.mustache'
      },
    },

		// Sass
    // https://github.com/sindresorhus/grunt-sass

		sass: {
      dev: {
        options: {
          sourceMap: true,
          outputStyle: 'expanded',
          sourceComments: true
        },
        files: {
          'twinspirits.css': 'sass/styles.scss'
        },
      },
		  dist: {
        options: {
          sourceMap: true,
          outputStyle: 'compressed'
        },
		    files: {
		      'twinspirits.css': 'sass/styles.scss'
		    },
		  }
		},
    
    // Autoprefixer
    // https://github.com/nDmitry/grunt-autoprefixer

    autoprefixer: {
      no_dest: {
        options: {
          browsers: ['last 2 versions']
        },
        src: 'twinspirits.css'
      }
    },

    // Combine Media Queries
    // https://github.com/frontendfriends/grunt-combine-mq

    combine_mq: {
      options: {
        // Task-specific options go here.
        log: false
      },
      your_target: {
        // Target-specific file lists and/or options go here.
        'output': ['../*.css']
      }
    },

    // Grunt Data URI
    // https://github.com/ahomu/grunt-data-uri

    dataUri: {
      dist: {
        // src file
        src: ['twinspirits.css'],
        // output dir
        dest: '',
        options: {
          // specified files are only encoding
          target: ['images/inline-image/*.*'],
          // adjust relative path?
          fixDirLevel: true,
          // img detecting base dir
          // baseDir: './'

          // Do not inline any images larger
          // than this size. 2048 is a size
          // recommended by Google's mod_pagespeed.
          maxBytes : 2048

        }
      }
    },

    // JS Hint
    // https://github.com/gruntjs/grunt-contrib-jshint

    jshint: {
      files: ['Gruntfile.js', 'js/*.js'],
      options: {
        // Don't throw errors on comma locations.
        // Set to be deprecated: http://jshint.com/docs/options/#laxcomma
        laxcomma:true
      }
    },
    
  });

  // DEPENDENT PLUGINS =========================/

  grunt.loadNpmTasks('grunt-spritesmith');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-combine-mq');
  grunt.loadNpmTasks('grunt-data-uri');
  grunt.loadNpmTasks('grunt-contrib-jshint');

  // TASKS =====================================/

  grunt.registerTask('default', [
    'sprite',
    'sass:dev',
    'autoprefixer',
    'combine_mq',
    'dataUri',
    'jshint'
  ]);

  grunt.registerTask('build', [
    'sprite',
    'sass:dist',
    'autoprefixer',
    'combine_mq',
    'dataUri',
    'jshint'
  ]);

};
