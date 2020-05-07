<?php

namespace qcth\Wechat\Traits;


/**
 * 获取jssdk  ticket
 * Trait JssdkTicketTrait
 * 
 */
trait JssdkTicketTrait {
    use TokenTrait, CurlTrait;
    //通过 商家的 access_token 获取 各自的 ticket
    public function get_jsapi_ticket(){
        if($this->config['jsapi_ticket_end_time']>time()){
            return $this->config['jsapi_ticket'];
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->authorizer_access_token().'&type=jsapi';

        $result_data = $this->curl( $url );
        $result_data = json_decode( $result_data, true );

        //获取失败
        if ( $result_data['errcode'] ) {
            return false;
        }

        //缓冲 jsapi_ticket
        $up_data=array(
            'jsapi_ticket'=>$result_data['ticket'],
            'jsapi_ticket_end_time'=>time()+$result_data['expires_in']-500,

        );
        M('weixin_open_authorizer_weixin_config')->where(array('authorizer_appid'=>$this->config['authorizer_appid']))->save($up_data);


        //返回ticket
        return $result_data['ticket'];


    }
}