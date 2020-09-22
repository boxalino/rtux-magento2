/**
 * View search track event for Boxalino RealTimeUserExperience
 */
define(['jquery'], function ($) {
    'use strict';
    return {

        supports() {
            return $.boxalino.rtuxApiHelper.isSearch();
        },

        execute() {
            let searchInput = $("#search").val();
            if(searchInput) {
                /*global bxq */
                bxq(['trackSearch', searchInput]);
            }
        }
    };
});
