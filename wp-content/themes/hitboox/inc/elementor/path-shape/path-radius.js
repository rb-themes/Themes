(function ($, elementorFrontend, elementorModules) {
    'use strict';
    var _decor = elementorModules.frontend.handlers.Base.extend({
        onInit() {
            const elementSettings = this.getElementSettings();

            if (elementSettings.hitboox_path_text) {
                this.$element.css('--path', elementSettings.hitboox_path_text);
                $(document).trigger('path-reload');
            }
            if (elementSettings.hitboox_path_radius_value) {
                this.$element.css('--path-radius', elementSettings.hitboox_path_radius_value);
                $(document).trigger('path-reload');
            }
        },
        getChangeableProperties() {
            return {
                hitboox_path_text: 'hitboox_path_text',
                hitboox_path_radius_value: 'hitboox_path_radius_value',
            }
        },
        onElementChange(propertyName) {
            const changeableProperties = this.getChangeableProperties();
            if (changeableProperties[propertyName]) {
                if ('hitboox_path_text' === propertyName) {
                    let newSettingValue = this.getElementSettings('hitboox_path_text');
                    this.$element.css('--path', newSettingValue);
                    $(document).trigger('path-reload');
                    console.log(newSettingValue);
                }
                if ('hitboox_path_radius_value' === propertyName) {
                    let newSettingValue = this.getElementSettings('hitboox_path_radius_value');
                    this.$element.css('--path-radius', newSettingValue);
                    $(document).trigger('path-reload');
                }
            }
            $(document).trigger('path-reload');
        }
    });

    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(_decor, {
                $element,
            });
        };

        elementorFrontend.hooks.addAction('frontend/element_ready/section', addHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/container', addHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', addHandler);
    });

}(jQuery, window.elementorFrontend, window.elementorModules));
