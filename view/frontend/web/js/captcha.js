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
    'jquery',
    'mage/translate'
], function ($,$t) {
    'use strict';

    $.widget('mageplaza.captcha', {
        options: {
            key: "",
            language: "en",
            position: "inline",
            theme: "light",
            forms: [],
            type: ""
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
            var self      = this,
                widgetIDCaptcha,
                sortEvent,
                number    = 0,
                resetForm = 0;

            window.recaptchaOnload = function () {
                //get form active
                var forms  = self.options.forms,
                    result = false;

                if (forms && forms.length > 0) {
                    forms.forEach(function (element) {
                        if (element === '.onestepcheckout-index-index .block-content .form.form-login') {
                            var checkOSC = setInterval(function () {
                                if ($(element).length === 2) {
                                    $(element).last().attr('id', 'Osccaptcha');
                                    result = true;
                                    self.processOscCaptcha(result, '#Osccaptcha');
                                    clearInterval(checkOSC);
                                }
                            }, 100);

                        } else if (element !== ''
                            && $(element).length > 0
                            && $(element).prop("tagName").toLowerCase() === 'form') {
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
                        var divCaptcha    = $('<div class="g-recaptcha"></div>');
                        var divAction     = $('.actions-toolbar');
                        var divError      = $('<div class="g-recaptcha-error"></div>');
                        var checkBox      = $('<input type="checkbox" style="visibility: hidden" data-validate="{required:false}" class="mage-error">');

                        divError.text($t('You need select captcha')).attr('style', 'display:none;color:red');
                        divCaptcha.attr('id', 'mp' + '_recaptcha_' + number);
                        checkBox.attr('name', 'mp' + '_recaptcha_' + number);


                        if (self.options.type === 'visible') {
                            if (element.attr('id') === 'mpageverify-form') {
                                element.find('.mpageverify-verify-action').before(divCaptcha).before(checkBox).before(divError);
                            } else {
                                element.find(divAction).first().before(divCaptcha).before(divError);
                            }
                            element.find(divAction).first().before(checkBox);
                        } else {
                            element.append(divCaptcha);
                        }

                        var target     = 'mp' + '_recaptcha_' + number,
                            parameters = {
                                'sitekey': self.options.key,
                                'size': 'invisible',
                                'callback': function (token) {
                                    if (token) {
                                        if (self.options.type === 'visible') {
                                            var name = element.find('.g-recaptcha').attr('id');

                                            $("input[name='" + name + "']").prop('checked', true);
                                        } else {
                                            self.stopSubmit = token;

                                            if (value === '#social-form-login'
                                                || value === '#social-form-create'
                                                || value === '#social-form-password-forget'
                                                || value === '.popup-authentication #login-form.form.form-login'
                                                || (value === '#review-form' && self.options.type === 'invisible')
                                            ) {
                                                buttonElement.trigger('click');
                                            } else {
                                                element.submit();
                                            }
                                        }
                                    } else {
                                        grecaptcha.reset(resetForm);
                                        resetForm = 0;
                                    }
                                },
                                'expired-callback': function () {
                                    if (self.options.type === 'visible') {
                                        var name = element.find('.g-recaptcha').attr('id');

                                        $("input[name='" + name + "']").prop('checked', false);
                                    }
                                },
                                'theme': self.options.theme,
                                'badge': self.options.position,
                                'hl': self.options.language
                            };

                        if (self.options.type === 'visible') {
                            parameters.size = self.options.size;
                        }
                        widgetIDCaptcha                   = grecaptcha.render(target, parameters);
                        self.captchaForm[widgetIDCaptcha] = target;
                        number++;

                        /**
                         * Check form submit
                         */
                        if (self.options.type !== 'visible') {
                            if (value === '#social-form-login'
                                || value === '#social-form-create'
                                || value === '#social-form-password-forget'
                                || value === '.popup-authentication #login-form.form.form-login'
                                || (value === '#review-form' && self.options.type === 'invisible')
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
                        } else {
                            if (value === '#social-form-login'
                                || value === '#social-form-create'
                                || value === '#social-form-password-forget'
                                || value === '.popup-authentication #login-form.form.form-login'
                            ) {
                                buttonElement.on('click', function (event) {
                                    var name  = element.find('.g-recaptcha').attr('id');
                                    var check = $("input[name='" + name + "']").prop('checked');

                                    if (!(element.validation() && element.validation('isValid')) && check === false) {
                                        $.each(self.captchaForm, function (form, value) {
                                            if (element.find('#' + value).length > 0) {
                                                self.showMessage(divError, 5000);
                                                grecaptcha.reset(form);
                                                event.preventDefault(event);
                                                event.stopImmediatePropagation();
                                            }
                                        });
                                    }
                                });
                            } else {
                                element.submit(function (event) {
                                    var name  = element.find('.g-recaptcha').attr('id');
                                    var check = $("input[name='" + name + "']").prop('checked');

                                    if (check === false) {
                                        $.each(self.captchaForm, function (form, value) {
                                            if (element.find('#' + value).length > 0) {
                                                self.showMessage(divError, 5000);
                                                buttonElement.attr('disabled', false);
                                                grecaptcha.reset(form);
                                                event.preventDefault(event);
                                                event.stopImmediatePropagation();
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });

                    for (var i = 1; i < number; i++){
                        var badge = $('#mp_recaptcha_' + i + ' .grecaptcha-badge');

                        badge.removeAttr("style");
                        badge.attr("style", $('#mp_recaptcha_0 .grecaptcha-badge').attr("style"));
                    }
                }
            };
            require(['//www.google.com/recaptcha/api.js?onload=recaptchaOnload&render=explicit']);
        },

        /**
         * compatible with form OSC
         */
        processOscCaptcha: function (result, value) {
            var self      = this,
                widgetIDCaptcha,
                sortEvent,
                resetForm = 0;

            if (result) {
                var element = $(value);

                /**
                 * Create Widget
                 */
                var buttonElement = element.find('button[type=button]').length > 0 ? element.find('button[type=button]') : element.find('button[type=submit]');
                var divCaptcha    = $('<div class="g-recaptcha"></div>');
                var divAction     = $('.actions-toolbar');
                var divError      = $('<div class="g-recaptcha-error"></div>');
                var checkBox      = $('<input type="checkbox" style="visibility: hidden" data-validate="{required:true}" class="mage-error">');
                divError.text('You need select captcha').attr('style', 'display:none;color:red');
                divCaptcha.attr('id', 'mp' + '_recaptcha_' + 'osc');
                checkBox.attr('name', 'mp' + '_recaptcha_' + 'osc');


                if (self.options.type === 'visible') {
                    element.find(divAction).before(divCaptcha).before(divError);
                    element.find(divAction).first().before(checkBox);
                } else {
                    element.append(divCaptcha);
                }

                var target     = 'mp' + '_recaptcha_' + 'osc',
                    parameters = {
                        'sitekey': self.options.key,
                        'size': 'invisible',
                        'callback': function (token) {
                            if (token) {
                                if (self.options.type === 'visible') {
                                    var name = element.find('.g-recaptcha').attr('id');
                                    $("input[name='" + name + "']").prop('checked', true);
                                } else {
                                    self.stopSubmit = token;
                                    buttonElement.trigger('click');
                                }
                            } else {
                                grecaptcha.reset(resetForm);
                            }
                        },
                        'expired-callback': function () {
                            if (self.options.type === 'visible') {
                                var name = element.find('.g-recaptcha').attr('id');
                                $("input[name='" + name + "']").prop('checked', false);
                            }
                        },
                        'theme': self.options.theme,
                        'badge': self.options.position,
                        'hl': self.options.language
                    };

                if (self.options.type === 'visible') {
                    parameters.size = self.options.size;
                }
                widgetIDCaptcha                   = grecaptcha.render(target, parameters);
                self.captchaForm[widgetIDCaptcha] = target;


                /**
                 * Check form submit
                 */
                if (self.options.type !== 'visible') {
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
                    buttonElement.on('click', function (event) {
                        var name  = element.find('.g-recaptcha').attr('id');
                        var check = $("input[name='" + name + "']").prop('checked');

                        if (!(element.validation() && element.validation('isValid')) && check === false) {
                            $.each(self.captchaForm, function (form, value) {
                                if (element.find('#' + value).length > 0) {
                                    self.showMessage(divError, 5000);
                                    grecaptcha.reset(form);
                                    event.preventDefault(event);
                                    event.stopImmediatePropagation();
                                }
                            });
                        }
                    });
                }

                var badge = $('#mp_recaptcha_' + 'osc' + ' .grecaptcha-badge');

                badge.removeAttr("style");
                badge.attr("style", $('#mp_recaptcha_osc .grecaptcha-badge').attr("style"));
            }
        },

        /**
         * Show error message
         */
        showMessage: function (el, timeDelay) {
            el.show();
            if (timeDelay <= 0) timeDelay = 5000;
            setTimeout(function () {
                el.hide();
            }, timeDelay);
        }
    });

    return $.mageplaza.captcha;
});
