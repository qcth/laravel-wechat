<?php


namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序模板库
 * Class AppTemplateWarehouse
 * @package qcth\laravel_open\plug
 */
class AppTemplateWarehouse extends Common {
    use TokenTrait,CurlTrait;


    //获取草稿箱内的所有临时代码草稿
    public function get_draft_list(){
        $url="https://api.weixin.qq.com/wxa/gettemplatedraftlist?access_token={$this->component_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);
    }

    //获取代码模版库中的所有小程序代码模版
    public function get_template_list(){
        $url="https://api.weixin.qq.com/wxa/gettemplatelist?access_token={$this->component_access_token()}";
        $result_data=$this->curl($url);

        return json_decode($result_data,true);
    }

    //将草稿箱的草稿选为小程序代码模版
    public function add_to_template($draft_id){
        $url="https://api.weixin.qq.com/wxa/addtotemplate?access_token={$this->component_access_token()}";

        $post_data['draft_id']=$draft_id;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //删除指定小程序代码模版
    public function del_template($template_id){
        $url="https://api.weixin.qq.com/wxa/addtotemplate?access_token={$this->component_access_token()}";

        $post_data['template_id']=$template_id;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //为授权的小程序帐号上传小程序代码
    public function upload_code($post_data){
        if(is_array($post_data)){
            $post_data=json_encode($post_data);
        }
        $url="https://api.weixin.qq.com/wxa/commit?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,$post_data);
        return json_decode($result_data,true);
    }
}
