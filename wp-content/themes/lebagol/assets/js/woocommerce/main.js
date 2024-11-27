(function ($) {
    'use strict';
    var $body = $('body');

    function tooltip() {
        $body.on('mouseenter', '.group-action.tooltip-left .woosw-btn:not(.tooltipstered), .group-action.group-action.tooltip-left .woosq-btn:not(.tooltipstered), .group-action.group-action.tooltip-left .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'left',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });

        $body.on('mouseenter', '.group-action.tooltip-top .woosw-btn:not(.tooltipstered), .group-action.group-action.tooltip-top .woosq-btn:not(.tooltipstered), .group-action.group-action.tooltip-top .woosc-btn:not(.tooltipstered)', function () {
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

    function ajax_wishlist_count() {

        $body.on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count, .footer-wishlist .count');
            if (count == 0) {
                counter.addClass('hide');
            } else {
                counter.removeClass('hide');
            }
            counter.html(count.toString().padStart(2, '0'));
        });
    }

    function quantity() {

        $body.on("click", ".lebagol-products .quantity input", function () {
            return false;
        });

        $body.on("change input", ".lebagol-products .quantity .qty", function () {
            var add_to_cart_button = $(this).parents(".product").find(".add_to_cart_button");
            add_to_cart_button.attr("data-quantity", $(this).val());
        });

        $body.on("keypress", ".lebagol-products .quantity .qty", function (e) {
            if ((e.which || e.keyCode) === 13) {
                $(this).parents(".product").find(".add_to_cart_button").trigger("click");
            }
        });
    }

    $(document).ready(function () {
        quantity();
        tooltip();
    });
    ajax_wishlist_count();
})(jQuery);
