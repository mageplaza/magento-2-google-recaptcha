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
        activeForm: [],
        stopSubmit: false,
        /**
         * This method constructs a new widget.
         * @private
         */
        _create: function () {
            var self = this,
                stop = 0;
            $(function () {
                var ID = setInterval(function () {
                    if (stop === 0) {
                        stop++;
                        self.createCaptcha();
                    }
                    clearInterval(ID);
                }, 1500);
            });
        },

        /**
         * Init Create reCaptcha
         */
        createCaptcha: function () {
            var self = this,
                widgetIDCaptcha,
                sortEvent,
                number = 0,
                resetForm = 0;

            window.recaptchaOnload = function () {
                //get form active
                var forms = self.options.forms,
                    result = false;
                if (forms && forms.length > 0) {
                    forms.forEach(function (element) {
                        if (element !== '' && $(element).length > 0 && $(element).prop("tagName").toLowerCase() == 'form') {
                            self.activeForm.push(element);
                            result = true;
                        }
                    });
                }
                if (result) {
                    forms = self.activeForm;
                    forms.forEach(function (value) {
                        var element = $(value);
                        //Multi ID
                        if (element.length > 1) {
                            element = $(element).first();
                        }
                        /**
                         * Create Widget
                         */
                        var buttonElement = element.find('button[type=button]').length > 0 ? element.find('button[type=button]') : element.find('button[type=submit]');
                        var divCaptcha = $('<div class="g-recaptcha"></div>');
                        divCaptcha.attr('id', 'mp' + '_recaptcha_' + number);
                        element.append(divCaptcha);

                        var target = 'mp' + '_recaptcha_' + number,
                            parameters = {
                                'sitekey': self.options.invisibleKey,
                                'size': 'invisible',
                                'callback': function (token) {
                                    if (token) {
                                        self.stopSubmit = token;
                                        if (value === '#social-form-login'
                                            || value === '#social-form-create'
                                            || value === '#social-form-password-forget'
                                            || value === '.popup-authentication #login-form.form.form-login') {
                                            buttonElement.trigger('click');
                                        } else {
                                            element.submit();
                                        }

                                    } else {
                                        grecaptcha.reset(resetForm);
                                        resetForm = 0;
                                    }
                                },
                                'theme': self.options.theme,
                                'badge': self.options.position,
                                'hl': self.options.language
                            };
                        widgetIDCaptcha = grecaptcha.render(target, parameters);
                        self.captchaForm[widgetIDCaptcha] = target;
                        number++;

                        /**
                         * Check form submit
                         */
                        if (value === '#social-form-login'
                            || value === '#social-form-create'
                            || value === '#social-form-password-forget'
                            || value === '.popup-authentication #login-form.form.form-login'
                        ) {
                            buttonElement.on('click', function (event) {
                                if (!(element.validation() && element.validation('isValid'))) {
                                    return;
                                }

                                if (!self.stopSubmit) {
                                    $.each(self.captchaForm, function (form, value) {
                                        if (element.find('#' + value).length > 0) {

                                            grecaptcha.reset(form);
                                            grecaptcha.execute(form);
                                            resetForm = form;
                                            event.preventDefault(event);
                                            event.stopImmediatePropagation();

                                            return false;
                                        }
                                    });
                                }
                            });

                            sortEvent = $._data(buttonElement[0], 'events').click;
                            sortEvent.unshift(sortEvent.pop());

                        } else {
                            element.submit(function (event) {
                                if (!element.valid()) {
                                    return;
                                }
                                if (!self.stopSubmit) {
                                    $.each(self.captchaForm, function (form, value) {
                                        if (element.find('#' + value).length > 0) {

                                            grecaptcha.reset(form);
                                            grecaptcha.execute(form);
                                            resetForm = form;
                                            event.preventDefault(event);
                                            event.stopImmediatePropagation();

                                            return false;
                                        }
                                    });
                                }
                            });
                            sortEvent = $._data(element[0], 'events').submit;
                            sortEvent.unshift(sortEvent.pop());
                        }

                    });
                    for (var i = 1; i < number; i++) {
                        var badge = $('#mp_recaptcha_' + i + ' .grecaptcha-badge');
                        badge.removeAttr("style");
                        badge.attr("style", $('#mp_recaptcha_0 .grecaptcha-badge').attr("style"));
                    }
                }
            };
            require(['//www.google.com/recaptcha/api.js?onload=recaptchaOnload&render=explicit']);
        }
    });

    return $.mageplaza.captcha;
});
