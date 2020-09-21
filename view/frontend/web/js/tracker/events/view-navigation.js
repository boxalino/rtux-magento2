/**
 * View category/navigation track event for Boxalino RealTimeUserExperience
 * triggered only on category pages
 */
define([], function () {
    'use strict';
    return {

        supports(moduleName, controllerName, actionName) {
            return moduleName === 'catalog' && controllerName === 'category' && actionName === 'view';
        },

        execute() {
            const activeId = window.rtuxActiveId;
            if (!activeId) {
                console.warn('[Boxalino RTUX API Tracker Plugin] Category ID could not be found.');
                return;
            }

            /*global bxq */
            bxq(['trackCategoryView', activeId]);
        }
    };
});
