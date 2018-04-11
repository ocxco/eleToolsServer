<?php

namespace frontend\module\translate;

use linslin\yii2\curl\Curl;
use yii\base\Exception;
use yii\web\HttpException;


/**
 * 百度翻译接口
 * Class Baidu
 * @package frontend\module\translate
 */
class Baidu extends TranslateBase
{

    public $baseUrl = 'http://api.fanyi.baidu.com/api/trans/vip/translate';

    protected static $configName = "baidu";

    private $params = [];

    public function init()
    {
        parent::init();
        $time = time();
        $this->params = [
            'appid' => $this->config['appid'],
            'salt'  => $time,
            'from'  => 'auto',
            'to'    => 'zh',
        ];
    }

    private function genSign()
    {
        if (empty($this->params['q'])) {
            throw new HttpException(500, '需要先设置关键词');
        }
        $sign = md5($this->params['appid'] . $this->params['q'] . $this->params['salt'] . $this->config['secret']);
        $this->params['sign'] = $sign;
        return $this;
    }

    public function setFrom($from = '')
    {
        if (!empty($from)) {
            $this->params['from'] = $from;
        }
        return $this;
    }

    public function setTo($to = '')
    {
        if (!empty($to)) {
            $this->params['to'] = $to;
        }
        return $this;
    }

    public function setQ($q)
    {
        if (empty($q)) {
            throw new Exception('关键词不能为空');
        }
        $this->params['q'] = $q;
        return $this;
    }

    public function translate($q)
    {
        $this->setQ($q)->setTo($this->toDetected($q));
        $this->genSign();
        $curl = new Curl();
        $response = $curl->setOption(
            CURLOPT_POSTFIELDS,
            http_build_query($this->params)
        )->post($this->baseUrl);
        return json_decode($response, true);
    }
}