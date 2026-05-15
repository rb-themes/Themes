(function ($, elementorFrontend, elementorModules) {
    'use strict';
    var _decor = elementorModules.frontend.handlers.Base.extend({
        onInit() {
            const elementSettings = this.getElementSettings();
            if (elementSettings.hitboox_decor_top_left === 'yes') {
                this.$element.append(`<div class="hitboox-border-shape top-left"><svg viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg"><path d="M147.269 54.72L117.876 25.28C114.502 21.9015 109.919 20 105.145 20H0V60H160C155.226 60 150.642 58.0985 147.269 54.72Z"/><path d="M0 0V20H20C8.95435 20 0 11.0457 0 0Z"/></svg></div>`);
            }
            if (elementSettings.hitboox_decor_top_right === 'yes') {
                this.$element.append(`<div class="hitboox-border-shape top-right"><svg viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg"><path d="M147.269 54.72L117.876 25.28C114.502 21.9015 109.919 20 105.145 20H0V60H160C155.226 60 150.642 58.0985 147.269 54.72Z"/><path d="M0 0V20H20C8.95435 20 0 11.0457 0 0Z"/></svg></div>`);
            }
            if (elementSettings.hitboox_decor_bottom_right === 'yes') {
                this.$element.append(`<div class="hitboox-border-shape bottom-right"><svg viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg"><path d="M147.269 54.72L117.876 25.28C114.502 21.9015 109.919 20 105.145 20H0V60H160C155.226 60 150.642 58.0985 147.269 54.72Z"/><path d="M0 0V20H20C8.95435 20 0 11.0457 0 0Z"/></svg></div>`);
            }
            if (elementSettings.hitboox_decor_bottom_left === 'yes') {
                this.$element.append(`<div class="hitboox-border-shape bottom-left"><svg viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg"><path d="M147.269 54.72L117.876 25.28C114.502 21.9015 109.919 20 105.145 20H0V60H160C155.226 60 150.642 58.0985 147.269 54.72Z"/><path d="M0 0V20H20C8.95435 20 0 11.0457 0 0Z"/></svg></div>`);
            }
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
