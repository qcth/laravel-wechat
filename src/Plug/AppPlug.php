<?php


namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序插件管理
 * Class AppPrivacy
 * @package qcth\laravel_open\plug
 */
class AppPlug extends Common {
    use TokenTrait,CurlTrait;


    //申请使用插件接口, 此接口用于小程序向插件开发者发起使用插件的申请。

    public function add_plug($plugin_appid){
        $url="https://api.weixin.qq.com/wxa/plugin?access_token={$this->authorizer_access_token()}";

        $post_data['action']="apply";
        $post_data['plugin_appid']=$plugin_appid; //插件appid

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //查询已添加的插件,此接口用于查询小程序目前已添加的插件（包括确认中、已通过、已拒绝、已超时状态）
    public function get_add_plug(){
        $url="https://api.weixin.qq.com/wxa/plugin?access_token={$this->authorizer_access_token()}";

        $post_data['action']="list";

        $result_data=$this->curl($url,json_encode($post_data));

        //插件状态status（1：申请中，2：申请通过，3：被拒绝；4：已超时）
        return json_decode($result_data,true);

    }

    //删除已添加的插件
    public function del_plug($plugin_appid){
        $url="https://api.weixin.qq.com/wxa/plugin?access_token={$this->authorizer_access_token()}";

        $post_data['action']="unbind";
        $post_data['plugin_appid']=$plugin_appid;

        $result_data=$this->curl($url,json_encode($post_data));

        //插件状态status（1：申请中，2：申请通过，3：被拒绝；4：已超时）
        return json_decode($result_data,true);

    }
}
