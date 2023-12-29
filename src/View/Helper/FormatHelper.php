<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\I18n\FrozenTime;
use Cake\View\Helper;
use Cake\Routing\Router;

class FormatHelper extends Helper
{
    public function formatCurrency($val, $currency_code): string
    {
        if($currency_code == 'VND')
            return number_format( (float)$val , 0 , '.' , ',' ) . ' ' . $currency_code;
        else
            return $currency_code . ' ' . number_format( (float)$val , 2 , '.' , ',' );
    }

    public function formatDiscount($val): string
    {
        return !empty($val) ? $val . '%' : '0%';
    }

    public function formatDate($date, $format = DATE_FORMAT_PHP)
    {
        if(empty($date) || !($date instanceof \DateTimeInterface)) return '';
        /** @var $date \DateTimeInterface */
        return $date->i18nFormat($format);
    }

    public function formatDateTime($date, $format = DATE_FORMAT_PHP. ' '. TIME_FORMAT_PHP)
    {
        if(empty($date) || !($date instanceof \DateTimeInterface)) return '';
        /** @var $date \DateTimeInterface */
        return $date->i18nFormat($format);
    }

    public function formatJson($value)
    {
        return json_encode($value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }
    /**
     * Des: render parameter in url in template
     * @param array $extend parameter wants to add in the url
     * @param array $remove_key parameter wants to delete in the url
     * @return string $param_url
     */
    public function renderParameterURL(array $extend = [], array $remove_key = []): string
    {
        $param_array = [];
        $queries = Router::getRequest()->getQuery();
        if (is_array($queries) && is_array($extend)) $param_array = array_merge($queries, $extend);
        if (!empty($param_array) && is_array($remove_key)) {
            foreach ($remove_key as $key) {
                unset($param_array[$key]);
            }
        }

        $param_url = '';
        if (!empty($param_array)) {
            $param_url = '?';
            foreach ($param_array as $key => $query) {
                $param_url .= $key . '=' . $query;
                if (!($key === array_key_last($param_array))) {
                    $param_url .= '&';
                }
            }
        }

        return $param_url;
    }
}
