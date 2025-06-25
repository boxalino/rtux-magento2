/**
 * API JS Helper class
 * it sets setup configurations via default.xml (if desired)
 * reusable for the client-side JS requests integration
 *
 * Can be accessed as $.boxalino.rtuxApiHelper in other JS components
 */

define([
    'jquery',
    'mage/mage',
    'mage/cookies'
], function ($) {
    'use strict';

    var RtuxApiHelper = function () {

        /**
         * Cookie default values.
         * @type {Object}
         */
        this.options = {
            dev: false,
            test: false,
            endpoint: "//track.bx-cloud.com/static/bav2.min.js",
            account: null,
            key: null,
            language: null,
            currencyCode: null,
            activeId: null,
            isProduct: false,
            isNavigation: false,
            isSearch: false,
            isRestricted: false,
            isUserNotAllowed: false,
            userCookieConfirmed: false,
            active: []
        };

        /**
         * additional parameters to be set: filters, facets, sort
         * for more details, check the Narrative Api Technical Integration manual provided by Boxalino
         *
         * @public
         * @param widget string
         * @param hitCount int
         * @param groupBy string
         * @param {*} otherParameters
         * @returns {{widget: *, hitCount: number, apiKey: *, dev: boolean, test: boolean, profileId: (string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg), customerId: (string|*), language: *, sessionId: *, groupBy: *, parameters: {"User-Agent": string, "User-URL", "User-Referer": string}, username: *}}
         */
        this.getApiRequestData = function(widget, hitCount, groupBy, otherParameters) {
            let baseParameters = {
                'username': this.getAccount(),
                'apiKey': this.options.key,
                'sessionId': this.getApiSessionId(),
                'profileId': this.getApiProfileId(),
                'widget': widget,
                'dev': this.isDev(),
                'test': this.isTest(),
                'hitCount': hitCount,
                'language': this.options.language,
                'groupBy': groupBy,
                'parameters': {
                    'User-Referer': document.referrer,
                    'User-URL': window.location.href,
                    'User-Agent': navigator.userAgent
                }
            };

            if (!otherParameters)
            {
                otherParameters = {};
            }

            return Object.assign({}, baseParameters, otherParameters);
        }

        /**
         * @public
         * @param url string|null
         * @returns {string}
         */
        this.getApiRequestUrl = function(url) {
            var endpoint = this.options.endpoint;
            if (url) {
                endpoint = url;
            }
            return endpoint + '?profileId=' + encodeURIComponent(this.getApiProfileId());
        }

        /**
         * @public
         * @returns {string|*}
         * @param customerId
         */
        this.getApiCustomerId = function(customerId) {
            if (customerId) {
                return atob(customerId);
            }

            return this.getApiProfileId();
        }

        /**
         * @public
         * @returns {string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg}
         */
        this.getApiProfileId = function() {
            if(this.hasCookieRestriction() && !this.userCookieConfirmed()) {
                return this.options.active[1];
            }
            return $.mage.cookies.get('cemv');
        }

        /**
         * @public
         * @returns {string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg}
         */
        this.getApiSessionId = function() {
            if(this.hasCookieRestriction() && !this.userCookieConfirmed()) {
                return this.options.active[0];
            }
            return $.mage.cookies.get('cems');
        }

        /**
         * @public
         * @returns {string|null}
         */
        this.getAccount = function() {
            return this.options.account;
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.isTest = function() {
            return this.options.test;
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.isDev = function() {
            return this.options.dev;
        }

        /**
         * @public
         */
        this.useDebugCookie = function(){
            bxq(['debugCookie', true]);
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.hasCookieRestriction = function() {
            return this.options.isRestricted;
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.isUserNotAllowed = function() {
            return this.options.isUserNotAllowed;
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.userCookieConfirmed = function() {
            return this.options.userCookieConfirmed;
        }

        /**
         * @public
         * @param value
         */
        this.setUserCookieConsent = function(value) {
            this.options.userCookieConfirmed = value;
            if(value) {
                this.setRtuxCookie("cemv", this.options.active[1], false);
                this.setRtuxCookie("cems", this.options.active[0], true);
            }
        }

        /**
         * @param name
         * @param value
         * @param isSession
         */
        this.setRtuxCookie = function(name, value, isSession) {
            let options = {}, defaultLifetime=$.mage.cookies.defaults.lifetime;
            if(isSession) {
                options = {lifetime: null, expires: null};
                $.mage.cookies.defaults.lifetime = null;
            }

            $.mage.cookies.set(name, value, options);
            if(isSession) {
                $.mage.cookies.defaults.lifetime = defaultLifetime;
            }
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.isNavigation = function() {
            return this.options.isNavigation;
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.isProduct = function() {
            return this.options.isProduct;
        }

        /**
         * @public
         * @returns {boolean}
         */
        this.isSearch = function() {
            return this.options.isSearch;
        }

        /**
         * @returns {null | string}
         */
        this.getActiveId = function() {
            return this.options.activeId;
        }

        /**
         * @returns {null| string}
         */
        this.getCurrencyCode = function() {
            return this.options.currencyCode;
        }

        return this;
    };

    $.extend(true, $, {
        boxalino: {
            rtuxApiHelper: new RtuxApiHelper()
        }
    });

    return function (options) {
        $.extend($.boxalino.rtuxApiHelper.options, options);
    };

});
