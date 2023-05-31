define([
    "jquery"
], function($) {
    "use strict";
    $.widget('mage.ajaxpriceloader', {

        product_ids: [],

        _create: function() {
            this.product_ids = [];
            this._bind();
        },

        _bind: function()  {
            this.getLoadedProductIds();
            this.loadAjaxPriceBoxes();
        },

        getLoadedProductIds: function () {
            let self = this;
            $(".ajax-price-loader").each(function () {
                self.product_ids.push($(this).data('product'));
            });
        },

        loadAjaxPriceBoxes: function () {
            let product_ids = this.product_ids.join(",");
            $.ajax({
                type: "GET",
                url: "/echainr_ajaxpriceloader/ajax/pricerender",
                cache: false,
                data: {
                    "product_ids": product_ids
                },
                success: function (data) {
                    data.forEach(function (item) {
                        $(".ajax-price-loader[data-product=" + item.product_id + "]")
                            .html(item.price_box_html)
                            .removeClass('loading');
                    });
                }
            });
        }

    });

    return $.mage.ajaxpriceloader;
});
