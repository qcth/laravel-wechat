<?php

namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;

use Qcth\Wechat\Traits\TokenTrait;

/**
 * 微信网页授权,获取用户微信信息
 * Class Oauth
 * @package qcth\laravel_open\plug
 */
class WechatOauth extends Common {
    use CurlTrait,TokenTrait;


    //静默授权, 只返出openid
    public function snsapi_base($callback_url='',$param='') {

        return $this->request( 'snsapi_base' ,$callback_url,$param);

    }

    /**
     * 用户点击授权
     * @param $callback_url 回调url
     * @param string $param  地址栏携带的参数
     * @param $is_weixin_info 为真时,返出用户微信详细信息,为假时,只返出openid
     * @return array|bool|mixed
     */

    public function snsapi_userinfo($callback_url='',$param='',$is_weixin_info=true) {
        if(empty($callback_url)){
            return false;
        }

        $data = $this->request( 'snsapi_userinfo',$callback_url,$param);

        //只返回 openid
        if(!$is_weixin_info){
            return $data;
        }
        //返回openid及用户微信详细信息
        return $this->openid_info($data);
    }
	
	//通过code,获取授权时access_token值
	private function request( $type,$callback_url,$param) {

        //请求微信地址后,跳回到 $callbak_url 后,携带code参数
        if(empty($_GET['code'])){
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->config['weixin']['authorizer_appid']}&redirect_uri={$callback_url}&response_type=code&scope={$type}&state={$param}&component_appid={$this->config['component']['component_appid']}#wechat_redirect";
            header( 'location:' . $url );die;

        }

        $url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=".$this->config['weixin']['authorizer_appid']."&code=".$_GET['code']."&grant_type=authorization_code&component_appid=".$this->config['component']['component_appid']."&component_access_token=".$this->component_access_token();


        $data=$this->curl( $url );

        return json_decode($data,true);

	}

    //如果是snsapi_userinfo,可以用openid获取用户的,微信详细信息
	private function openid_info($data){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
        $weixin_info = $this->curl( $url );

        return json_decode($weixin_info,true);

    }
	
	
	
	
}
