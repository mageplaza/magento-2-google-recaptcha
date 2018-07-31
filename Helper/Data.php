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
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\GoogleRecaptcha\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData as CoreHelper;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Mageplaza\GoogleRecaptcha\Model\System\Config\Source\Frontend\Forms as DefaultFormsPaths;

/**
 * Class Data
 *
 * @package Mageplaza\GoogleRecaptcha\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'googlerecaptcha';
    const BACKEND_CONFIGURATION = '/backend';
    const FRONTEND_CONFIGURATION = '/frontend';

    /**
     * @var CurlFactory
     */
    protected $_curlFactory;

    /**
     * @var \Mageplaza\GoogleRecaptcha\Model\System\Config\Source\Frontend\Forms
     */
    protected $_formPaths;

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        CurlFactory $curlFactory,
        DefaultFormsPaths $formPaths
    )
    {
        parent::__construct($context, $objectManager, $storeManager);
        $this->_curlFactory = $curlFactory;
        $this->_formPaths   = $formPaths;
    }

    /**
     * Backend
     */

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getVisibleKey($storeId = null)
    {
        return $this->getConfigGeneral('visible/api_key', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getVisibleSecretKey($storeId = null)
    {
        return $this->getConfigGeneral('visible/api_secret', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isCaptchaBackend($storeId = null)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->getConfigBackend('enabled', $storeId);
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getFormsBackend($storeId = null)
    {
        $data = $this->getConfigBackend('forms', $storeId);

        return explode(',', $data);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getSizeBackend($storeId = null)
    {
        return $this->getConfigBackend('size', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getThemeBackend($storeId = null)
    {
        return $this->getConfigBackend('theme', $storeId);
    }

    /**
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getConfigBackend($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(static::CONFIG_MODULE_PATH . static::BACKEND_CONFIGURATION . $code, $storeId);
    }

    /**
     * Frontend
     */

    /**
     * @param string $code
     * @param null $storeId
     * @return array|mixed
     */
    public function getConfigFrontend($code = '', $storeId = null)
    {
        $code = ($code !== '') ? '/' . $code : '';

        return $this->getConfigValue(static::CONFIG_MODULE_PATH . static::FRONTEND_CONFIGURATION . $code, $storeId);
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function isCaptchaFrontend($storeId = null)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->getConfigFrontend('enabled', $storeId);
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function getPositionFrontend($storeId = null)
    {
        return $this->getConfigFrontend('position', $storeId);
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function getThemeFrontend($storeId = null)
    {
        return $this->getConfigFrontend('theme', $storeId);
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getFormsFrontend($storeId = null)
    {
        $data = $this->getConfigFrontend('forms', $storeId);

        return explode(',', $data);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getInvisibleKey($storeId = null)
    {
        return $this->getConfigGeneral('invisible/api_key', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getInvisibleSecretKey($storeId = null)
    {
        return $this->getConfigGeneral('invisible/api_secret', $storeId);
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function getFormPostPaths($storeId = null)
    {
        $data = [];
        foreach ($this->_formPaths->defaultForms() as $key => $value) {
            if (in_array($key, $this->getFormsFrontend())) {
                $data[] = $value;
            }
        }
        $custom = explode("\n", str_replace("\r", "", $this->getConfigFrontend('custom/paths', $storeId)));

        if ($custom) {
            return $data;
        }

        return array_merge($data, $custom);
    }

    /**
     * @param null $storeId
     * @return array|mixed
     */
    public function getCssSelectors($storeId = null)
    {
        $data = $this->getConfigFrontend('custom/css', $storeId);

        return explode("\n", str_replace("\r", "", $data));
    }

    /**
     * General
     */

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getLanguageCode($storeId = null)
    {
        return $this->getConfigGeneral('language', $storeId);
    }

    /**
     * get reCAPTCHA server response
     *
     * @param null $recaptcha
     * @return array
     */
    public function verifyResponse($end = null, $recaptcha = null)
    {
        $result = ['success' => false];

        $recaptcha = $recaptcha ?: $this->_request->getParam('g-recaptcha-response');
        if (!$recaptcha) {
            $result['message'] = __('The response parameter is missing.');

            return $result;
        }
        try {
            $recaptchaClass = new \ReCaptcha\ReCaptcha($end ? $this->getVisibleSecretKey() : $this->getInvisibleSecretKey());
            $resp           = $recaptchaClass->verify($recaptcha, $this->_request->getClientIp());
            if ($resp) {
                if ($resp->isSuccess()) {
                    $result['success'] = true;

                } else {
                    $result['message'] = __('The request is invalid or malformed.');
                }
            } else {
                $result['message'] = __('The request is invalid or malformed.');
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
        }

        return $result;
    }
}