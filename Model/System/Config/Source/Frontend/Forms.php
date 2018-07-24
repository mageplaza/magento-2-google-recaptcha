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

use Magento\Framework\Option\ArrayInterface;
/**
 * Class Forms
 * @package Mageplaza\GoogleRecaptcha\Model\Config\Source
 */
class Forms implements ArrayInterface
{
    const TYPE_LOGIN = 'user_login';
    const TYPE_CREATE    = 'user_create';
    const TYPE_FORGOT = 'user_forgotpassword';
    const TYPE_CONTACT = 'contact_us';
    const TYPE_CHANGEPASSWORD = 'change_password';
    const TYPE_PRODUCTREVIEW = 'product_review';

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
        return [
            self::TYPE_LOGIN => __('Login'),
            self::TYPE_CREATE => __('Create User'),
            self::TYPE_FORGOT => __('Forgot Password'),
            self::TYPE_CONTACT => __('Contact Us'),
            self::TYPE_CHANGEPASSWORD => __('Change Password'),
            self::TYPE_PRODUCTREVIEW => __('Product Review')
        ];
    }
}