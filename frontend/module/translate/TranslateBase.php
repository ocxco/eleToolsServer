<?php

namespace frontend\module\translate;


use Yii;
use yii\base\BaseObject;
use yii\web\HttpException;

class TranslateBase extends BaseObject
{
    protected $baseUrl = '';

    protected $config = [];

    protected static $configName = '';

    public function init()
    {
        parent::init();
        if (empty(Yii::$app->params['translate'][static::$configName])) {
            throw new HttpException(500, '缺少配置');
        }
        $this->config = Yii::$app->params['translate'][static::$configName];
    }

    public function toDetected($str)
    {
        $to = 'zh';
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $str) > 0) {
            $to = 'en';
        }
        return $to;
    }

}