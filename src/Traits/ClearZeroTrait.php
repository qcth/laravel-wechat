<?php

namespace Qcth\Wechat\Traits;

/**
 * 接口调用次数清零
 * Trait ClearZeroTrait
 *
 */
trait ClearZeroTrait {

    //公众平台超限清零,共有10次机会
    public function clear_public_zero(){
        $url="https://api.weixin.qq.com/cgi-bin/clear_quota?access_token={$this->authorizer_access_token()}";

        $post_data['appid']=$this->config['authorizer_appid'];

        $restlt_data=$this->curl($url,json_encode($post_data));

        $restlt_data=json_decode($restlt_data,true);

        return $restlt_data;
    }
    //第三方调用接口清零
    public function clear_component_zero(){
        $url="https://api.weixin.qq.com/cgi-bin/component/clear_quota?component_access_token={$this->component_access_token()}";

        $post_data['component_appid']=$this->config['component_appid'];

        $restlt_data=$this->curl($url,json_encode($post_data));

        $restlt_data=json_decode($restlt_data,true);

        return $restlt_data;
    }
}
