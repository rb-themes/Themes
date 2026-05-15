(function ($) {
    "use strict";
    var _button = elementorModules.frontend.handlers.Base.extend({
        onElementChange(propertyName) {
            setTimeout(() => {
                $(document).trigger('path-reload');
            },400);
        }
    });
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(_button, {
                $element,
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/hitboox-button.default', addHandler);
    });

})(jQuery);