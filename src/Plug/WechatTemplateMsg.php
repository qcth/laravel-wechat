<?php

namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 公众号　模板消息
 * Class TemplateMsg
 * @package qcth\laravel_open\plug
 */
class WechatTemplateMsg extends Common {
    use TokenTrait,CurlTrait;


    //第一步,设置所属行业
    public function set_industry(){
        $url="https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$this->authorizer_access_token()}";

        $post_data['industry_id1']=1;
        $post_data['industry_id2']=41;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);

    }
    //获取 行业信息
    public function get_industry(){

        $url="https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);


    }

    //添加模板
    //把模板库中的某个模板,设置到,我的模板列表中.并且返回模板ID
    //推荐客户状态变更通知    OPENTM204430030  VFNyjwjBTTnzNMJ1eFHdyzWn11GioCW5gETRqCX4a7g
    public function add_template($template_id_short='OPENTM204430030'){
        $url="https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$this->authorizer_access_token()}";

        $post_data['template_id_short']=$template_id_short;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);

    }

    //获取已添加的,我的模板
    public function my_all_template(){

        $url="https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$this->authorizer_access_token()}";


        $result_data=$this->curl($url);

        return json_decode($result_data,true);

    }

    //删除模板
    public function del_template($template_id){
        $url="https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token={$this->authorizer_access_token()}";

        $post_data['template_id']=$template_id;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //发送模板消息
	public function send_template_msg($data){
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,json_encode($data));

        return json_decode($result_data,true);

    }
	
}
