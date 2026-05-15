(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(hitbooxSwiperBase, {
                $element,
            });
            $element.find('a.elementor-video').magnificPopup({
                type: 'iframe',
                removalDelay: 500,
                midClick: true,
                closeBtnInside: true,
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-image-carousel.default', addHandler);
    });
})(jQuery);