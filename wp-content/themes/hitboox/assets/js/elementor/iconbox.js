(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        var _path = elementorModules.frontend.handlers.Base.extend({
            onElementChange(propertyName) {
                setTimeout(() => {
                    $(document).trigger('path-reload');
                },400);
            }
        });
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(hitbooxSwiperBase, {
                $element,
            });
            elementorFrontend.elementsHandler.addHandler(_path, {
                $element,
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-iconbox.default', addHandler);
    });
})(jQuery);