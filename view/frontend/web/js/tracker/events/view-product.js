/**
 * View product view track event for Boxalino RealTimeUserExperience
 */
define([], function () {
    'use strict';
    return {

        supports(moduleName, controllerName, actionName) {
            return moduleName === 'catalog' && controllerName === 'product' && actionName === 'view';
        },

        execute() {
            const activeId = window.rtuxActiveId;
            if (!activeId) {
                console.warn('[Boxalino RTUX API Tracker Plugin] Product ID could not be found.');
                return;
            }

            /*global bxq */
            bxq(['trackProductView', activeId]);
        }
    };
});
