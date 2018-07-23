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

namespace Mageplaza\GoogleRecaptcha\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\Plugin\AuthenticationException as PluginAuthenticationException;
use Magento\Framework\Phrase;

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Login implements ObserverInterface
{
    /**
     * @type \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Mageplaza\GoogleRecaptcha\Helper\Data
     */
    protected $_helperData;

    /**
     * Login constructor.
     * @param \Mageplaza\GoogleRecaptcha\Helper\Data $helperData
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        HelperData $helperData,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_helperData = $helperData;
        $this->_resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\Plugin\AuthenticationException
     */
    public function execute(Observer $observer)
    {
        if($this->_helperData->isEnabled()){
            if(in_array('backend_login', $this->_helperData->getFormsBackend())){
                $response = $this->_helperData->verifyResponse();
                if (isset($response['success']) && !$response['success']) {
                    throw new PluginAuthenticationException(
                        new Phrase($response['message'])
                    );
                }
            }
        }
    }
}
