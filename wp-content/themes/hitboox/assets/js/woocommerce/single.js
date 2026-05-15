(function ($) {
    'use strict';
    var $body = $('body');

    function singleProductGalleryImages() {
        var lightbox = $('.single-product .woocommerce-product-gallery__image > a');
        if (lightbox.length) {
            lightbox.attr("data-elementor-open-lightbox", "no");
        }

        if (typeof elementorFrontendConfig !== 'undefined') {
            const swiperClass = elementorFrontend.config.swiperClass;
            var galleryVertical = $('.woocommerce-product-gallery.woocommerce-product-gallery-vertical .flex-control-thumbs');
            if (galleryVertical.length > 0) {
                galleryVertical.wrap(`<div class='${swiperClass} ${swiperClass}-thumbs-vertical'></div>`).addClass(`${swiperClass}-wrapper`).find('li').addClass('swiper-slide');
                $(`.${swiperClass}-thumbs-vertical`).append('<div class="elementor-swiper-button elementor-swiper-button-prev"><i class="hitboox-icon-arrow-left" aria-hidden="true"></i><span class="elementor-screen-only">Previous</span></div><div class="elementor-swiper-button elementor-swiper-button-next"><i class="hitboox-icon-arrow-right" aria-hidden="true"></i><span class="elementor-screen-only">Next</span></div>');
                new Swiper(`.${swiperClass}-thumbs-vertical`, {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    autoHeight: true,
                    direction: 'vertical',
                    navigation: {
                        prevEl: $(`.${swiperClass}-thumbs-vertical`).find('.elementor-swiper-button-prev').get(0),
                        nextEl: $(`.${swiperClass}-thumbs-vertical`).find('.elementor-swiper-button-next').get(0),
                    }
                });
            }
        }
    }

    $('.woocommerce-product-gallery').on('wc-product-gallery-after-init', function () {
        singleProductGalleryImages();
    });

    $body.on('click', '.wc-tabs li a, ul.tabs li a', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');
        var $control = $tab.closest('li').attr('aria-controls');
        $tabs_wrapper.find('.resp-accordion').removeClass('active');
        $('.' + $control).addClass('active');

    }).on('click', 'h2.resp-accordion', function (e) {
        e.preventDefault();
        var $tab = $(this);
        var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');
        var $tabs = $tabs_wrapper.find('.wc-tabs, ul.tabs');

        if ($tab.hasClass('active')) {
            return;
        }
        $tabs_wrapper.find('.resp-accordion').removeClass('active');
        $tab.addClass('active');
        $tabs.find('li').removeClass('active');
        $tabs.find($tab.data('control')).addClass('active');
        $tabs_wrapper.find('.wc-tab, .panel:not(.panel .panel)').hide(300);
        $tabs_wrapper.find($tab.attr('aria-controls')).show(300);

    });

})(jQuery);
