<?php

namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 设置小程序“扫普通链接二维码打开小程序”能力
 * Class AppQrOpen
 * @package qcth\laravel_open\plug
 */
class AppQrOpen extends Common {
    use TokenTrait,CurlTrait;


    //增加或修改二维码规则
    public function add_save($post_data){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpadd?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //获取已设置的二维码规则
    public function get_qr(){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpget?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);
    }

    //获取校验文件名称及内容
    public function get_file(){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdownload?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,'{}');

        $result_data= json_decode($result_data,true);

        if($result_data['errcode']!=0){
            return false;
        }
        //写入根目录下,校验文件
        file_put_contents($result_data['file_name'],$result_data['file_content']);
    }

    //删除已设置的二维码规则
    public function del_qr($prefix){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdelete?access_token={$this->authorizer_access_token()}";

        $post_data['prefix']=$prefix;

        $result_data=$this->curl($url,json_encode($post_data));
        return json_decode($result_data,true);
    }

    //发布已设置的二维码规则
    public function send_qr($prefix){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumppublish?access_token={$this->authorizer_access_token()}";
        $post_data['prefix']=$prefix;

        $result_data=$this->curl($url,json_encode($post_data));
        return json_decode($result_data,true);
    }
}
