/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return {
        modalWindow: null,

        /**
         * Create popUp window for provided element
         *
         * @param {HTMLElement} element
         */
        createPopUp: function (element) {
            var options = {
                'type': 'popup',
                'modalClass': 'popup-cart-sharing',
                'responsive': true,
                'innerScroll': true,
                'trigger': '.share-cart',
                'buttons': []
            };

            this.modalWindow = element;
            modal(options, $(this.modalWindow));
        },

        /** Show cart sharing popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal').trigger('contentUpdated');
        }
    };
});
