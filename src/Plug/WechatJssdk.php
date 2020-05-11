<?php

namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\GetRandStrTrait;
use Qcth\Wechat\Traits\JssdkTicketTrait;

/**
 * 微信分享
 * Class Jssdk
 * @package qcth\laravel_open\plug
 *
 * 前台引用 http://res.wx.qq.com/open/js/jweixin-1.4.0.js
 */
class WechatJssdk extends Common {
    use GetRandStrTrait,JssdkTicketTrait;


    //返回 jssdk 配置项
    public function jsapi_signature($url){

        $timestamp = time();

        //生成随机字符串
        $nonceStr=$this->get_rand_str(16);
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket={$this->get_jsapi_ticket()}&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appid"     => $this->config['weixin']['authorizer_appid'],
            "noncestr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );

        return $signPackage;

    }


}
