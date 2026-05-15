(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(hitbooxSwiperBase, {
                $element,
            });
        };

        let $element_sticky = $('.elementor-project-style-1.elementor-style-effect-yes .grid-item');
        if ($element_sticky) {
            $(window).on('scroll', function () {
                $element_sticky.each(function (index, element) {
                    isElementSticky($(element));
                });
            });
        }

        function isElementSticky($element) {
            var windowTop = $(window).scrollTop();
            var elementBottom = $element.offset().bottom;
            var elementStickyBottom = parseInt($element.css('bottom'), 10);
            if (windowTop + elementStickyBottom >= elementBottom) {
                $element.addClass('sticked');
            }else {
                $element.removeClass('sticked');
            }
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-projects.default', addHandler);
    });
})(jQuery);
