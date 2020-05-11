<?php

namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\CurlTrait;

use Qcth\Wechat\Traits\TokenTrait;

/**
 * 针对 vue  授权
 * 微信网页授权,获取用户微信信息
 * Class Oauth
 * @package qcth\laravel_open\plug
 */
class WechatOauth extends Common {
    use CurlTrait,TokenTrait;


    //静默授权,只有 open_id
    public function snsapi_base($callback_url='',$code='',$param='') {

        //返回结果,可能是302跳转地址,也可能是 微信open_id,调用此接口,外边自行判断
        return  $this->request( 'snsapi_base' ,urlencode($callback_url),$param);

    }

    /**
     * 用户点击授权,返回微信用户信息
     * @param $callback_url 回调url
     * @param $code 前台,通过地址栏,获取code
     * @param string $param  跳转微信地址前携带的参数,微信跳回时,把此参数带回
     * @return array|bool|mixed
     */
    public function snsapi_userinfo($callback_url,$code='',$param='') {

        $data=$this->request( 'snsapi_userinfo',urlencode($callback_url),$code,$param);

        //返出跳转地址,让前台去跳转到微信
        if($data['code']==302){
            return $data;
        }
        //返回用户微信信息
        return ['code'=>0,'data'=>$this->openid_info($data['data'])];
    }

    //$auth_params 带上 code, state 以及 appid
    private function request( $type,$callback_url,$code,$param) {

        //如果前台没有传code,则返出跳转地址
        if(empty($code)){
            //请求微信地址后,跳回到 $callbak_url 后,携带code参数
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->config['weixin']['authorizer_appid']}&redirect_uri={$callback_url}&response_type=code&scope={$type}&state={$param}&component_appid={$this->config['component']['component_appid']}#wechat_redirect";
            return ['code'=>302,'redirect_url'=>$url];
        }

        $url="https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=".$this->config['weixin']['authorizer_appid']."&code=".$code."&grant_type=authorization_code&component_appid=".$this->config['component']['component_appid']."&component_access_token=".$this->component_access_token();

        $data=$this->curl( $url );

        return ['code'=>0,'data'=>json_decode($data,true)];

    }

    //如果是snsapi_userinfo,可以用openid获取用户的,微信详细信息
    private function openid_info($data){

        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
        $weixin_info = $this->curl( $url );

        return json_decode($weixin_info,true);

    }




}
