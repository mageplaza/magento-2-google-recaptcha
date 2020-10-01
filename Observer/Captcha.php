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
use Magento\Framework\App\RequestInterface;
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
     * @var RequestInterface
     */
    protected $requestInterface;

    /**
     * Captcha constructor.
     *
     * @param HelperData $helperData
     * @param Http $request
     * @param ManagerInterface $messageManager
     * @param ActionFlag $actionFlag
     * @param ResponseInterface $responseInterface
     * @param RedirectInterface $redirect
     * @param RequestInterface $requestInterface
     */
    public function __construct(
        HelperData $helperData,
        Http $request,
        ManagerInterface $messageManager,
        ActionFlag $actionFlag,
        ResponseInterface $responseInterface,
        RedirectInterface $redirect,
        RequestInterface $requestInterface
    ) {
        $this->_helperData = $helperData;
        $this->_request = $request;
        $this->messageManager = $messageManager;
        $this->_actionFlag = $actionFlag;
        $this->_responseInterface = $responseInterface;
        $this->redirect = $redirect;
        $this->requestInterface = $requestInterface;
    }

    /**
     * @param Observer $observer
     *
     * @return array|void
     */
    public function execute(Observer $observer)
    {
        if ($this->_helperData->isEnabled() && $this->_helperData->isCaptchaFrontend()) {
            $checkResponse = 1;
            $captcha = false;
            if ($this->_request->getFullActionName() === 'wishlist_index_add') {
                return;
            }
            foreach ($this->_helperData->getFormPostPaths() as $item) {
                if ($item !== '' && strpos($this->_request->getRequestUri(), trim($item, ' ')) !== false) {
                    $checkResponse = 0;
                    $captcha = $this->_request->getParam('g-recaptcha-response');
                    // case ajax login
                    if ($item === 'customer/ajax/login' && !empty($captcha) && $this->_request->isAjax()) {
                        $formData = HelperData::jsonDecode($this->requestInterface->getContent());
                        if (array_key_exists('g-recaptcha-response', $formData)) {
                            $captcha = $formData['g-recaptcha-response'];
                        } else {
                            return $this->redirectUrlError(__('Missing required parameters recaptcha!'));
                        }
                    }
                    if (!empty($captcha)) {
                        $type = $this->_helperData->getRecaptchaType();
                        $response = $this->_helperData->verifyResponse($type);
                        if (isset($response['success']) && !$response['success']) {
                            $this->redirectUrlError($response['message']);
                        }
                    } else {
                        $this->redirectUrlError(__('Missing required parameters recaptcha!'));
                    }
                }
            }

            if ($checkResponse === 1 && $captcha !== false) {
                $this->redirectUrlError(__('Missing Url in "Form Post Paths" configuration field!'));
            }
        }
    }

    /**
     * @param string $message
     *
     * @return array
     */
    public function redirectUrlError($message)
    {
        if (strpos($this->_request->getRequestUri(), 'customer/ajax/login') !== false
            || strpos($this->_request->getRequestUri(), 'sociallogin/popup/forgot') !== false
        ) {
            return [
                'errors' => true,
                'message' => $message
            ];
        }
        if (strpos($this->_request->getRequestUri(), 'sociallogin/popup/create') !== false) {
            return [
                'success' > false,
                'message' => $message
            ];
        }

        $this->messageManager->getMessages(true);
        $this->messageManager->addErrorMessage($message);
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
        $this->_responseInterface->setRedirect($this->redirect->getRefererUrl());
    }
}
