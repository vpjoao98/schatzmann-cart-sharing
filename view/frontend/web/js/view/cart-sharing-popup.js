/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */
define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'Schatzmann_CartSharing/js/model/cart-sharing-popup'
], function ($, ko, Component, cartSharingPopup) {
    'use strict';

    return Component.extend({
        modalWindow: null,

        defaults: {
            template: 'Schatzmann_CartSharing/cart-sharing-popup'
        },

        /** Init */
        initialize: function () {
            this._super();
        },

        /** Init popup window */
        setModalElement: function (element) {
            if (cartSharingPopup.modalWindow == null) {
                cartSharingPopup.createPopUp(element);
            }
        },

        /** Show popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal');
        }
    });
});
