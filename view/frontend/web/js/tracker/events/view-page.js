/**
 * View page track event for Boxalino RealTimeUserExperience
 * (it is sent from any page)
 */
define([], function () {
    'use strict';
    return {

        supports() {
            return true;
        },

        execute() {
            /*global bxq */
            bxq(['trackPageView']);
        }
    };
});
