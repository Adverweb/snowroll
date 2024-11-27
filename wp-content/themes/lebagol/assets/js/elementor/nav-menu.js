(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/lebagol-nav-menu.default', ($scope) => {
            let $button = $scope.find('a.main-navigation-button');
            if($button.length) {
                $button.on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let $wrap = $(this).closest('.elementor-nav-menu-wrapper');
                    $wrap.toggleClass('popup-active');
                });
            }
        });
    });
})(jQuery);