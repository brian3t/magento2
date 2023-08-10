/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(["jquery"], function ($) {
    $('.header-container .switcher .dropdown .mage-dropdown-dialog').css({'display': 'none'});
    if ($(".hidden-title-category").length > 0) {
        $(".page-title").css({'display': 'none'});
    }
    ;
    if ($('.cms-home-style-4 .header-container').hasClass('.header-style-4')) {
        $('.cms-home-style-4').addClass('hd-4');
    }

});	