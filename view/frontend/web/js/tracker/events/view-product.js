/**
 * View product view track event for Boxalino RealTimeUserExperience
 */
define(['jquery'], function ($) {
    'use strict';
    return {

        supports() {
            return $.boxalino.rtuxApiHelper.isProduct();
        },

        execute() {
            let activeId = $.boxalino.rtuxApiHelper.getActiveId();
            if (activeId) {
                /*global bxq */
                bxq(['trackProductView', activeId]);
            }
        }
    };
});
