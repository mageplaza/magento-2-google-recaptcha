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
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\UrlInterface;

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Forgot implements ObserverInterface
{
    /**
     * @var \Mageplaza\GoogleRecaptcha\Helper\Data
     */
    protected $_helperData;

    /**
     * Request object
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_responseInterface;

    /**
     * @var ActionFlag
     */
    private $_actionFlag;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * Forgot constructor.
     * @param \Mageplaza\GoogleRecaptcha\Helper\Data $helperData
     * @param \Magento\Framework\App\RequestInterface $httpRequest
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\ResponseInterface $responseInterface
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\UrlInterface $urlInterface
     */
    public function __construct(
        HelperData $helperData,
        RequestInterface $httpRequest,
        ManagerInterface $messageManager,
        ResponseInterface $responseInterface,
        ActionFlag $actionFlag,
        UrlInterface $urlInterface
    )
    {
        $this->_helperData = $helperData;
        $this->_request = $httpRequest;
        $this->_messageManager = $messageManager;
        $this->_responseInterface = $responseInterface;
        $this->_actionFlag = $actionFlag;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute(Observer $observer)
    {
        if($this->_helperData->isEnabled()
            && $this->_helperData->isCaptchaBackend()
            && in_array('backend_forgotpassword', $this->_helperData->getFormsBackend())
        ){
                if($this->_request->getParam('g-recaptcha-response')!== null)
                {
                    $controller = $this->_urlInterface->getCurrentUrl();
                    try {
                        $response = $this->_helperData->verifyResponse('backend');
                        if (isset($response['success']) && !$response['success']) {
                            $this->redirectError($controller, $response['message']);
                        }
                    } catch (\Exception $e) {
                        $this->redirectError($controller, $e->getMessage());
                    }
                }
        }
    }
    public function redirectError($url, $message){
        $this->_messageManager->addErrorMessage($message);
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        $this->_responseInterface->setRedirect($url);
    }
}
