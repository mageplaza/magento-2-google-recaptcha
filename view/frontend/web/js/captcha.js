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
            theme: "light",
            forms: []
        },
        captchaForm: [],
        /**
         * This method constructs a new widget.
         * @private
         */
        _create: function () {
            var self = this;
            var stop = 0;
            $(function () {
                var ID = setInterval(function () {
                    if (self.isLoadForm() && stop == 0) {
                        stop++;
                        self.createCaptcha();
                    }
                    clearInterval(ID);
                }, 1300);
            });
        },

        /**
         * Init Create reCaptcha
         */
        createCaptcha: function () {
            var self = this,
                number = 0,
                widgetIDCaptcha,
                checkSubmitType = 0,
                resetForm = 0;
            window.recaptchaOnload = function () {
                var forms = self.options.forms;
                forms.forEach(function (value) {
                    var element = $(value);

                    /**
                     * Create Widget
                     */
                    if (element.length > 0 && element.prop("tagName").toLowerCase() == 'form') {
                        //Multi ID
                        if (element.length > 1) {
                            element = element.first();
                        }
                        var divCaptcha = $('<div class="g-recaptcha"></div>');
                        divCaptcha.attr('id', element.attr('id') + '_recaptcha_' + number);
                        element.append(divCaptcha);

                        var target = element.attr('id') + '_recaptcha_' + number,
                            parameters = {
                                'sitekey': self.options.invisibleKey,
                                'size': 'invisible',
                                'callback': function (token) {
                                    if (element.valid() && token) {
                                        checkSubmitType = 1;
                                        element.submit();
                                    } else {
                                        grecaptcha.reset(resetForm);
                                        resetForm = 0;
                                    }
                                },
                                'theme': self.options.theme,
                                'badge': self.options.position,
                                'hl' : self.options.language
                            };
                        widgetIDCaptcha = grecaptcha.render(target, parameters);
                        self.captchaForm[widgetIDCaptcha] = target;
                        number++;

                        /**
                         * Check form submit
                         */
                        element.on('submit', function (event) {
                            var result = false;
                            if (checkSubmitType == 1) {
                                checkSubmitType = 0;
                                result = true;
                            } else {
                                $.each(self.captchaForm, function (form, value) {
                                    if (element.find('#' + value).length > 0) {
                                        grecaptcha.reset(form);
                                        grecaptcha.execute(form);
                                        resetForm = form;
                                        return false;
                                    }
                                });
                            }

                            return result;
                        });
                    }
                })
            };
            require(['https://www.google.com/recaptcha/api.js?onload=recaptchaOnload&render=explicit']);
        },

        /**
         * Is Load Library
         * @returns {boolean}
         */
        isLoadForm: function () {
            var forms = this.options.forms;
            var result = false;
            if (forms && forms.length > 0) {
                forms.forEach(function (element) {
                    if ($(element).length > 0 && $(element).prop("tagName").toLowerCase() == 'form') {
                        result = true;
                        return false;
                    }
                });
            }
            return result;
        }
    });

    return $.mageplaza.captcha;
});
