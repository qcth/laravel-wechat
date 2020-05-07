<?php

namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * Class GetAccount 获取授权账号信息,包括基本信息及详细信息
 * @package qcth\laravel_open\plug
 */
class GetAccount extends Common {

    use CurlTrait,TokenTrait;


    /**
     * 获取商家的appid及令牌
     * $authorization_code 商家同意授权后,回调地址中携带的参数
     * authorizer_appid, authorizer_access_token,authorizer_refresh_token, func_info
     * @return mixed
     */
    public function basic_info($authorization_code){
        $url="https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$this->component_access_token()}";

        $post_data=array(
            'component_appid'=>$this->config['component']['component_appid'],
            'authorization_code'=>$authorization_code
        );

        $return_data=$this->curl($url,json_encode($post_data));

        return json_decode($return_data,true);

    }

    //6、1 获取或更新 公众平台 基本信息,该API用于获取授权方的基本信息，包括头像、昵称、帐号类型、认证类型、微信号、原始ID和二维码图片URL。
    //需要特别记录授权方的帐号类型，在消息及事件推送时，对于不具备客服接口的公众号，需要在5秒内立即响应；而若有客服接口，则可以选择暂时不响应，而选择后续通过客服接口来发送消息触达粉丝。
    public function detail_info($authorizer_appid){

        $url="https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$this->component_access_token()}";

        $post_data['component_appid']=$this->config['component']['component_appid'];
        $post_data['authorizer_appid']=$authorizer_appid;


        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);

    }


}