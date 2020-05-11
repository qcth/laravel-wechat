<?php

namespace Qcth\Wechat\Traits;

use Carbon\Carbon;

/**
 * 获取jssdk  ticket
 * Trait JssdkTicketTrait
 *
 */
trait JssdkTicketTrait {
    use TokenTrait, CurlTrait;
    //通过 商家的 access_token 获取 各自的 ticket
    public function get_jsapi_ticket(){

        //判断jsapi_ticket 令牌 是否过期
        if(Carbon::parse($this->config['weixin']['jsapi_ticket_end_time'])->gt(Carbon::now())){
            return $this->config['weixin']['jsapi_ticket'];
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->authorizer_access_token().'&type=jsapi';

        $result_data = $this->curl( $url );
        $result_data = json_decode( $result_data, true );

        //获取失败
        if ( $result_data['errcode'] ) {
            return false;
        }

        //更新到数据中
        $this->config['weixin']->jsapi_ticket=$result_data['ticket'];
        $this->config['weixin']->jsapi_ticket_end_time=Carbon::now()->addSecond($result_data['expires_in']-500);

        $this->config['weixin']->save();

        //返回ticket
        return $result_data['ticket'];


    }
}
