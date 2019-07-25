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

namespace Mageplaza\GoogleRecaptcha\Observer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Captcha implements ObserverInterface
{
    /**
     * @var ResponseInterface
     */
    protected $_responseInterface;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var Http
     */
    protected $_request;

    /**
     * @var ActionFlag
     */
    private $_actionFlag;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * Captcha constructor.
     *
     * @param HelperData $helperData
     * @param Http $request
     * @param ManagerInterface $messageManager
     * @param ActionFlag $actionFlag
     * @param ResponseInterface $responseInterface
     * @param RedirectInterface $redirect
     */
    public function __construct(
        HelperData $helperData,
        Http $request,
        ManagerInterface $messageManager,
        ActionFlag $actionFlag,
        ResponseInterface $responseInterface,
        RedirectInterface $redirect
    ) {
        $this->_helperData = $helperData;
        $this->_request = $request;
        $this->messageManager = $messageManager;
        $this->_actionFlag = $actionFlag;
        $this->_responseInterface = $responseInterface;
        $this->redirect = $redirect;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->_helperData->isEnabled() && $this->_helperData->isCaptchaFrontend()) {
            $checkResponse = 1;

            foreach ($this->_helperData->getFormPostPaths() as $item) {
                if ($item !== '' && strpos($this->_request->getRequestUri(), trim($item, ' ')) !== false) {
                    $checkResponse = 0;
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
            if ($checkResponse === 1 && $this->_request->getParam('g-recaptcha-response') !== null) {
                $this->redirectUrlError(__('Missing Url in "Form Post Paths" configuration field!'));
            }
        }
    }

    /**
     * @param $message
     *
     * @return array
     */
    public function redirectUrlError($message)
    {
        if (strpos($this->_request->getRequestUri(), 'customer/ajax/login') !== false
            || strpos($this->_request->getRequestUri(), 'sociallogin/popup/forgot') !== false
        ) {
            return [
                'errors'  => true,
                'message' => $message
            ];
        }
        if (strpos($this->_request->getRequestUri(), 'sociallogin/popup/create') !== false) {
            return [
                'success' > false,
                'message' => $message
            ];
        }

        $this->messageManager->addErrorMessage($message);
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        $this->_responseInterface->setRedirect($this->redirect->getRefererUrl());
    }
}
