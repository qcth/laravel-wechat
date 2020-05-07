<?php

namespace qcth\Wechat\Traits;


/**
 * 获取第三方平台, access_token 或 公众平台或小程序的 access_token
 * Trait TokenTrait
 * 
 */
trait TokenTrait {

    //2 获取第三方平台component_access_token 有效期为2小时,缓冲到数据库
    //第三方平台通过自己的component_appid（即在微信开放平台管理中心的第三方平台详情页中的AppID和AppSecret）和component_appsecret，以及component_verify_ticket（每10分钟推送一次的安全ticket）来获取自己的接口调用凭据（component_access_token）
    protected function component_access_token(){

        //判断令牌是否过期

        if($this->config['component']['component_access_token_end_time']>time()){
            return $this->config['component']['component_access_token'];
        }

        $url="https://api.weixin.qq.com/cgi-bin/component/api_component_token";

        //第三方平台appid
        $post['component_appid']=$this->config['component']['component_appid'];
        //第三方平台appsecret
        $post['component_appsecret']=$this->config['component']['component_appsecret'];
        //微信后台推送的ticket，此ticket会定时推送，具体请见本页的推送说明
        $post['component_verify_ticket']=$this->config['component']['component_verify_ticket'];

        //返回 {"component_access_token":"61W3mEpU66027wgNZ_MhGHNQDHnFATkDa9-2llqrMBjUwxRSNPbVsMmyD-yq8wZETSoE5NQgecigDrSHkPtIYA", "expires_in":7200}
        $return_data=$this->curl($url,json_encode($post));
        //json转数组
        $return_data= json_decode($return_data,true);

        if(empty($return_data['component_access_token'])){
            return false;
        }

        //更新到数据中
        $update=array('component_access_token'=>$return_data['component_access_token'],'component_access_token_end_time'=>time()+$return_data['expires_in']-500);
        M('weixin_open_component_config')->where(array('type'=>3))->setField($update);

        return $return_data['component_access_token'];
    }
    //5、获取（刷新）授权公众号或小程序的接口调用凭据（令牌）
    protected function authorizer_access_token(){

        //判断component_access_token 令牌 是否过期
        if($this->config['component']['authorizer_access_token_end_time']>time()){
            return $this->config['component']['authorizer_access_token'];
        }

        //刷新 authorizer_access_token
        $url="https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token={$this->component_access_token()}";

        $post_data['component_appid']=$this->config['component']['component_appid'];
        $post_data['authorizer_appid']=$this->config[$this->type_name]['authorizer_appid'];
        $post_data['authorizer_refresh_token']=$this->config[$this->type_name]['authorizer_refresh_token'];

        $result_data=$this->curl($url,json_encode($post_data));
        $result_data=json_decode($result_data,true);
        if(empty($result_data['authorizer_access_token'])||empty($result_data['authorizer_refresh_token'])){
            return false;
        }
        $update_data['authorizer_access_token']=$result_data['authorizer_access_token'];
        $update_data['authorizer_refresh_token']=$result_data['authorizer_refresh_token'];
        $update_data['authorizer_access_token_end_time']=time()+$result_data['expires_in']-500;

        M('weixin_open_authorizer_'.$this->type_name.'_config')->where(array('authorizer_appid'=>$this->config[$this->type_name]['authorizer_appid']))->save($update_data);

        //返出刷新后的商家令牌
        return $result_data['authorizer_access_token'];
    }


}