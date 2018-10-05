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

namespace Mageplaza\GoogleRecaptcha\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;
use Mageplaza\GoogleRecaptcha\Model\System\Config\Source\Frontend\Forms;

/**
 * Class Captcha
 * @package Mageplaza\GoogleRecaptcha\Block
 */
class Captcha extends Template
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var
     */
    private $_dataFormId;

    /**
     * @var array
     */
    private $actionName = [
        'customer_account_login',
        'customer_account_create',
        'customer_account_forgotpassword',
        'contact_index_index',
        'catalog_product_view',
        'customer_account_edit'
    ];

    /**
     * Captcha constructor.
     * @param Context $context
     * @param HelperData $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        array $data = []
    )
    {
        $this->_helperData = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getForms()
    {
        $useLogin          = false;
        $this->_dataFormId = $this->_helperData->getFormsFrontend();

        foreach ($this->_dataFormId as $item => $value) {
            switch ($value) {
                case Forms::TYPE_LOGIN:
                    $actionName = $this->actionName[0];
                    $useLogin   = true;
                    break;
                case Forms::TYPE_CREATE:
                    $actionName = $this->actionName[1];
                    break;
                case Forms::TYPE_FORGOT:
                    $actionName = $this->actionName[2];
                    break;
                case Forms::TYPE_CONTACT:
                    $actionName = $this->actionName[3];
                    break;
                case Forms::TYPE_PRODUCTREVIEW:
                    $actionName = $this->actionName[4];
                    break;
                case Forms::TYPE_CHANGEPASSWORD :
                    $actionName = $this->actionName[5];
                    break;
                default:
                    $actionName = '';
            }
            $this->unsetDataFromId($item, $actionName);
        }

        if ($useLogin) {
            if (!$this->_helperData->allowGuestCheckout()) {
                $this->_dataFormId[] = Forms::TYPE_FORMSEXTENDED[0];
            }
        }
        $data = array_merge($this->_helperData->getCssSelectors(), $this->_dataFormId);

        return json_encode($data);
    }

    /**
     * @param $item
     * @param $text
     */
    public function unsetDataFromId($item, $text)
    {
        if ($this->_request->getFullActionName() !== $text) {
            unset($this->_dataFormId[$item]);
        }
    }

    /**
     * @return mixed
     */
    public function getInvisibleKey()
    {
        return $this->_helperData->getInvisibleKey();
    }

    /**
     * @return array|mixed
     */
    public function getPositionFrontend()
    {
        return $this->_helperData->getPositionFrontend();
    }

    /**
     * @return array|mixed
     */
    public function isCaptchaFrontend()
    {
        return $this->_helperData->isCaptchaFrontend();
    }

    /**
     * @return mixed
     */
    public function getLanguageCode()
    {
        return $this->_helperData->getLanguageCode();
    }

    /**
     * @return array|mixed
     */
    public function getThemeFrontend()
    {
        return $this->_helperData->getThemeFrontend();
    }
}