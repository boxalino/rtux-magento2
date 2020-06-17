define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    $.widget('rtuxApiHelper.js', {
        /**
         * additional parameters to be set: returnFields, filters, facets, sort
         * for more details, check the Narrative Api Technical Integration manual provided by Boxalino
         *
         * @public
         * @param account
         * @param apiKey
         * @param widget
         * @param language
         * @param groupBy
         * @param hitCount
         * @param dev
         * @param test
         * @param customerId
         * @param {*} otherParameters
         * @returns {{widget: *, hitCount: number, apiKey: *, dev: boolean, test: boolean, profileId: (string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg), customerId: (string|*), language: *, sessionId: *, groupBy: *, parameters: {"User-Agent": string, "User-URL", "User-Referer": string}, username: *}}
         */
        getApiRequestData: function(account, apiKey, widget, language, groupBy, hitCount = 1, dev = false, test = false, customerId = null, otherParameters = {}) {
            var baseParameters = {
                'username': account,
                'apiKey': apiKey,
                'sessionId': this.getApiSessionId(),
                'profileId': this.getApiProfileId(),
                'customerId': this.getApiCustomerId(customerId),
                'widget': widget,
                'dev': dev,
                'test': test,
                'hitCount': hitCount,
                'language': language,
                'groupBy': groupBy,
                'parameters': {
                    'User-Referer': document.referrer,
                    'User-URL': window.location.href,
                    'User-Agent': navigator.userAgent
                }
            };

            return Object.assign({}, baseParameters, otherParameters);
        },

        /**
         * @public
         * @param url
         * @returns {string}
         */
        getApiRequestUrl: function(url) {
            return url + '?profileId=' + encodeURIComponent(this.getApiProfileId());
        },

        /**
         * @public
         * @returns {string|*}
         * @param customerId
         */
        getApiCustomerId: function(customerId = null) {
            if (customerId) {
                return atob(customerId);
            }

            return this.getApiProfileId();
        },

        /**
         * @public
         * @returns {string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg}
         */
        getApiProfileId: function() {
            return $.mage.cookies.get('cemv');
        },

        /**
         * @public
         * @returns {string|*|{}|DOMPoint|SVGTransform|SVGNumber|SVGLength|SVGPathSeg}
         */
        getApiSessionId: function() {
            return $.mage.cookies.get('cems');
        },
    });

    return $.rtuxApiHelper.js;
});
