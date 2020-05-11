<?php


namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序 代码管理
 * Class AppCode
 * @package qcth\laravel_open\plug
 */
class AppCode extends Common {
    use TokenTrait,CurlTrait;


    //为授权的小程序帐号上传小程序代码
    /*
        传参示例
        $ext_json['extAppid']="wxfbda2ef3ce210ed6";
        $ext_json['ext']=["domain"=>"https://app.hssy114.com"];
        $ext_json['extEnable']=true;

        $ext_str=json_encode($ext_json);

        $post_data['template_id']=2;
        $post_data['ext_json']="$ext_str";
        $post_data['user_version']="v2.1";
        $post_data['user_desc']="米椒互动,相互描述";

    */
    public function up_code($post_data){
        $url="https://api.weixin.qq.com/wxa/commit?access_token={$this->authorizer_access_token()}";



        $result_data=$this->curl($url,json_encode($post_data,JSON_UNESCAPED_UNICODE));

        return json_decode($result_data,true);
    }

    //获取体验小程序的体验二维码
    //二维码保存路径
    //$path page/index?action=1
    public function get_tester_qr($file_name,$path=null){
        if(is_null($path)){
            $url="https://api.weixin.qq.com/wxa/get_qrcode?access_token={$this->authorizer_access_token()}";
        }else{
            $url="https://api.weixin.qq.com/wxa/get_qrcode?access_token={$this->authorizer_access_token()}&path=".urlencode($path);
        }

        $result_data=$this->curl($url);
        return file_put_contents($file_name,$result_data);
    }

    //获取授权小程序帐号的可选类目, 该接口可获取已设置的二级类目及用于代码审核的可选三级类目。
    public function get_category(){
        $url="https://api.weixin.qq.com/wxa/get_category?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);

    }

    //将第三方提交的代码包提交审核（仅供第三方开发者代小程序调用）
    //返回审核编号 auditid
    public function submit_audit($post_data){
        $url="https://api.weixin.qq.com/wxa/submit_audit?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,json_encode($post_data,JSON_UNESCAPED_UNICODE));

        return json_decode($result_data,true);

    }


    //获取小程序的第三方提交代码的页面配置（仅供第三方开发者代小程序调用）
    public function get_page(){
        $url="https://api.weixin.qq.com/wxa/get_page?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);
    }

    //通过审核编号,查询审核状态
    public function get_audit_status($auditid){
        $url="https://api.weixin.qq.com/wxa/get_auditstatus?access_token={$this->authorizer_access_token()}";

        $post_data['auditid']=$auditid;

        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //查询最新一次提交的审核状态（仅供第三方代小程序调用）
    public function get_last_audit_status(){
        $url="https://api.weixin.qq.com/wxa/get_latest_auditstatus?access_token={$this->authorizer_access_token()}";
        $result_data=$this->curl($url);

        return json_decode($result_data,true);
    }
    //发布已通过审核的小程序（仅供第三方代小程序调用）
    public function send_release(){
        $url="https://api.weixin.qq.com/wxa/release?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,'{}');

        return json_decode($result_data,true);
    }

    //修改小程序线上代码的可见状态（仅供第三方代小程序调用）
    //$action 值 设置可访问状态，发布后默认可访问，close为不可见，open为可见
    public function change_visit_status($action){
        $url="https://api.weixin.qq.com/wxa/change_visitstatus?access_token={$this->authorizer_access_token()}";

        $post_data['action']=$action;
        $result_data=$this->curl($url,json_encode($post_data));

        return json_decode($result_data,true);
    }

    //小程序版本回退（仅供第三方代小程序调用）
    public function revert_code_release(){
        $url="https://api.weixin.qq.com/wxa/revertcoderelease?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);
    }

    //查询当前设置的最低基础库版本及各版本用户占比 （仅供第三方代小程序调用）
    public function get_proportion(){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/getweappsupportversion?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url,'{}');
        return json_decode($result_data,true);
    }

    //设置最低基础库版本（仅供第三方代小程序调用）
    public function set_lib_version($version='1.0.0'){
        $url="https://api.weixin.qq.com/cgi-bin/wxopen/setweappsupportversion?access_token={$this->authorizer_access_token()}";

        $post_data['version']=$version;
        $result_data=$this->curl($url,$post_data);
        return json_decode($result_data,true);
    }

    //小程序审核撤回,单个帐号每天审核撤回次数最多不超过1次，一个月不超过10次。
    public function unsubmit_audit(){
        $url="https://api.weixin.qq.com/wxa/undocodeaudit?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);

        return json_decode($result_data,true);

    }

    //分阶段发布接口
    public function gray_release($gray_percentage=1){
        $url="https://api.weixin.qq.com/wxa/grayrelease?access_token={$this->authorizer_access_token()}";

        $post_data['gray_percentage']=$gray_percentage; //灰度的百分比，1到100的整数

        $result_data=$this->curl($url,$post_data);
        return json_decode($result_data,true);

    }

    //取消分阶段发布
    public function revert_gray_release(){
        $url="https://api.weixin.qq.com/wxa/revertgrayrelease?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);
        return json_decode($result_data,true);

    }

    //查询当前分阶段发布详情
    public function get_gray_release(){
        $url="https://api.weixin.qq.com/wxa/getgrayreleaseplan?access_token={$this->authorizer_access_token()}";

        $result_data=$this->curl($url);
        return json_decode($result_data,true);

    }


}
