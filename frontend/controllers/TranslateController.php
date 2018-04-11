<?php

namespace frontend\controllers;

use frontend\module\translate\Baidu;

/**
 * TranslateController
 */
class TranslateController extends BaseController
{

    public function actionIndex()
    {
        if (empty($this->data['q'])) {
            $this->responseJson('关键词不能为空!');
        }
        $res = $this->translate($this->data);
        $this->responseJson('success', $res, self::STATUS_SUCCESS);
    }

    private function translate($data)
    {
        if (empty($this->data['type'])) {
            $this->data['type'] = 'default';
        }
        switch ($this->data['type']) {
            case 'baidu':
                $dict = new Baidu();
                break;
            default:
                $dict = new Baidu();
                break;
        }
        $res = $dict->translate($data['q']);
        return $res;
    }

}
