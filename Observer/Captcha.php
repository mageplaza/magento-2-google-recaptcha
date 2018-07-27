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

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Captcha implements ObserverInterface
{
	/**
	 * @var \Mageplaza\GoogleRecaptcha\Helper\Data
	 */
	protected $_helperData;

	/**
	 * @var \Magento\Framework\App\Request\Http
	 */
	protected $_request;
	protected $resultRedirectFactory;
	protected $messageManager;

	public function __construct(
		HelperData $helperData,
		\Magento\Framework\App\Request\Http $request,
		\Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
		\Magento\Framework\Message\ManagerInterface $messageManager
	)
	{
		$this->_helperData = $helperData;
		$this->_request = $request;
		$this->resultRedirectFactory = $resultRedirectFactory;
		$this->messageManager = $messageManager;
	}

	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return \Magento\Framework\Controller\Result\Redirect
	 */
	public function execute(Observer $observer)
	{
		if($this->_helperData->isEnabled() && $this->_helperData->isCaptchaFrontend()){
			foreach($this->_helperData->getFormPostPaths() as $item){
				if(strpos($this->_request->getRequestUri(), $item) !== false){

					if($this->_request->getParam('g-recaptcha-response')!== null)
					{
                        $response = $this->_helperData->verifyResponse();
                        if (isset($response['success']) && !$response['success']) {
                            $this->redirectUrlError($response['message']);
                        }
					}else{
						$this->redirectUrlError(__('Missing required parameters recaptcha!'));
					}

				}
			}
		}
	}
	public function redirectUrlError($message){
		$this->messageManager->addErrorMessage($message);
		return $this->resultRedirectFactory->create()->setRefererUrl();
	}
}
