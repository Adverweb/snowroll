(function ($) {
    'use strict';
    var $lebagol_apply = $('#lebagol_apply');
    lebagol_terms_select();
    lebagol_conditional_init();
    lebagol_conditional_select();
    lebagol_labels_select();

    $('.color-picker').wpColorPicker();

    $lebagol_apply.on('change', function() {
        var apply = $(this).val();
        var $terms = $('#lebagol_terms');

        $('#lebagol_configuration_combination').hide();
        $('#lebagol_configuration_terms').hide();

        if (apply === '' || apply === 'none' || apply === 'all' || apply ===
            'sale' || apply === 'featured' || apply === 'bestselling' || apply ===
            'instock' || apply === 'outofstock' || apply === 'backorder') {
            return;
        }

        if (apply === 'combination') {
            $('#lebagol_configuration_combination').show();
            return;
        }

        $('#lebagol_configuration_terms').show();

        if ((typeof $terms.data(apply) === 'string' || $terms.data(apply) instanceof
            String) && $terms.data(apply) !== '') {
            $terms.val($terms.data(apply).split(',')).change();
        } else {
            $terms.val([]).change();
        }

        lebagol_terms_select();
    });

    function lebagol_terms_select() {
        var apply = $lebagol_apply.val();
        var label = $lebagol_apply.find(':selected').text().trim();

        $('#lebagol_configuration_terms_label').html(label);

        $('#lebagol_terms').selectWoo({
            ajax: {
                url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
                    return {
                        q: params.term,
                        action: 'lebagol_search_term',
                        nonce: lebagol_vars.nonce,
                        taxonomy: apply,
                    };
                }, processResults: function(data) {
                    var options = [];
                    if (data) {
                        $.each(data, function(index, text) {
                            options.push({id: text[0], text: text[1]});
                        });
                    }
                    return {
                        results: options,
                    };
                }, cache: true,
            }, minimumInputLength: 1,
        });
    }

    $(document).on('click touch', '.lebagol_add_conditional', function(e) {
        e.preventDefault();

        var $this = $(this);

        $this.addClass('disabled');

        var data = {
            action: 'lebagol_add_conditional', nonce: lebagol_vars.nonce,
        };

        $.post(ajaxurl, data, function(response) {
            $('.lebagol_conditionals').append(response);
            lebagol_conditional_init();
            $this.removeClass('disabled');
        });
    });

    function lebagol_conditional_init() {
        $('.lebagol_conditional_apply').each(function() {
            var $this = $(this);
            var $value = $this.closest('.lebagol_conditional').
            find('.lebagol_conditional_value');
            var $select_wrap = $this.closest('.lebagol_conditional').
            find('.lebagol_conditional_select_wrap');
            var $select = $this.closest('.lebagol_conditional').
            find('.lebagol_conditional_select');
            var $compare = $this.closest('.lebagol_conditional').
            find('.lebagol_conditional_compare');
            var apply = $this.val();
            var compare = $compare.val();

            if (apply === 'sale' || apply === 'featured' || apply === 'bestselling' ||
                apply === 'instock' || apply === 'outofstock' || apply ===
                'backorder') {
                $compare.hide();
                $value.hide();
                $select_wrap.hide();
            } else {
                $compare.show();

                if (apply === 'price' || apply === 'rating' || apply === 'release') {
                    $select_wrap.hide();
                    $value.show();
                    $compare.find('.lebagol_conditional_compare_price option').
                    prop('disabled', false);
                    $compare.find('.lebagol_conditional_compare_terms option').
                    prop('disabled', true);

                    if (compare === 'is' || compare === 'is_not') {
                        $compare.val('equal').trigger('change');
                    }
                } else {
                    $select_wrap.show();
                    $value.hide();
                    $compare.find('.lebagol_conditional_compare_price option').
                    prop('disabled', true);
                    $compare.find('.lebagol_conditional_compare_terms option').
                    prop('disabled', false);

                    if (compare !== 'is' && compare !== 'is_not') {
                        $compare.val('is').trigger('change');
                    }
                }
            }

            if ($value.data(apply) !== '') {
                $value.val($value.data(apply));
            }

            if ((typeof $select.data(apply) === 'string' ||
                $select.data(apply) instanceof String) && $select.data(apply) !==
                '') {
                $select.val($select.data(apply).split(',')).change();
            } else {
                $select.val([]).change();
            }
        });
    }

    function lebagol_conditional_select() {
        $('.lebagol_conditional_select').each(function() {
            var $this = $(this);
            var apply = $this.closest('.lebagol_conditional').
            find('.lebagol_conditional_apply').
            val();

            $this.selectWoo({
                ajax: {
                    url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
                        return {
                            action: 'lebagol_search_term',
                            nonce: lebagol_vars.nonce,
                            q: params.term,
                            taxonomy: apply,
                        };
                    }, processResults: function(data) {
                        var options = [];
                        if (data) {
                            $.each(data, function(index, text) {
                                options.push({id: text[0], text: text[1]});
                            });
                        }
                        return {
                            results: options,
                        };
                    }, cache: true,
                }, minimumInputLength: 1,
            });
        });
    }

    function lebagol_labels_select() {
        $('#lebagol_labels').selectWoo({
            ajax: {
                url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
                    return {
                        action: 'lebagol_search_labels',
                        nonce: lebagol_vars.nonce,
                        q: params.term,
                    };
                }, processResults: function(data) {
                    var options = [];
                    if (data) {
                        $.each(data, function(index, text) {
                            options.push({id: text[0], text: text[1]});
                        });
                    }
                    return {
                        results: options,
                    };
                }, cache: true,
            }, minimumInputLength: 1, select: function(e) {
                var element = e.params.data.element;
                var $element = $(element);

                $(this).append($element);
                $(this).trigger('change');
            },
        });
    }

    $(document).on('change', '.lebagol_conditional_apply', function() {
        lebagol_conditional_init();
        lebagol_conditional_select();
    });

    $(document).on('click touch', '.lebagol_conditional_remove', function(e) {
        e.preventDefault();

        $(this).closest('.lebagol_conditional').remove();
    });

})(jQuery);