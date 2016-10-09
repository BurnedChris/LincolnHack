module.exports = function (grunt) {
    require('load-grunt-tasks')(grunt); // npm install --save-dev load-grunt-tasks

    grunt.initConfig({
        sass_globbing: {
            your_target: {
                files: {
                    'library/scss/build/components.scss': 'library/scss/components/**/*.scss',
                    'library/scss/build/settings.scss': 'library/scss/settings/**/*.scss',
                    'library/scss/build/tools.scss': 'library/scss/tools/**/*.scss',
                },
                options: {
                    useSingleQuotes: false,
                    signature: '// Created by Chris Burns.'
                }
            }
        },
        sass: {
            options: {
                sourceMap: true
            },
            dist: {
                files: {
                    'build/css/style.css': 'library/scss/build/style.scss'
                }
            },
            kss: {
                files: {
                    'library/builder/nest/kss-assets/kss.css': 'library/builder/nest/kss-assets/scss/kss.scss'
                }
            }
        },
        kss: {
            options: {
                css: '/css/style.css',
                builder: 'library/builder/nest',
                title: 'Lincoln Community Watcher',
                homepage: 'homepage.md',
                verbose: true,
                localURL: '//lincolnhack.local/index.html',
                liveURL: '//burnsy.github.io/LincolnHack/'
            },
            dist: {
                src: ['library/scss'],
                dest: 'build/library',
            }
        },
        watch: {
            sass: {
                files: "library/scss/**/*.scss",
                tasks: ['sass_globbing', 'sass', 'kss']
            },
        },

    });
    grunt.loadNpmTasks('grunt-kss');
    grunt.loadNpmTasks('grunt-sass-globbing');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['sass', 'watch']);
};