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

namespace Mageplaza\GoogleRecaptcha\Model\System\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Language
 * @package Mageplaza\GoogleRecaptcha\Model\System\Config\Source
 */
class Language implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $languageOptionArray = [
            ['label' => __('-- Auto Detected --'), 'value' => 'en'],
            ['label' => __('Arabic'), 'value' => 'ar'],
            ['label' => __('Afrikaans'), 'value' => 'af'],
            ['label' => __('Amharic'), 'value' => 'am'],
            ['label' => __('Armenian'), 'value' => 'hy'],
            ['label' => __('Azerbaijani'), 'value' => 'az'],
            ['label' => __('Basque'), 'value' => 'eu'],
            ['label' => __('Bengali'), 'value' => 'bn'],
            ['label' => __('Bulgarian'), 'value' => 'bg'],
            ['label' => __('Catalan'), 'value' => 'ca'],
            ['label' => __('Chinese (Hong Kong)'), 'value' => 'zh-HK'],
            ['label' => __('Chinese (Simplified)'), 'value' => 'zh-CN'],
            ['label' => __('Chinese (Traditional)'), 'value' => 'zh-TW'],
            ['label' => __('Croatian'), 'value' => 'hr'],
            ['label' => __('Czech'), 'value' => 'cs'],
            ['label' => __('Danish'), 'value' => 'da'],
            ['label' => __('Dutch'), 'value' => 'nl'],
            ['label' => __('English (UK)'), 'value' => 'en-GB'],
            ['label' => __('English (US)'), 'value' => 'en'],
            ['label' => __('Estonian'), 'value' => 'et'],
            ['label' => __('Filipino'), 'value' => 'fil'],
            ['label' => __('Finnish'), 'value' => 'fi'],
            ['label' => __('French'), 'value' => 'fr'],
            ['label' => __('French (Canadian)'), 'value' => 'fr-CA'],
            ['label' => __('Galician'), 'value' => 'gl'],
            ['label' => __('Georgian'), 'value' => 'ka'],
            ['label' => __('German (Austria)'), 'value' => 'de-AT'],
            ['label' => __('German (Switzerland)'), 'value' => 'de-CH'],
            ['label' => __('Greek'), 'value' => 'el'],
            ['label' => __('Gujarati'), 'value' => 'gu'],
            ['label' => __('Hebrew'), 'value' => 'iw'],
            ['label' => __('Hindi'), 'value' => 'hi'],
            ['label' => __('Hungarain'), 'value' => 'hu'],
            ['label' => __('Icelandic'), 'value' => 'is'],
            ['label' => __('Indonesian'), 'value' => 'id'],
            ['label' => __('Italian'), 'value' => 'it'],
            ['label' => __('Japanese'), 'value' => 'ja'],
            ['label' => __('Kannada'), 'value' => 'kn'],
            ['label' => __('Korean'), 'value' => 'ko'],
            ['label' => __('Laothian'), 'value' => 'lo'],
            ['label' => __('Latvian'), 'value' => 'lv'],
            ['label' => __('Lithuanian'), 'value' => 'lt'],
            ['label' => __('Malay'), 'value' => 'ms'],
            ['label' => __('Malayalam'), 'value' => 'ml'],
            ['label' => __('Marathi'), 'value' => 'mr'],
            ['label' => __('Mongolian'), 'value' => 'mn'],
            ['label' => __('Norwegian'), 'value' => 'no'],
            ['label' => __('Persian'), 'value' => 'fa'],
            ['label' => __('Polish'), 'value' => 'pl'],
            ['label' => __('Portuguese'), 'value' => 'pt'],
            ['label' => __('Portuguese (Brazil)'), 'value' => 'pt-BR'],
            ['label' => __('Portuguese (Portugal)'), 'value' => 'pt-PT'],
            ['label' => __('Romanian'), 'value' => 'ro'],
            ['label' => __('Russian'), 'value' => 'ru'],
            ['label' => __('Serbian'), 'value' => 'sr'],
            ['label' => __('Sinhalese'), 'value' => 'si'],
            ['label' => __('Slovak'), 'value' => 'sk'],
            ['label' => __('Slovenian'), 'value' => 'sl'],
            ['label' => __('Spanish'), 'value' => 'es'],
            ['label' => __('Spanish (Latin America)'), 'value' => 'es-419'],
            ['label' => __('Swahili	'), 'value' => 'sw'],
            ['label' => __('Swedish'), 'value' => 'sv'],
            ['label' => __('Tamil'), 'value' => 'ta'],
            ['label' => __('Telugu'), 'value' => 'te'],
            ['label' => __('Thai'), 'value' => 'th'],
            ['label' => __('Turkish'), 'value' => 'tr'],
            ['label' => __('Ukrainian'), 'value' => 'uk'],
            ['label' => __('Urdu'), 'value' => 'ur'],
            ['label' => __('Vietnamese'), 'value' => 'vi'],
            ['label' => __('Zulu'), 'value' => 'zu'],
        ];

        return $languageOptionArray;
    }
}
