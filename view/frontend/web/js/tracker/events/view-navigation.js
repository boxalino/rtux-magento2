/**
 * View category/navigation track event for Boxalino RealTimeUserExperience
 * triggered only on category pages
 */
define(['jquery'], function ($) {
    'use strict';
    return {

        supports() {
            return $.boxalino.rtuxApiHelper.isNavigation();
        },

        execute() {
            let activeId = $.boxalino.rtuxApiHelper.getActiveId();
            if (activeId) {
                /*global bxq */
                bxq(['trackCategoryView', activeId]);
            }
        }
    };
});
