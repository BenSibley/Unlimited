module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            basic_and_extras: {
                files: {
                    'js/build/production.js': ['js/fitvids.js', 'js/functions.js'],
                    'js/build/customizer.js': ['js/customizer.js', 'js/multiple-select.js']
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    'js/build/production.min.js' : 'js/build/production.js',
                    'js/build/customizer.min.js' : 'js/build/customizer.js',
                    'js/build/postMessage.min.js' : 'js/postMessage.js',
                    'js/build/html5shiv.min.js' : 'js/html5shiv.js',
                    'js/build/respond.min.js' : 'js/respond.js'
                }
            }
        },
        watch: {
            scripts: {
                files: ['js/*.js'],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: ['sass/*.scss'],
                tasks: ['sass', 'autoprefixer', 'cssjanus', 'cssmin'],
                options: {
                    livereload: true,
                    spawn: false
                }
            }
        },
        sass: {
            dist: {
                files: {
                    'style.css': 'sass/style.scss',
                    'styles/admin.css': 'sass/admin.scss',
                    'styles/custom-editor-style.css': 'sass/custom-editor-style.scss',
                    'styles/customizer.css': 'sass/customizer.scss'
                }
            }
        },
        autoprefixer: {
            dist: {
                options: {
                    browsers: ['last 1 version', '> 1%', 'ie 8']
                },
                files: {
                    'style.css': 'style.css',
                    'styles/admin.css': 'styles/admin.css',
                    'styles/custom-editor-style.css': 'styles/custom-editor-style.css',
                    'styles/customizer.css': 'styles/customizer.css'
                }
            }
        },
        cssjanus: {
            dev: {
                options: {
                    swapLtrRtlInUrl: false // replace 'ltr' with 'rtl'
                },
                src: ['style.css'],
                dest: 'rtl.css'
            }
        },
        cssmin: {
            combine: {
                files: {
                    'style.min.css': ['style.css'],
                    'styles/admin.min.css': ['styles/admin.css'],
                    'styles/custom-editor-style.min.css': ['styles/custom-editor-style.css'],
                    'styles/customizer.min.css': ['styles/customizer.css'],
                    'rtl.min.css': ['rtl.css']
                }
            }
        },
        compress: {
            main: {
                options: {
                    archive: '/Users/bensibley/Desktop/ignite.zip'
                },
                files: [
                    {
                        src: ['**', '!node_modules/**','!sass/**', '!gruntfile.js', '!package.json', '!style-prefixed.css','!/.git/','!/.idea/','!/.sass-cache/','!**.DS_Store'],
                        filter: 'isFile'
                    }
                ]
            }
        },
        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    exclude: ['library/.*/.*'],
                    potFilename: 'unlimited.pot',
                    type: 'wp-theme',
                    processPot: function( pot ) {
                        var translation,
                            excluded_meta = [
                                'Theme Name of the plugin/theme',
                                'Theme URI of the plugin/theme',
                                'Author of the plugin/theme',
                                'Author URI of the plugin/theme'
                            ];

                        for ( translation in pot.translations[''] ) {
                            if ( 'undefined' !== typeof pot.translations[''][ translation ].comments.extracted ) {
                                if ( excluded_meta.indexOf( pot.translations[''][ translation ].comments.extracted ) >= 0 ) {
                                    console.log( 'Excluded meta: ' + pot.translations[''][ translation ].comments.extracted );
                                    delete pot.translations[''][ translation ];
                                }
                            }
                        }

                        return pot;
                    }
                }
            }
        },
        phpcs: {
            application: {
                dir: ['*.php']
            },
            options: {
                tabWidth: 4
            }
        },
        phpunit: {
            classes: {
                dir: 'tests/php/'
            },
            options: {
                bin: 'vendor/bin/phpunit',
                bootstrap: 'tests/php/phpunit.php',
                colors: true
            }
        },
        excludeFiles: '--exclude "*.gitignore" --exclude ".sass-cache/" --exclude "*.DS_Store" --exclude ".git/" --exclude ".idea/" --exclude "gruntfile.js" --exclude "node_modules/" --exclude "package.json" --exclude "sass/" --exclude "style.css.map" --exclude "styles/admin.css.map" --exclude "styles/custom-editor-style.css.map" --exclude "styles/customizer.css.map"',
        shell: {
            zip: {
                command: [
                    // delete existing copies (if they exist)
                    'rm -R "/Users/bensibley/Dropbox/Compete Themes/Distribution/unlimited" || true',
                    'rm -R "/Users/bensibley/Dropbox/Compete Themes/Distribution/unlimited.zip" || true',
                    // copy theme folder without any project/meta files
                    'rsync -r "/Users/bensibley/Sites/unlimited/wp-content/themes/unlimited" "/Users/bensibley/Dropbox/Compete Themes/Distribution/" <%= excludeFiles %>',
                    // open dist folder
                    'cd "/Users/bensibley/Dropbox/Compete Themes/Distribution/"',
                    // zip the unlimited folder
                    'zip -r unlimited.zip unlimited'
                ].join('&&')
            }
        }
    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-cssjanus');
    grunt.loadNpmTasks('grunt-shell');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify', 'watch', 'sass', 'autoprefixer', 'cssmin', 'compress', 'makepot', 'phpcs', 'phpunit', 'cssjanus', 'shell']);

};