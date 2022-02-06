/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */
define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'mage/translate',
    'mage/url',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/model/messageList',
    'mage/validation'
], function ($, ko, Component, $t, url, customerData, messageList) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Schatzmann_CartSharing/form/cart-sharing-form',
            cartSharingUrl: ko.observable(false)
        },

        /** Init */
        initialize: function () {
            this._super();
        },

        /** Handle form submit */
        shareCart: function () {
            let formSelector = $('#cart-sharing-form');

            formSelector.validation()

            if (formSelector.valid()) {
                let shareCartUrl = url.build('shared/carts/create');
                $.ajax({
                    url: shareCartUrl,
                    type: 'POST',
                    dataType: 'json',
                    showLoader: true,
                    data: {
                        cart_sharing_key: $('input#cart-sharing-key').val()
                    },
                    complete: (response) => {
                        if (response.responseText) {
                            this.cartSharingUrl(response.responseText)
                            messageList.addSuccessMessage({ message: $t('Cart Sharing URL successfully created. You will be redirected to homepage in 15 seconds.') })
                            setTimeout(() => {
                                window.location.href = '/';
                            }, 15000)
                        } else {
                            messageList.addErrorMessage({ message: $t('Invalid Cart Sharing Key. Contact us for more information.') })
                        }
                    }
                });
            }
        }
    });
});
