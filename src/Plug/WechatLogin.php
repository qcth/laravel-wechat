<?php

namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\CurlTrait;

/**
 * 微信开放平台,登陆网站
 * Class Login
 * @package qcth\laravel_open\plug
 */
class WechatLogin extends Common {

    use CurlTrait;

    /**
     * $callback_url 回调URL
     * $state  用于保持请求和回调的状态，授权请求后原样带回给第三方。该参数可用于防止csrf攻击（跨站请求伪造攻击），建议第三方带上该参数，可设置为简单的随机数加session进行校验
     * $scope 应用授权作用域，拥有多个作用域用逗号（,）分隔，网页应用目前仅填写snsapi_login即可
     */
    private function request($callback_url,$state,$scope='snsapi_login'){

        if(isset($_GET['code']) && !empty($_GET['code'])){ //通过code获取 access_token openid unionid

            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->config['component']['login_appid']."&secret=".$this->config['component']['login_appsecret']."&code=".$_GET['code']."&grant_type=authorization_code";
            $weixin_data    = $this->curl( $url );

            $data=json_decode($weixin_data,true);
            if(isset( $data['errcode'] )){
                //如果有错误码，删除code 再次请求

                unset($_GET['code']);

                $this->request( $callback_url,$state);  //再次请求

            }else{
//				Array
//				(
//				    [access_token] => YlhGDhVR6ygN5LO-OZTnPoo0rB36S39CoM0iIRPolt9LMb0Fx0UuPdRz5DwJxxGrL0vO3Hi0Ji90TYAqYEBvNw
//				    [expires_in] => 7200
//				    [refresh_token] => zQCIgiN7aW2Y6f_fm0jhb51d85v1SVTI4wYPvW4yyc1k5ogq6y0xRfWj1cg7GQQAWTTBHuYjaax2TB0uF0eEYw
//				    [openid] => or62Jwvkvjf0kw9smEbj3W9eLGL0
//				    [scope] => snsapi_login
//				    [unionid] => oj60d0mOet_jjT2UawcorHrraz-Q
//				)
                return $data;
            }

        }

        $url="https://open.weixin.qq.com/connect/qrconnect?appid=".$this->config['component']['login_appid']."&redirect_uri=".urlencode($callback_url)."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";

        header( 'location:' . $url );
        exit;
    }

    //获取用户的基本信息的

    public function get_userinfo($callback_url,$state=0) {
        $data = $this->request($callback_url,$state);


        if ( $data !== false ) {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
            $res = $this->curl( $url );

            return json_decode($res,true);
        }

        return false;
    }



}
