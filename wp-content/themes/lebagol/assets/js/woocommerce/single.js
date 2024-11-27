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
            var galleryHorizontal = $('.woocommerce-product-gallery.woocommerce-product-gallery-horizontal .flex-control-thumbs');

            if (galleryHorizontal.length > 0) {
                galleryHorizontal.wrap(`<div class='${swiperClass} ${swiperClass}-thumbs-horizontal'></div>`).addClass(`${swiperClass}-wrapper`).find('li').addClass('swiper-slide');
                $(`.${swiperClass}-thumbs-horizontal`).append('<div class="elementor-swiper-button elementor-swiper-button-prev"><i class="lebagol-icon-angle-left" aria-hidden="true"></i><span class="elementor-screen-only">Previous</span></div><div class="elementor-swiper-button elementor-swiper-button-next"><i class="lebagol-icon-angle-right" aria-hidden="true"></i><span class="elementor-screen-only">Next</span></div>');
                new Swiper(`.${swiperClass}-thumbs-horizontal`, {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    navigation: {
                        prevEl: $(`.${swiperClass}-thumbs-horizontal`).find('.elementor-swiper-button-prev').get(0),
                        nextEl: $(`.${swiperClass}-thumbs-horizontal`).find('.elementor-swiper-button-next').get(0),
                    },
                });
            }
            var galleryVertical = $('.woocommerce-product-gallery.woocommerce-product-gallery-vertical .flex-control-thumbs');
            if (galleryVertical.length > 0) {
                galleryVertical.wrap(`<div class='${swiperClass} ${swiperClass}-thumbs-vertical'></div>`).addClass(`${swiperClass}-wrapper`).find('li').addClass('swiper-slide');
                $(`.${swiperClass}-thumbs-vertical`).append('<div class="elementor-swiper-button elementor-swiper-button-prev"><i class="lebagol-icon-angle-left" aria-hidden="true"></i><span class="elementor-screen-only">Previous</span></div><div class="elementor-swiper-button elementor-swiper-button-next"><i class="lebagol-icon-angle-right" aria-hidden="true"></i><span class="elementor-screen-only">Next</span></div>');
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

            var gallerySlider = $('.woocommerce-product-gallery-slider .woocommerce-product-gallery__wrapper');
            var navigationSlider = $('.woocommerce-product-gallery-slider');
            if (gallerySlider.length) {
                gallerySlider.wrap(`<div class='${swiperClass} ${swiperClass}-product-slider'></div>`).addClass(`${swiperClass}-wrapper`).find('div.woocommerce-product-gallery__image').addClass('swiper-slide');
                navigationSlider.append('<div class="elementor-swiper-button elementor-swiper-button-prev"><i class="lebagol-icon-arrow-left" aria-hidden="true"></i><span class="elementor-screen-only">Previous</span></div><div class="elementor-swiper-button elementor-swiper-button-next"><i class="lebagol-icon-arrow-right" aria-hidden="true"></i><span class="elementor-screen-only">Next</span></div>');
                navigationSlider.append('<div class="swiper-pagination"></div>');
                new Swiper(`.${swiperClass}-product-slider`, {
                    slidesPerView: 1,
                    navigation: {
                        prevEl: navigationSlider.find('.elementor-swiper-button-prev').get(0),
                        nextEl: navigationSlider.find('.elementor-swiper-button-next').get(0),
                    },
                    pagination: {
                        el: navigationSlider.find('.swiper-pagination').get(0),
                        type: 'bullets',
                        clickable: true,
                    },
                });
            }
        }
    }

    function setupCarousel(selector) {
        if (typeof elementorFrontendConfig === 'undefined') {
            return;
        }
        if (typeof elementorFrontendConfig.kit === 'undefined') {
            return;
        }

        var kit = elementorFrontendConfig.kit;

        var settingCarousel = {
            slidesPerView: kit.woo_carousel_slides_to_show || 1,
            spaceBetween: kit.woo_carousel_spaceBetween.size || 0,
            handleElementorBreakpoints: true,
            watchSlidesProgress: true,
            breakpoints: {}
        };

        if (kit.woo_carousel_autoplay === 'yes') {
            settingCarousel.autoplay = {
                delay: kit.woo_carousel_autoplay_speed
            }
        }

        var elementorBreakpoints = kit.active_breakpoints;
        var lastBreakpintOption = {
            slidesPerView: settingCarousel.slidesPerView,
            spaceBetween: settingCarousel.spaceBetween
        };
        let breakpointsKey = Object.keys(elementorBreakpoints).reverse();

        for (let _index in breakpointsKey) {

            var breakpointName = elementorBreakpoints[_index].replace('viewport_', '');

            let currentSettings = {
                spaceBetween: +kit['woo_carousel_spaceBetween_' + breakpointName]['size'] || lastBreakpintOption.spaceBetween,
                slidesPerView: +kit['woo_carousel_slides_to_show_' + breakpointName] || lastBreakpintOption.slidesPerView
            };
            // lastBreakpintOption = currentSettings;
            var viewport = elementorFrontend.config.responsive.activeBreakpoints[breakpointName].value;

            settingCarousel.breakpoints[viewport] = currentSettings;

            if (breakpointName === 'mobile') {
                settingCarousel.breakpoints[0] = currentSettings;
            }
        }

        var $container = $(selector);
        const swiperClass = elementorFrontend.config.swiperClass;

        $container.append(`<div class="products-carousel"><div class="${swiperClass}"></div></div>`);

        if (kit.woo_carousel_navigation === 'dots' || kit.woo_carousel_navigation === 'both') {
            $container.find('.products-carousel').append('<div class="swiper-pagination"></div>');
            settingCarousel.pagination = {
                el: $container.find('.swiper-pagination').get(0),
                type: 'bullets',
                clickable: true,
            };
        }
        if (kit.woo_carousel_navigation === 'arrows' || kit.woo_carousel_navigation === 'both') {
            $container.append('<div class="elementor-swiper-button elementor-swiper-button-prev"><i class="eicon-chevron-left" aria-hidden="true"></i><span class="elementor-screen-only">Previous</span></div><div class="elementor-swiper-button elementor-swiper-button-next"><i class="eicon-chevron-right" aria-hidden="true"></i><span class="elementor-screen-only">Next</span></div>');
            settingCarousel.navigation = {
                prevEl: $container.find('.elementor-swiper-button-prev').get(0),
                nextEl: $container.find('.elementor-swiper-button-next').get(0),
            };
        }


        $container.find('ul.products').appendTo($container.find(`.products-carousel .${swiperClass}`));
        if ($container.find('li.product').length > 1) {
            $container.find('ul.products').addClass('swiper-wrapper').find('>li').addClass('swiper-slide');
            var gallery_swiper = new Swiper($container.find(`.${swiperClass}`).get(0), settingCarousel)
            console.log(settingCarousel);
            $container.data('swiper', gallery_swiper);

            checkLastVisible(gallery_swiper);
            gallery_swiper.on('slideChange', function (swiper) {
                checkLastVisible(swiper)
            });
        }
    }

    function checkLastVisible(swiper) {
        const lastVisibleClass = 'last-visible';
        let $slides = $(swiper.slides).removeClass(lastVisibleClass);
        let lastVisibleIndex = 0;
        $slides.each(function (index) {
            if ($(this).hasClass('swiper-slide-visible')) {
                lastVisibleIndex = index;
            }
        });
        $slides.eq(lastVisibleIndex).addClass(lastVisibleClass);
    }

    $('.woocommerce-product-gallery').on('wc-product-gallery-after-init', function () {
        singleProductGalleryImages();
    });

    function output_accordion() {
        $('.js-card-body.active').slideDown();
        /*   Produc Accordion   */
        $('.js-btn-accordion').on('click', function () {
            if (!$(this).hasClass('active')) {
                $('.js-btn-accordion').removeClass('active');
                $('.js-card-body').removeClass('active').slideUp();
            }
            $(this).toggleClass('active');
            var card_toggle = $(this).parent().find('.js-card-body');
            card_toggle.slideToggle();
            card_toggle.toggleClass('active');

            setTimeout(function () {
                $('.product-sticky-layout').trigger('sticky_kit:recalc');
            }, 1000);
        });
    }

    function _makeStickyKit() {
        var top_sticky = 20,
            $adminBar = $('#wpadminbar');

        if ($adminBar.length > 0) {
            top_sticky += $adminBar.height();
        }

        if (window.innerWidth < 992) {
            $('.product-sticky-layout').trigger('sticky_kit:detach');
        } else {
            $('.product-sticky-layout').stick_in_parent({
                offset_top: top_sticky
            });
        }
    }

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

    var $hasThumbnail = false;
    var swiperThumbnails;
    var swiperMain;

    function stickyThumbnails() {
        var html = '';
        var $parent = $('.woocommerce-product-gallery-sticky');
        if (!$parent.length) {
            return;
        }
        var $gallery = $('.woocommerce-product-gallery__wrapper');
        if (!$hasThumbnail) {
            $gallery.find('.woocommerce-product-gallery__image').each(function () {
                var $this = $(this);
                var image = $this.data('thumb'),
                    alt = $this.find('a img').attr('alt'),
                    title = $this.find('a img').attr('title');

                html += '<div class="swiper-slide"><img alt="' + alt + '" title="' + title + '" src="' + image + '" /></div>';
            });

            $(`<div class="swiper sticky-thumbnails"><div class="swiper-wrapper">${html}</div></div>`).insertAfter($gallery);
            $hasThumbnail = true;
        }

        var top_sticky = 20,
            $adminBar = $('#wpadminbar');

        if ($adminBar.length > 0) {
            top_sticky += $adminBar.height();
        }

        if (window.innerWidth < 768) {

            $('.sticky-thumbnails').trigger('sticky_kit:detach');

            if (typeof swiperThumbnails == 'undefined' && typeof swiperMain == 'undefined') {
                swiperThumbnails = new Swiper(".sticky-thumbnails", {
                    loop: false,
                    spaceBetween: 10,
                    slidesPerView: 'auto',
                });
                swiperMain = new Swiper(".woocommerce-product-gallery__wrapper", {
                    loop: false,
                    slidesPerView: 1,
                    spaceBetween: 10,
                    thumbs: {
                        swiper: swiperThumbnails,
                    },
                });
            }

        } else {

            checkImagesLoaded('.sticky-thumbnails', function() {
                $('.sticky-thumbnails').stick_in_parent({
                    offset_top: top_sticky
                });
            });

            if (typeof swiperThumbnails !== 'undefined' && typeof swiperMain !== 'undefined') {
                swiperThumbnails.destroy();
                swiperThumbnails = undefined;
                swiperMain.destroy();
                swiperMain = undefined;
                $('.woocommerce-product-gallery__wrapper .swiper-wrapper, .sticky-thumbnails .swiper-wrapper').removeAttr('style');
                $('.woocommerce-product-gallery__wrapper .swiper-slide, .sticky-thumbnails .swiper-slide').removeAttr('style');
            }
        }

    }

    function checkImagesLoaded(elementClass, callback) {
        var images = $(elementClass).find('img');
        var totalImages = images.length;
        var loadedImages = 0;

        images.on('load', function() {
            loadedImages++;
            if (loadedImages === totalImages) {
                callback();
            }
        });

        images.each(function() {
            if (this.complete) {
                $(this).trigger('load');
            }
        });
    }


    function scrollMainThumbnail() {
        let indexActive = 0;
        window.addEventListener("scroll", (function () {
            if (window.innerWidth >= 768) {
                $('.woocommerce-product-gallery__wrapper').find(".swiper-slide").each((function () {
                    const slide = $(this);
                    if (slide.is(":visible")) {
                        let slideHeight = slide.outerHeight(true);
                        let slideIndex = slide.index() + 1;
                        let slideTop = slide.offset().top;
                        let doccumentTop = $(document).scrollTop();
                        let isVisible = doccumentTop > slideTop && doccumentTop < slideHeight + slideTop;

                        if (isVisible && indexActive !== slideIndex) {
                            indexActive = slideIndex;
                            $(".sticky-thumbnails .swiper-slide").removeClass("swiper-slide-thumb-active");
                            $(".sticky-thumbnails .swiper-slide:nth-child(" + slideIndex + ")").addClass("swiper-slide-thumb-active");
                        }
                    }
                }))
            }
        }));
    }

    $body.on("click", ".sticky-thumbnails .swiper-slide", function () {
        if (window.innerWidth >= 768) {
            scrollToElement($('.woocommerce-product-gallery__wrapper').find(".swiper-slide:nth-child(" + ($(this).index() + 1) + ")"));
        }
    });

    function scrollToElement(element) {
        let elementTop = element.offset().top + 1;
        $([document.documentElement, document.body]).animate({scrollTop: elementTop}, 500);
    }

    function single_popup(){
        var $button_sizechart = $('.product-sizechart-button');
        if ($button_sizechart.length > 0) {
            $button_sizechart.magnificPopup({
                type: 'inline',
                fixedContentPos: false,
                fixedBgPos: true,
                overflowY: 'auto',
                closeBtnInside: true,
                preloader: false,
                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in',
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                }
            });
        }
        var $button_ask = $('.ask-a-question-button');
        if ($button_ask.length > 0) {
            $button_ask.magnificPopup({
                type: 'inline',
                fixedContentPos: false,
                fixedBgPos: true,
                overflowY: 'auto',
                closeBtnInside: true,
                preloader: false,
                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in',
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        setupCarousel('.related');
        setupCarousel('.upsells');
        output_accordion();
        stickyThumbnails();
        scrollMainThumbnail();
        single_popup();

        if ($('.product-sticky-layout').length > 0) {
            _makeStickyKit();
            $(window).on('resize', function () {
                setTimeout(function () {
                    _makeStickyKit();
                    stickyThumbnails();
                }, 100);
            });
        }

    });

})(jQuery);
