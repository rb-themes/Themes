(function ($) {
    'use strict';
    var $body = $('body');
    var xhr = false;
    function quantity() {
        var $parent = $(".products");
        $parent.on("click", ".quantity input", function () {
            return false;
        });

        $parent.on("change input", ".quantity .qty", function () {
            var add_to_cart_button = $(this).parents(".product").find(".add_to_cart_button");
            add_to_cart_button.attr("data-quantity", $(this).val());
        });

        $parent.on("keypress", ".quantity .qty", function (e) {
            if ((e.which || e.keyCode) === 13) {
                $(this).parents(".product").find(".add_to_cart_button").trigger("click");
            }
        });
    }

    function quantity_product_list() {
        var $parent = $(".products-list");
        $parent.on("click", ".quantity input", function () {
            return false;
        });

        $parent.on("change input", ".quantity .qty", function () {
            var add_to_cart_button = $(this).parents(".product-list").find(".add_to_cart_button");
            add_to_cart_button.attr("data-quantity", $(this).val());
        });

        $parent.on("keypress", ".quantity .qty", function (e) {
            if ((e.which || e.keyCode) === 13) {
                $(this).parents(".product-list").find(".add_to_cart_button").trigger("click");
            }
        });
    }

    function tooltip() {

        $('body').on('mouseenter', '.product-list .product-caption .woosw-btn:not(.tooltipstered), .product-list .product-caption .woosq-btn:not(.tooltipstered), .product-list .product-caption .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });
    }

    function product_hover_image() {

        $('body').on('click', '.product-block .product-color .item', function () {
            var image = $(this).data('image');
            var $product = $(this).closest('.product-block');
            var $image = $product.find('.product-image img');
            $image.attr('src', image.src);
            $image.attr('srcset', image.srcset);
            $image.attr('sizes', image.sizes);
            if ($(this).hasClass('active-swatch')) {
                return;
            }
            $(this).parent().find('.active-swatch').removeClass('active-swatch');
            $(this).addClass('active-swatch');
        });
    }

    function ajax_wishlist_count() {

        $(document).on('added_to_wishlist removed_from_wishlist', function () {
            var counter = $('.header-wishlist .count, .footer-wishlist .count, .header-wishlist .wishlist-count-item');
            $.ajax({
                url: yith_wcwl_l10n.ajax_url,
                data: {
                    action: 'yith_wcwl_update_wishlist_count'
                },
                dataType: 'json',
                success: function (data) {
                    counter.html(data.count);
                    $('.wishlist-count-text').html(data.text);
                },
            });
        });

        $('body').on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count, .footer-wishlist .count, .header-wishlist .wishlist-count-item');

            $.ajax({
                url: woosw_vars.ajax_url,
                data: {
                    action: 'woosw_ajax_update_count'
                },
                dataType: 'json',
                success: function (data) {
                    $('.wishlist-count-text').html(data.text);
                },
            });
            counter.html(count);
        });
    }

    function woo_widget_categories() {
        var widget = $('.widget_product_categories'),
            main_ul = widget.find('ul');
        if (main_ul.length) {
            var dropdown_widget_nav = function () {

                main_ul.find('li').each(function () {

                    var main = $(this),
                        link = main.find('> a'),
                        ul = main.find('> ul.children');
                    if (ul.length) {

                        //init widget
                        // main.removeClass('opened').addClass('closed');

                        if (main.hasClass('closed')) {
                            ul.hide();

                            link.before('<i class="icon-plus"></i>');
                        } else if (main.hasClass('opened')) {
                            link.before('<i class="icon-minus"></i>');
                        } else {
                            main.addClass('opened');
                            link.before('<i class="icon-minus"></i>');
                        }

                        // on click
                        main.find('i').on('click', function (e) {

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('>i').removeClass('icon-plus').addClass('icon-minus');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('>i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });

                        main.on('click', function (e) {

                            if ($(e.target).filter('a').length)
                                return;

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('i').removeClass('icon-plus').addClass('icon-minus');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });
                    }
                });
            };
            dropdown_widget_nav();
        }
    }

    function sendRequest(url) {

        if (xhr) {
            xhr.abort();
        }

        xhr = $.ajax({
            type: "GET",
            url: url,
            beforeSend: function () {
                var $products = $('ul.hitboox-products');
                $products.addClass('preloader');
            },
            success: function (data) {
                let $html = $(data);
                $('#main ul.hitboox-products').replaceWith($html.find('#main ul.hitboox-products'));
                $('#main .woocommerce-pagination').replaceWith($html.find('#main .woocommerce-pagination'));
                window.history.pushState(null, null, url);
                xhr = false;
                $(document).trigger('hitboox-products-loaded')
            }
        });
    }

    $body.on('change', '.hitboox-products-per-page #per_page', function (e) {
        e.preventDefault();
        var url = this.value;
        sendRequest(url);
    });

    quantity();
    quantity_product_list();
    product_hover_image();
    woo_widget_categories();
    tooltip();
    ajax_wishlist_count();
})(jQuery);
