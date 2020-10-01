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

use Exception;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;

/**
 * Class Forgot
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Forgot implements ObserverInterface
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * Request object
     *
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var ResponseInterface
     */
    protected $_responseInterface;

    /**
     * @var ActionFlag
     */
    private $_actionFlag;

    /**
     * @var UrlInterface
     */
    protected $_urlInterface;

    /**
     * Forgot constructor.
     *
     * @param HelperData $helperData
     * @param RequestInterface $httpRequest
     * @param ManagerInterface $messageManager
     * @param ResponseInterface $responseInterface
     * @param ActionFlag $actionFlag
     * @param UrlInterface $urlInterface
     */
    public function __construct(
        HelperData $helperData,
        RequestInterface $httpRequest,
        ManagerInterface $messageManager,
        ResponseInterface $responseInterface,
        ActionFlag $actionFlag,
        UrlInterface $urlInterface
    ) {
        $this->_helperData = $helperData;
        $this->_request = $httpRequest;
        $this->_messageManager = $messageManager;
        $this->_responseInterface = $responseInterface;
        $this->_actionFlag = $actionFlag;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->_helperData->isCaptchaBackend()
            && $this->_request->getParam('g-recaptcha-response') !== null
            && in_array('backend_forgotpassword', $this->_helperData->getFormsBackend(), true)
        ) {
            $controller = $this->_urlInterface->getCurrentUrl();
            try {
                $response = $this->_helperData->verifyResponse('visible');
                if (!array_key_exists('success', $response) || empty($response['success'])) {
                    $this->redirectError($controller, $response['message']);
                }
            } catch (Exception $e) {
                $this->redirectError($controller, $e->getMessage());
            }
        }
    }

    /**
     * @param string $url
     * @param string $message
     */
    public function redirectError($url, $message)
    {
        $this->_messageManager->addErrorMessage($message);
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        $this->_responseInterface->setRedirect($url);
    }
}
