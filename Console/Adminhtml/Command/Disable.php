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

namespace Mageplaza\GoogleRecaptcha\Console\Adminhtml\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;
use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriter;
use Magento\Framework\App\ScopeInterface as ScopeConfigInterface;

class Disable extends Command
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ConfigWriter
     */
    protected $_configWriter ;

    /**
     * Disable constructor.
     * @param HelperData $helperData
     * @param ConfigWriter $configWriter
     * @param null $name
     */
    public function __construct(
        HelperData $helperData,
        ConfigWriter $configWriter,
        $name = null
    ) {
        $this->helperData = $helperData;
        $this->_configWriter = $configWriter;

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('mpgooglerecaptcha:backend:disable')
            ->setDescription('Disable backend captcha');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->helperData->isEnabled()) {
            $output->writeln('Module is not enabled for your website.');
        }
        else if (!$this->helperData->isCaptchaBackend()) {
            $output->writeln('The captcha is disable for your admin website.');
        }
        else {
            $path = 'googlerecaptcha/backend/enabled';
            $this->_configWriter->save($path, '0', $scope = ScopeConfigInterface::SCOPE_DEFAULT, $scopeId = 0);
            $output->writeln('The captcha backend has been successfully disabled. Please run the flush cache command again');
        }
    }
}
