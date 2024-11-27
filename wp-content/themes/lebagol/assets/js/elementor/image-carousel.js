(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(lebagolSwiperBase, {
                $element,
            });
            $element.find('a.elementor-video').magnificPopup({
                type: 'iframe',
                removalDelay: 500,
                midClick: true,
                closeBtnInside: true,
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/lebagol-image-carousel.default', addHandler);
    });
})(jQuery);
