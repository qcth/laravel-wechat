<?php

namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * Class Check 授权登陆
 * @package qcth\laravel_open\plug
 */
class Check extends Common {

    use CurlTrait,TokenTrait;

    /**
     * 授权第三方平台
     * @param null $back_url  用户点击同意授权后, 跳回到 $back_url中.并带上认证参数
     * @param int $auth_type  要授权的帐号类型， 1则商户扫码后，手机端仅展示公众号、2表示仅展示小程序，3表示公众号和小程序都展示,如果为未制定，则默认小程序和公众号都展示
     */
    public function request_page($back_url=null,$auth_type=3){

        if($_SESSION['access_type']=='pc'){ // PC端授权
            //请求url PC端
            $url="https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->config['component']['component_appid']}&pre_auth_code={$this->pre_auth_code()}&redirect_uri={$back_url}&auth_type={$auth_type}";

        }else{  //在微信浏览器授权
            $url="https://mp.weixin.qq.com/safe/bindcomponent?action=bindcomponent&auth_type=3&no_scan=1&component_appid={$this->config['component']['component_appid']}&pre_auth_code={$this->pre_auth_code()}&redirect_uri={$back_url}&auth_type={$auth_type}#wechat_redirect";
        }


        header( 'location:' . $url );die;

    }


    /**
     * 3 获取预授权码
     * 跳转到微信授权页面,需要的参数
     * @return mixed
     */
    protected function pre_auth_code(){
        //判断预授权码是否过期

        if($this->config['pre_auth_code_end_time']>time()){
            return $this->config['pre_auth_code'];
        }
        //component_access_token 第三方平台access_token
        $url="https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$this->component_access_token()}";

        //第三方平台方appid
        $post['component_appid']=$this->config['component']['component_appid'];

        //返回json数据; {"pre_auth_code":"Cx_Dk6qiBE0Dmx4EmlT3oRfArPvwSQ-oa3NL_fwHM7VI08r52wazoZX2Rhpz1dEw","expires_in":600}
        $return_data=$this->curl($url,json_encode($post));
        //json 转 数组
        $return_data=json_decode($return_data,true);

        //更新到数据中
        $update=array('pre_auth_code'=>$return_data['pre_auth_code'],'pre_auth_code_end_time'=>time()+$return_data['expires_in']-500);
        M('weixin_open_component_config')->where(array('type'=>3))->setField($update);

        return $return_data['pre_auth_code'];
    }

}