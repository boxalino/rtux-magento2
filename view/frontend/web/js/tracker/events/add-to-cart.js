/**
 * Add to basket track event for Boxalino RealTimeUserExperience
 */
define([
    'jquery',
    'mage/mage',
    'Magento_Catalog/product/view/validation',
    'Magento_Catalog/js/catalog-add-to-cart'
], function ($) {
    'use strict';
    return {

        supports() {
            return true;
        },

        execute() {
            /** add to cart tracker for the product view main item **/
            $('button.tocart').on("click", function(e){
                var productInfo = $(this).closest('.product-info-main');
                if(productInfo.length)
                {
                    var productId = $(productInfo).find('input[name="product"]').val(),
                        priceBox = $('[data-role=priceBox][data-product-id=' + productId + ']'),
                        qty = $(productInfo).find('input[name="qty"]').val(),
                        price = $(priceBox).find('meta[itemprop="price"]')[0].content;

                    /*global bxq */
                    bxq(['trackAddToBasket',
                        parseInt(productId),
                        qty,
                        price,
                        $.boxalino.rtuxApiHelper.getCurrencyCode(),
                    ]);
                }
            });

            /** add to cart for products on listing  -- should be updated if the theme allows quick buy for configurable/grouped products **/
            $('[data-role=tocart-form]').mage('validation', {
                submitHandler: function (form) {
                     let productId = $(form).find('input[name="product"]').val(),
                     price = $('#product-price-'+ productId).data("price-amount");

                    var parameters={
                        "_n-name":$(form).closest('.bx-narrative')[0].dataset.bxNarrativeName,
                        "_v-uuid":$(form).closest('.bx-narrative')[0].dataset.bxVariantUuid
                    };

                    /*global bxq */
                    bxq(['trackAddToBasket', productId, 1, price, $.boxalino.rtuxApiHelper.getCurrencyCode(), parameters]);
                }
            });
        }
    };
});
