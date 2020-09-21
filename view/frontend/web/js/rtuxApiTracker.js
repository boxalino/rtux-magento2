/**
 * RTUX API JS Tracker class
 * Sends tracking events to the Boxalino RTUX server
 */
define([
    'jquery',
    'Boxalino_RealTimeUserExperience/js/tracker/events/view-page',
    'Boxalino_RealTimeUserExperience/js/tracker/events/view-navigation',
    'Boxalino_RealTimeUserExperience/js/tracker/events/view-search',
    'Boxalino_RealTimeUserExperience/js/tracker/events/view-product',
    'Boxalino_RealTimeUserExperience/js/tracker/events/add-to-cart',
    'Boxalino_RealTimeUserExperience/js/tracker/events/login',
    'Boxalino_RealTimeUserExperience/js/tracker/events/purchase',
], function (
    $,
    ViewPageEvent,
    ViewNavigationEvent,
    ViewSearchEvent,
    ViewProductEvent,
    AddToCartEvent,
    LoginEvent,
    PurchaseEvent
){
    'use strict';

    $.widget('boxalino.rtuxApiTracker', {
        options: {
            isRestricted: true,
            activeId: null,
            currencyCode: null,
            controllerName: null,
            actionName: null,
            moduleName: null
        },

        /**
         * 1. initializes the tracker (sets the active account and required global parameters);
         * 2. registers the events (based on the declared dependencies)
         *
         * @private
         */
        _create: function () {
            if(!this.options.isRestricted) {
                (function (i, s, o, g, a, m) {
                    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
                    a.async = 1; a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', $.boxalino.rtuxApiHelper.isDev() ? '//r-st.bx-cloud.com/static/bav2.min.js' : '//track.bx-cloud.com/static/bav2.min.js');

                bxq(["setAccount", $.boxalino.rtuxApiHelper.getAccount()]);
                if($.boxalino.rtuxApiHelper.isTest()) {
                    bxq(['debugCookie', true]);
                }
                window.rtuxActiveId = this.options.activeId;
                window.currencyCode = this.options.currencyCode;
                this.events = [];

                this.registerDefaultEvents();
                this.handleEvents();
            }
        },

        /**
         * @public
         */
        handleEvents() {
            this.events.forEach(event => {
                if (!event.supports(this.options.moduleName, this.options.controllerName, this.options.actionName)) {
                    return;
                }
                event.execute();
            });
        },

        /**
         * @public
         */
        registerDefaultEvents() {
            this.registerEvent(ViewPageEvent);
            this.registerEvent(ViewProductEvent);
            this.registerEvent(ViewSearchEvent);
            this.registerEvent(ViewNavigationEvent);
            this.registerEvent(AddToCartEvent);
            this.registerEvent(LoginEvent);
            this.registerEvent(PurchaseEvent);
        },

        /**
         * @public
         * @param event
         */
        registerEvent(event) {
            this.events.push(event);
        },

        disableEvents() {
            this.events.forEach(event => {
                event.disable();
            });
        }
    });

    return $.boxalino.rtuxApiTracker;
});
