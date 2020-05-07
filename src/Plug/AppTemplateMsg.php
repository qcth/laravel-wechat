<?php

namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序　模板消息
 * Class TemplateMsg
 * @package qcth\laravel_open\plug
 */
class AppTemplateMsg extends Common {
    use TokenTrait,CurlTrait;


    /**
     * 发送模板消息
     * $scenario_id 场景ID,为submit事件带上的formId；支付场景下，为本次支付的prepay_id
     * $openid 接收者（用户）的openid
     * $template_id 所需下发的模板消息的id
     * $data 模板内容
     *
     * $emphasis_keyword 模板需要放大的关键词，不填则默认无放大
     * $target_url 点击模板查看详情跳转页面，不填则模板无跳转
     *
     *  下发条件说明
     * 支付,当用户在小程序内完成过支付行为，可允许开发者向用户在7天内推送有限条数的模板消息（1次支付可下发3条，多次支付下发条数独立，互相不影响）

     * 提交表单,当用户在小程序内发生过提交表单行为且该表单声明为要发模板消息的，开发者需要向用户提供服务时，可允许开发者向用户在7天内推送有限条数的模板消息（1次提交表单可下发1条，多次提交下发条数独立，相互不影响）
     */

    public function send_template_msg($scenario_id,$openid,$template_id,$data,$target_url=null,$emphasis_keyword=null){

        if(empty($scenario_id) || empty($openid) || empty($template_id) || empty($data)){

            echo json_decode(array('code'=>0,'msg'=>'参数错误'));die;
        }

        $url     = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$this->authorizer_access_token()}";

        $data['touser']=$openid;
        $data['template_id']=$template_id;
        $data['form_id']=$scenario_id;
        $data['data']=$data;

        //可选项
        if($target_url){
            $data['page']=$target_url;
        }
        //可选项
        if($emphasis_keyword){
            $data['emphasis_keyword']=$emphasis_keyword;
        }



        $content = $this->curl( $url ,json_encode($data));

        echo  json_encode($content);
    }

	//获取小程序模板库标题列表
    public function get_template_lib($offset=0,$count=20){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list?access_token={$this->authorizer_access_token()}";

        $post_data['offset']=$offset;
        $post_data['count']=$count;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //获取模板库某个模板标题下关键词库
    //通过模板简称, 获取模板的详细信息,具体有几个字段
    public function get_template_info($template_number='AT0002'){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token={$this->authorizer_access_token()}";

        $post_data['id']=$template_number;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);

    }

    //组合模板并添加至帐号下的个人模板库
    public function add_template($template_number='AT0002',$filed=[3,4]){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token={$this->authorizer_access_token()}";

        $post_data['id']=$template_number; //模板编号
        $post_data['keyword_id_list']=$filed; //模板字段,最多10个

        $result_data=$this->curl($url,json_encode($post_data));

        //返出模板ID 类似 yb6LVKQKsKKUVX1n4uUKUw-cOmF0Cqk4YFtSrZMhWVk
        return json_decode($result_data,true);

    }
	
	//获取帐号下已存在的模板列表

    public function get_are_template($offset=0,$count=20){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token={$this->authorizer_access_token()}";

        $post_data['offset']=$offset;
        $post_data['count']=$count;

        $result_data=$this->curl($url,json_encode($post_data));


        return json_decode($result_data,true);


    }

    //删除帐号下的某个模板
    public function del_template($template_id='yb6LVKQKsKKUVX1n4uUKUw-cOmF0Cqk4YFtSrZMhWVk'){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/template/del?access_token={$this->authorizer_access_token()}";

        $post_data['template_id']=$template_id;

        $result_data=$this->curl($url,json_encode($post_data));


        return json_decode($result_data,true);

    }



}
