/**
 * Login track for Boxalino RealTimeUserExperience
 * With the use of the server-side observer, a cookie is set on customer-login event
 */
define(['jquery'], function ($) {
    'use strict';
    return {

        supports() {
            return $.boxalino.rtuxApiHelper.isLogin();
        },

        execute() {
            /*global bxq */
            bxq(['trackLogin', $.boxalino.rtuxApiHelper.getCustomerId()]);
        }
    };
});
