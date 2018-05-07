module.exports = function() {
    var dist = 'public/dist';
    var src = 'public';
    var dist_twig = 'application/views/template_2/parts';

    var config = {
        src: src,
        dist: dist,
        dist_twig: dist_twig,
        common_js: [
            src + '/js/jquery.js',
            src + '/js/tooltipster.bundle.min.js',
            src + '/js/serverdate.js',
            src + '/js/countdown.js',
            src + '/js/jquery.cookie.js',
            src + '/js/datatables.min.js',
            src + '/js/jquery.modal.js',
            src + '/js/custom.js',
            src + '/js/base.js'
        ],
        new_common_js: [
            src + '/v2/js/jquery-3.2.1.min.js',
            src + '/v2/js/owl.carousel.min.js',
            src + '/v2/js/jquery.mCustomScrollbar.concat.min.js',
            src + '/v2/js/core_custom.js'
        ],
        new_layout_js: [
            src + '/v2/js/core_custom.js'
        ],
        new_detail_js: [
            src + '/v2/js/jquery-3.2.1.min.js',
            src + '/v2/js/owl.carousel.min.js',
            src + '/v2/js/jquery.mCustomScrollbar.concat.min.js',
            src + '/js/jquery.modal.js',
            src + '/v2/js/core_custom.js'
        ],
        common_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css'
        ],
        homepage_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/index.css',
            src + '/css/custom.css'
        ],
        login_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/login.css',
            src + '/css/custom.css'
        ],
        register_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/register.css',
            src + '/css/custom.css'
        ],
        advertise_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/advertise.css',
            src + '/css/custom.css'
        ],
        project_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/project.css',
            src + '/css/custom.css'
        ],
        ico_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/icos.css',
            src + '/css/custom.css'
        ],
        submission_css: [
            src + '/libs/bootstrap/my.bootstrap.css',
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/submission.css',
            src + '/css/custom.css'
        ],
        stats_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/stats.css',
            src + '/css/custom.css'
        ],
        profile_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/profile.css',
            src + '/css/custom.css'
        ],
        listing_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/listing.css',
            src + '/css/custom.css'
        ],
        new_full_css: [
            src + '/v2/css/normalize.css',
            src + '/v2/css/owl.carousel.min.css',
            src + '/v2/css/jquery.mCustomScrollbar.min.css',
            src + '/dist/new-main.css',
            src + '/v2/css/custom.css',
            src + '/dist/new-merge.css'
        ],
        new_layout_css: [
            src + '/v2/css/normalize.css',
            src + '/dist/layout.css',
            src + '/v2/css/custom.css',
            src + '/dist/merge.css',
            src + '/dist/new-merge.css'
        ],
        new_detail_css: [
            src + '/v2/css/normalize.css',
            src + '/v2/css/owl.carousel.min.css',
            src + '/v2/css/jquery.mCustomScrollbar.min.css',
            src + '/css/jquery.modal.min.css',
            src + '/dist/new-main.css',
            src + '/v2/css/custom.css',
            src + '/dist/new-merge.css'
        ],
        people_css: [
            src + '/css/font_opensans.css',
            src + '/css/style.css',
            src + '/css/icos.css',
            src + '/css/custom.css',
            src + '/css/merge.css'
        ]
    };

    return config;
};