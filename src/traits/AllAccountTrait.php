<?php

namespace qcth\Wechat\Traits;


/**
 * 拉取已授权的部分账号或所有账号
 * Trait AllAccountTrait
 *
 */
trait AllAccountTrait {

    //$offset偏移量,从几开始拉取
    //$count 拉取的数量,最大一次能拉取500
    public function get_all_account_info($offset=0,$count=500){
        $url="https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_list?component_access_token={$this->component_access_token()}";

        $post_data['component_appid']=$this->config['component_appid'];
        $post_data['offset']=$offset;
        $post_data['count']=$count;

        $result_data=$this->curl($url,json_encode($post_data));
        $result_data=json_decode($result_data,true);

        return $result_data;


    }
}