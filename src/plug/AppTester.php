<?php


namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序体验者
 * Class AppTester
 * @package qcth\laravel_open\plug
 */
class AppTester extends Common {
    use TokenTrait,CurlTrait;


    //绑定微信用户为小程序体验者
    //$weixin_number 微信号
    public function bind_tester($weixin_number){
        $url="https://api.weixin.qq.com/wxa/bind_tester?access_token={$this->authorizer_access_token()}";

        $post_data['wechatid']=$weixin_number;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);

    }

    //解除绑定小程序的体验者
    public function del_tester($weixin_number){
        $url="https://api.weixin.qq.com/wxa/unbind_tester?access_token={$this->authorizer_access_token()}";

        $post_data['wechatid']=$weixin_number;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //获取体验者列表
    public function get_tester(){
        $url="https://api.weixin.qq.com/wxa/memberauth?access_token={$this->authorizer_access_token()}";

        $post_data['action']='get_experiencer';

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }
}
