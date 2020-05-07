<?php


namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序 域名
 * 要操作的域名,必须在第三方平台中,注册的域名
 * Class AppDomain
 * @package qcth\laravel_open\plug
 */
class AppDomain extends Common {

    use TokenTrait,CurlTrait;


    //获取域名
    //当action为get时,无需传其它字段
    //$post_data['action']='get|add|delete|set'   //add添加, delete删除, set覆盖, get获取
    //$post_data['requestdomain']=['https://a.com',.......]
    //$post_data['wsrequestdomain']=['https://a.com',.......]
    //$post_data['uploaddomain']=['https://a.com',.......]
    //$post_data['downloaddomain']=['https://a.com',.......]
	public function set_domain($post_data){
        $url="https://api.weixin.qq.com/wxa/modify_domain?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);

    }


    //设置业务域名,当参数是get时不需要填webviewdomain字段
    //$post_data['action']='get|add|delete|set'      //add添加, delete删除, set覆盖, get获取
    //$post_data['webviewdomain']=['https://a.com','https://b.com'];
    public function set_webview_domain($post_data){
        $url="https://api.weixin.qq.com/wxa/setwebviewdomain?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

}
