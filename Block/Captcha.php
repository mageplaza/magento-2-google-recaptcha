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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Framework\View\Design\ThemeInterface;
use Magento\Framework\View\DesignInterface;
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
     * @var ThemeProviderInterface
     */
    protected $_themeProvider;

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
        'customer_account_edit',
        'multishipping_checkout_login',
        'multishipping_checkout_register',
        'checkout_index_index'
    ];

    /**
     * Captcha constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param ThemeProviderInterface $themeProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        ThemeProviderInterface $themeProvider,
        array $data = []
    ) {
        $this->_helperData = $helperData;
        $this->_themeProvider = $themeProvider;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getForms()
    {
        $useLogin = false;
        $this->_dataFormId = $this->_helperData->getFormsFrontend();

        foreach ($this->_dataFormId as $item => $value) {
            switch ($value) {
                case Forms::TYPE_LOGIN:
                    $actionName = $this->actionName[0];
                    $useLogin = true;
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
                case Forms::TYPE_EDITACCOUNT:
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

        if ($this->isAgeVerificationEnabled()) {
            $this->_dataFormId[] = Forms::TYPE_AGEVERIFICATION;
        }

        $actionName = $this->_request->getFullActionName();

        if ($actionName === $this->actionName[6]) {
            $this->_dataFormId[] = '.form.form-login';
        }
        if ($actionName === $this->actionName[7]) {
            $this->_dataFormId[] = '#form-validate.form-create-account';
        }
        if ($actionName === $this->actionName[8]) {
            $this->_dataFormId[] = Forms::TYPE_FORMSEXTENDED[1];
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
     * @return mixed
     */
    public function getVisibleKey()
    {
        return $this->_helperData->getVisibleKey();
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

    /**
     * @return array|mixed
     * @throws NoSuchEntityException
     */
    public function getRecaptchaType()
    {
        return $this->_helperData->getRecaptchaType($this->getStoreId());
    }

    /**
     * @param null $storeId
     *
     * @return array|mixed
     */
    public function isAgeVerificationEnabled($storeId = null)
    {
        return $this->_helperData->isAgeVerificationEnabled($storeId);
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return array|mixed
     */
    public function getSize()
    {
        return $this->_helperData->getSizeFrontend();
    }

    /**
     * @return string
     */
    public function getCurrentTheme()
    {
        $themeId = $this->_helperData->getConfigValue(DesignInterface::XML_PATH_THEME_ID);

        /**
         * @var $theme ThemeInterface
         */
        $theme = $this->_themeProvider->getThemeById($themeId);

        return $theme->getCode();
    }
}
