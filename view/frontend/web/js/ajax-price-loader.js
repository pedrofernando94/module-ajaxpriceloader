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
            this.product_ids.forEach(function (product_id, index) {
                $.ajax({
                    type: "GET",
                    url: "/echainr_ajaxpriceloader/ajax/pricerender",
                    data: {
                        "product_id": product_id
                    },
                    success: function (data) {
                        if(data.price_box_html) {
                            $(".ajax-price-loader[data-product=" + product_id + "]").html(data.price_box_html);
                        }
                    }
                });
            });
        }

    });

    return $.mage.ajaxpriceloader;
});
