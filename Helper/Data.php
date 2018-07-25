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

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        CurlFactory $curlFactory
    )
    {
        parent::__construct($context, $objectManager, $storeManager);
        $this->_curlFactory = $curlFactory;
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
    public function verifyResponse($recaptcha = null)
    {
        $result = ['success' => false];

        $recaptcha = $recaptcha ?: $this->_request->getParam('g-recaptcha-response');
        if (!$recaptcha) {
            $result['message'] = __('The response parameter is missing.');

            return $result;
        }

        /** @var \Magento\Framework\HTTP\Adapter\Curl $curl */
        $curl = $this->_curlFactory->create();
        $curl->write(\Zend_Http_Client::POST, $this->getVerifyUrl(), '1.1', [], http_build_query([
            'secret' => $this->getVisibleSecretKey(),
            'remoteip' => $this->_request->getClientIp(),
            'response' => $recaptcha
        ]));

        try {
            $resultCurl = $curl->read();
            if (!empty($resultCurl)) {
                $responseBody = \Zend_Http_Response::extractBody($resultCurl);
                $responses = Data::jsonDecode($responseBody);

                if (isset($responses['success']) && $responses['success'] == true) {
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

        $curl->close();

        return $result;
    }

    /**
     * @return string
     */
    protected function getVerifyUrl()
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }
}