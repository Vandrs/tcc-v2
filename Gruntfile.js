module.exports = function(grunt){
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        options: {
            banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
        },
        uglify:{
            jsmin: {
                files: [{
                    expand: true,
                    cwd: 'public/assets/js/app/src',
                    src: '*.js',
                    dest: 'public/assets/js/app/dist',
                    ext: '.min.js'    
                }]
            }
        },
        cssmin:{
            target: {
                files: [{
                    expand: true,
                    cwd: 'public/assets/css/app/src',
                    src: '*.css',
                    dest: 'public/assets/css/app/dist',
                    ext: '.min.css'
                }]
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', ['uglify','cssmin']);
};
