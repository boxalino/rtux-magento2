/**
 * View search track event for Boxalino RealTimeUserExperience
 */
define(['jquery'], function ($) {
    'use strict';
    return {

        supports(moduleName, controllerName, actionName) {
            return moduleName === 'catalogsearch' && controllerName === 'result' && actionName === 'index';
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
