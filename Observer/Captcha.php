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

namespace Mageplaza\GoogleRecaptcha\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Response\RedirectInterface;

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Captcha implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_responseInterface;
    /**
     * @var \Mageplaza\GoogleRecaptcha\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * @var ActionFlag
     */
    private $_actionFlag;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * Captcha constructor.
     * @param \Mageplaza\GoogleRecaptcha\Helper\Data $helperData
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\App\ResponseInterface $responseInterface
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     */
    public function __construct(
        HelperData $helperData,
        Http $request,
        ManagerInterface $messageManager,
        ActionFlag $actionFlag,
        ResponseInterface $responseInterface,
        RedirectInterface $redirect
    )
    {
        $this->_helperData        = $helperData;
        $this->_request           = $request;
        $this->messageManager     = $messageManager;
        $this->_actionFlag        = $actionFlag;
        $this->_responseInterface = $responseInterface;
        $this->redirect           = $redirect;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute(Observer $observer)
    {
        if ($this->_helperData->isEnabled() && $this->_helperData->isCaptchaFrontend()) {
            $checkResponse = true;
            foreach ($this->_helperData->getFormPostPaths() as $item) {
                if ($item != "" && strpos($this->_request->getRequestUri(), $item) !== false) {
                    $checkResponse = false;
                    if ($this->_request->getParam('g-recaptcha-response') !== null) {
                        $response = $this->_helperData->verifyResponse();
                        if (isset($response['success']) && !$response['success']) {
                            $this->redirectUrlError($response['message']);
                        }
                    } else {
                        $this->redirectUrlError(__('Missing required parameters recaptcha!'));
                    }
                }
            }
            if ($checkResponse && $this->_request->getParam('g-recaptcha-response') !== null) {
                $this->redirectUrlError(__('Missing Url in "Form Post Paths" configuration field!'));
            }
        }
    }

    public function redirectUrlError($message)
    {
        $this->messageManager->addErrorMessage($message);
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        $this->_responseInterface->setRedirect($this->redirect->getRefererUrl());
    }
}
