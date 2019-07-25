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

namespace Mageplaza\GoogleRecaptcha\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;
use Mageplaza\GoogleRecaptcha\Model\System\Config\Source\Forms as FormsAdmin;

/**
 * Class Captcha
 * @package Mageplaza\GoogleRecaptcha\Block\Adminhtml
 */
class Captcha extends Template
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * Captcha constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        array $data = []
    ) {
        $this->_helperData = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getVisibleKey()
    {
        return $this->_helperData->getVisibleKey();
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->_helperData->getSizeBackend();
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->_helperData->getThemeBackend();
    }

    /**
     * @return mixed
     */
    public function isCaptchaBackend()
    {
        return $this->_helperData->isCaptchaBackend();
    }

    /**
     * @return bool
     */
    public function showInForm()
    {
        $form = $this->_helperData->getFormsBackend();
        if (!empty($form)) {
            if ($this->_request->getFullActionName() == 'adminhtml_auth_login') {
                if (in_array(FormsAdmin::TYPE_LOGIN, $form)) {
                    return true;
                }
            } else {
                if ($this->_request->getFullActionName() == 'adminhtml_auth_forgotpassword') {
                    if (in_array(FormsAdmin::TYPE_FORGOT, $form)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getLanguageCode()
    {
        return $this->_helperData->getLanguageCode();
    }
}
