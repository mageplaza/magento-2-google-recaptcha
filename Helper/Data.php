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

use Mageplaza\Core\Helper\AbstractData as CoreHelper;

/**
 * Class Data
 *
 * @package Mageplaza\GoogleRecaptcha\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'googlerecaptcha';
    const BACKEND_CONFIGURATION = '/backend';
    const FRONTEND_CONFIGURATION = '/spending';

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
    public function getLanguageCode($storeId = null)
    {
        return $this->getConfigGeneral('language', $storeId);
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
}