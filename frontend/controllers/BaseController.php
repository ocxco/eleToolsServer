<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class BaseController extends Controller
{
    public $enableCsrfValidation = false;

    const STATUS_SUCCESS   = 200;
    const STATUS_FAILED    = 400;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_ERROR     = 500;

    public $data = '';

    public $user = [
        'name'     => 'requestName',
        'password' => 'requestPwd',
    ];

    public function init()
    {
        parent::init();
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        if (empty($data['user']) || $data['user'] !== $this->user) {
            $this->responseJson('Request User Needed', null, self::STATUS_FORBIDDEN);
        }
        $this->data = $data['data'];
    }

    /**
     * @param string $msg 返回的消息.
     * @param array $data 返回的数据(如果有的话).
     * @param int $code 响应Code(0:成功,1:失败).
     * @param array $headers 额外的Header.
     *
     * @return void
     */
    public function responseJson($msg, $data = null, $code = self::STATUS_FAILED, $headers = array())
    {
        $return = [
            'code' => $code,
            'msg'  => $msg,
            'data' => ['data' => $data],
        ];
        foreach ($headers as $key => $header) {
            if ($key == 'Access-Control-Allow-Origin' && is_array($header)) {
                // 如果要允许多个域名跨域。
                $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
                if (in_array($origin, $header)) {
                    Yii::$app->response->headers->add($key, $origin);
                }
            } else {
                Yii::$app->response->headers->add($key, $header);
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = $code;
        Yii::$app->response->data = $return;
        Yii::$app->end();
    }

}
