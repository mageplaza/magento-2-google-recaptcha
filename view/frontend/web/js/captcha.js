/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_GoogleRecaptcha
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mageplaza.captcha', {
        options: {
            invisibleKey: "",
            language: "en",
            position: "inline",
            forms: []
        },

        /**
         * This method constructs a new widget.
         * @private
         */
        _create: function () {
            var self = this;

            $(function(){
                var ID = setInterval(function () {
                    if (self.isLoadForm()) {
                        self.createCaptcha();
                    }
                    clearInterval(ID);
                }, 1500);
            });
        },
        load: function (url) {
            $.ajax({
                url: url,
                dataType: 'script',
                async: true,
                defer: true
            });
        },
        createCaptcha: function () {



                    window.recaptchaOnload = function () {
                        var self = this;
                        var forms = this.options.forms;
                        forms.forEach(function (element) {
                            if($(element).length > 0) {
                                var divCaptcha = $('<div class="mageplaza_captcha"></div>');
                                $(element).append(divCaptcha);
                                var target = $(element).find(".mageplaza_captcha"),
                                    parameters = {
                                        'sitekey': this.options.invisibleKey,
                                        'size': 'invisible',
                                        'theme': 'dark',
                                        'badge': this.options.position,
                                        'callback': this.onSubmit.bind(this)
                                    };
                                grecaptcha.render(target, parameters);
                            }
                        })
                    };
            self.load('https://www.google.com/recaptcha/api.js?hl=' + self.options.language+'&onload=recaptchaOnload&render=explicit');
        },
        onSubmit: function (token) {
            console.log(token);
        },

        /**
         * Nếu tìm thấy một from thì load library
         * @returns {boolean}
         */
        isLoadForm: function () {
            var forms = this.options.forms;
            var flag = false;
            if(forms && forms.length > 0){
                forms.forEach(function (element) {
                    if($(element).length > 0 && $(element).prop("tagName").toLowerCase() == 'form'){
                            flag = true;
                            return false;
                    }
                });
            }
            return flag;
        }
    });

    return $.mageplaza.captcha;
});
