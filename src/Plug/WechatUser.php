<?php

namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 会员管理
 * Class User
 * @package qcth\laravel_open\plug
 */
class WechatUser extends Common {
    use CurlTrait,TokenTrait;


    //设置备注名
    public function setRemark( $param ) {
        $url     = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=' . $this->authorizer_access_token();
        $content = $this->curl( $url, urldecode( json_encode( $this->urlencodeArray( $param ) ) ) );

        return json_decode($content,true);
    }

    //获取用户基本信息
    public function getUserInfo( $openid ) {
        $url     =  "https://api.weixin.qq.com/cgi-bin/user/info?openid={$openid}&lang=zh_CN&access_token=" . $this->authorizer_access_token();
        $content = $this->curl( $url );

        return json_decode($content,true);
    }

    //批量获取用户基本信息
    public function getUserInfoLists( $param ) {
        $url     =  'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . $this->authorizer_access_token();
        $content = $this->curl( $url, urldecode( json_encode( $this->urlencodeArray( $param ) ) ) );

        return json_decode($content,true);
    }

    //获取用户列表
    public function getUserLists( $next_openid = '' ) {
        $url     =  "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$this->authorizer_access_token}&next_openid={$next_openid}";
        $content = $this->curl( $url );

        return json_decode($content,true);
    }




}
