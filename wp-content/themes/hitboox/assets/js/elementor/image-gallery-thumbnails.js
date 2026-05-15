(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(hitbooxSwiperBase, {
                $element,
            });
            $('body').on('mouseover', 'a.hitboox-video-item', function () {
                $(this).magnificPopup({
                    type: 'iframe',
                    removalDelay: 500,
                    midClick: true,
                    closeBtnInside: true,
                    callbacks: {
                        beforeOpen: function () {
                            this.st.mainClass = this.st.el.attr('data-effect');
                        }
                    },
                })
            });

            $('body').on('click', 'a.hitboox-video-item', function () {
                $(this).magnificPopup('open');
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-image-gallery-thumbnails.default', addHandler);
    });
})(jQuery);



