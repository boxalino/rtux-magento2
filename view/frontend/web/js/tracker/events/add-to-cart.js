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
            /** add to cart for main product on PDP **/
            $('#product_addtocart_form').mage('validation', {
                radioCheckboxClosest: '.nested',
                submitHandler: function (form) {
                    let productId = $(form).find('input[name="product"]').val(),
                        priceBox = $('[data-role=priceBox][data-product-id='+productId+']');

                    /*global bxq */
                    bxq(['trackAddToBasket',
                        productId,
                        $(form).find('input[name="qty"]').val(),
                        $(priceBox).find('meta[itemprop="price"]')[0].content,
                        $(priceBox).find('meta[itemprop="priceCurrency"]')[0].content,
                    ]);

                    var widget = $(form).catalogAddToCart({
                        bindSubmit: false
                    });

                    widget.catalogAddToCart('submitForm', $(form));
                    return false;
                }
            });

            /** add to cart for products on listing  -- should be updated if the theme allows quick buy for configurable/grouped products **/
            $('[data-role=tocart-form]').mage('validation', {
                submitHandler: function (form) {
                    let productId = $(form).find('input[name="product"]').val(),
                        price = $('#product-price-'+ productId).data("price-amount");

                    /*global bxq */
                    bxq(['trackAddToBasket', productId, 1, price, $.boxalino.rtuxApiHelper.getCurrencyCode()]);
                }
            });
        }
    };
});
