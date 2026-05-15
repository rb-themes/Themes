(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {

            if($element.hasClass('elementor-service-style-2') || $element.hasClass('elementor-service-style-3')){
                let $service_head = $element.find('.grid-item');
                $service_head.first().addClass('active');
                $($element).on('mouseenter','.service-inner',function (e) {
                    let $parent = $(this).closest('.grid-item');
                    $service_head.removeClass('active');
                    $parent.addClass('active');
                });
            }

            elementorFrontend.elementsHandler.addHandler(hitbooxSwiperBase, {
                $element,
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-services.default', addHandler);
    });
})(jQuery);