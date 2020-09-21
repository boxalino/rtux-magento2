/**
 * Purchase event track event for Boxalino RealTimeUserExperience
 * (done server-side)
 */
define([], function () {
    'use strict';
    return {

        supports(moduleName, controllerName, controllerAction) {
            return false;
        },

        execute() {}
    };
});
