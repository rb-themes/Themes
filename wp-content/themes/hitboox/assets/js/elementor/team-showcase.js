(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-team-showcase.default', ($scope) => {
            let $tabs = $scope.find('.team-showcase-title-wrapper');
            let $contents = $scope.find('.team-showcase-content-wrapper');
            // Active tab
            $contents.find('.elementor-active').show();
            var windowsize = $(window).width();
            if (windowsize > 767) {
                $tabs.find('.elementor-team-showcase-title').hover(function (e) {
                    let id = $(this).attr('aria-controls');
                    e.preventDefault();
                    $tabs.find('.elementor-team-showcase-title').removeClass('elementor-active');
                    $contents.find('.elementor-team-showcase-content').removeClass('elementor-active');
                    $(this).addClass('elementor-active');
                    $contents.find('#' + id).addClass('elementor-active');

                });
            } else {
                $tabs.find('.elementor-team-showcase-title').on('click', function (e) {
                    let id = $(this).attr('aria-controls');
                    e.preventDefault();
                    $contents.find('.elementor-prev').removeClass('elementor-prev');
                    $contents.find('.elementor-active').addClass('elementor-prev');
                    $tabs.find('.elementor-team-showcase-title').removeClass('elementor-active');
                    $contents.find('.elementor-team-showcase-content').removeClass('elementor-active');
                    $(this).addClass('elementor-active');
                    $contents.find('#' + id).addClass('elementor-active');
                });
            }

        });
    });

})(jQuery);
