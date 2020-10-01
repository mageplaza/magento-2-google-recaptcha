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

use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriter;
use Mageplaza\GoogleRecaptcha\Helper\Data as HelperData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Enable
 * @package Mageplaza\GoogleRecaptcha\Console\Adminhtml\Command
 */
class Enable extends Command
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var HelperData
     */
    protected $_configWriter;

    /**
     * Enable constructor.
     *
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
        $this->setName('mpgooglerecaptcha:backend:enable')
            ->setDescription(__('Enable backend captcha'));

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->helperData->isCaptchaBackend()) {
            $output->writeln(__('The captcha is enabled for your admin website.'));
        } else {
            $path = 'googlerecaptcha/backend/enabled';
            $this->_configWriter->save($path, '1');
            $output->writeln(__('The captcha backend has been successfully enabled. Please run the flush cache command again'));
        }
    }
}
