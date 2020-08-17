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
            profile: null,
            language: null
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
        this.getApiRequestData = function(widget, hitCount, groupBy, otherParameters = {}) {
            let baseParameters = {
                'username': this.options.account,
                'apiKey': this.options.key,
                'sessionId': this.getApiSessionId(),
                'profileId': this.getApiProfileId(),
                'customerId': this.getApiCustomerId(this.options.profile),
                'widget': widget,
                'dev': this.options.dev,
                'test': this.options.test,
                'hitCount': hitCount,
                'language': this.options.language,
                'groupBy': groupBy,
                'parameters': {
                    'User-Referer': document.referrer,
                    'User-URL': window.location.href,
                    'User-Agent': navigator.userAgent
                }
            };

            return Object.assign({}, baseParameters, otherParameters);
        }

        /**
         * @public
         * @param url string|null
         * @returns {string}
         */
        this.getApiRequestUrl = function(url = null) {
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
        this.getApiCustomerId = function(customerId = null) {
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
            return $.mage.cookies.get('cemv');
        }

        /**
         * @public
         * @returns {string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg}
         */
        this.getApiSessionId = function() {
            return $.mage.cookies.get('cems');
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
