<?php


namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序隐私设置
 * Class AppPrivacy
 * @package qcth\laravel_open\plug
 */
class AppPrivacy extends Common {
    use TokenTrait,CurlTrait;


    //设置小程序隐私设置（是否可被搜索）
    public function set_search($status=1){
        $url="https://api.weixin.qq.com/wxa/changewxasearchstatus?access_token={$this->authorizer_access_token()}";

        $post_data['status']=$status; //1表示不可搜索，0表示可搜索

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //查询小程序当前隐私设置（是否可被搜索）

    public function get_search_status(){
        $url="https://api.weixin.qq.com/wxa/getwxasearchstatus?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        //1表示不可搜索，0表示可搜索
        return json_decode($result_data,true);
    }

}
