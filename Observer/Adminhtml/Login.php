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

namespace Mageplaza\GoogleRecaptcha\Observer\Adminhtml;

use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\Plugin\AuthenticationException as PluginAuthenticationException;
use Magento\Framework\Phrase;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Login implements ObserverInterface
{
    /**
     * @type JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * Login constructor.
     *
     * @param HelperData $helperData
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        HelperData $helperData,
        JsonFactory $resultJsonFactory
    ) {
        $this->_helperData = $helperData;
        $this->_resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @param Observer $observer
     *
     * @throws PluginAuthenticationException
     */
    public function execute(Observer $observer)
    {
        if ($this->_helperData->isCaptchaBackend()
            && in_array('backend_login', $this->_helperData->getFormsBackend())
        ) {
            $response = $this->_helperData->verifyResponse('backend');
            if (isset($response['success']) && !$response['success']) {
                throw new PluginAuthenticationException(
                    new Phrase($response['message'])
                );
            }
        }
    }
}
