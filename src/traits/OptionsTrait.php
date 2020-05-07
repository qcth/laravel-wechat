<?php

namespace qcth\Wechat\Traits;


/**
 * 设置或获取 公众号或小程序的 选项设置
 * Trait OptionsTrait
 *
 */
trait OptionsTrait {

    //7、获取授权方的选项设置信息
    //该API用于获取授权方的公众号或小程序的选项设置信息，如：地理位置上报，语音识别开关，多客服开关。注意，获取各项选项设置信息，需要有授权方的授权，详见权限集说明。
    //location_report(地理位置上报选项) 0 无上报 1 进入会话时上报 2 每5s上报
    //voice_recognize（语音识别开关选项）0 关闭语音识别 1 开启语音识别
    //customer_service（多客服开关选项） 0 关闭多客服 1 开启多客服
    protected function get_authorizer_options($option_name){

        $url="https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_option?component_access_token={$this->component_access_token()}";

        $post_data['component_appid']=$this->config['component_appid'];
        $post_data['authorizer_appid']=$this->config['authorizer_appid'];
        $post_data['option_name']=$option_name;

        $result_data=$this->curl($url,json_encode($post_data));
        $result_data=json_decode($result_data,true);

        return $result_data;

    }

    //8、设置授权方的选项信息
    //location_report(地理位置上报选项) 0 无上报 1 进入会话时上报 2 每5s上报
    //voice_recognize（语音识别开关选项）0 关闭语音识别 1 开启语音识别
    //customer_service（多客服开关选项） 0 关闭多客服 1 开启多客服
    protected function set_authorizer_options($option_name,$option_value){

        $url="https://api.weixin.qq.com/cgi-bin/component/api_set_authorizer_option?component_access_token={$this->component_access_token()}";

        $post_data['component_appid']=$this->config['component_appid'];
        $post_data['authorizer_appid']=$this->config['authorizer_appid'];
        $post_data['option_name']=$option_name;
        $post_data['option_value']=$option_value;

        $result_data=$this->curl($url,json_encode($post_data));
        $result_data=json_decode($result_data,true);

        return $result_data;

    }
}