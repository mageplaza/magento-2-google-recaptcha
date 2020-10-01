<?php
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

namespace Mageplaza\GoogleRecaptcha\Model\System\Config\Source\Frontend;

use Magento\Framework\Module\Manager;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class Forms
 * @package Mageplaza\GoogleRecaptcha\Model\Config\Source
 */
class Forms implements ArrayInterface
{
    const TYPE_LOGIN = 'body.customer-account-login #login-form.form.form-login';
    const TYPE_CREATE = 'body.customer-account-create #form-validate.form-create-account';
    const TYPE_FORGOT = '#form-validate.form.password.forget';
    const TYPE_CONTACT = '#contact-form';
    const TYPE_EDITACCOUNT = '#form-validate.form.form-edit-account';
    const TYPE_PRODUCTREVIEW = '#review-form';
    const TYPE_AGEVERIFICATION = '#mpageverify-form';
    const TYPE_FORMSEXTENDED = [
        '.popup-authentication .form.form-login',
        '.checkout-index-index form[data-role=login]',
        '.onestepcheckout-index-index .form.form-login'
    ];

    /**
     * @var Manager
     */
    protected $_moduleManager;

    /**
     * Forms constructor.
     *
     * @param Manager $moduleManager
     */
    public function __construct(Manager $moduleManager)
    {
        $this->_moduleManager = $moduleManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getOptionHash() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getOptionHash()
    {
        $labels = [
            self::TYPE_LOGIN => __('Login'),
            self::TYPE_CREATE => __('Create User'),
            self::TYPE_FORGOT => __('Forgot Password'),
            self::TYPE_CONTACT => __('Contact Us'),
            self::TYPE_EDITACCOUNT => __('Edit Account'),
            self::TYPE_PRODUCTREVIEW => __('Product Review')
        ];
        if ($this->checkModuleEnable('Mageplaza_AgeVerification')) {
            $labels = array_merge($labels, [self::TYPE_AGEVERIFICATION => __('Age Verification')]);
        }

        return $labels;
    }

    /**
     * @return array
     */
    public function defaultForms()
    {
        $forms = [
            self::TYPE_LOGIN => 'customer/account/loginPost/',
            self::TYPE_CREATE => 'customer/account/createpost/',
            self::TYPE_FORGOT => 'customer/account/forgotpasswordpost/',
            self::TYPE_CONTACT => 'contact/index/post/',
            self::TYPE_EDITACCOUNT => 'customer/account/editPost/',
            self::TYPE_PRODUCTREVIEW => 'review/product/post/'
        ];
        if ($this->checkModuleEnable('Mageplaza_AgeVerification')) {
            $forms = array_merge($forms, [self::TYPE_AGEVERIFICATION => '']);
        }

        return $forms;
    }

    /**
     * @param $moduleName
     *
     * @return bool
     */
    public function checkModuleEnable($moduleName)
    {
        return $this->_moduleManager->isEnabled($moduleName);
    }
}
