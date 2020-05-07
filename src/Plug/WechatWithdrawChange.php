<?php


namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\GetRandStrTrait;
use Qcth\Wechat\Traits\MakeSignTrait;
use Qcth\Wechat\Traits\XmlTrait;

/**
 * 提现到零钱,但未测试
 * Class WithdrawChange
 * @package qcth\laravel_open\plug
 */
class WechatWithdrawChange extends Common {

    use GetRandStrTrait,MakeSignTrait,XmlTrait,CurlTrait;


    //提现
    public function send_money( $data,$openid,$price ,$desc) {
    
        $data['mch_appid']= $this->config['weixin']['authorizer_appid'];  //微信公众号appid
        $data['mchid']= $this->config['weixin']['mch_id'];  //微信支付分配的商户号
        $data['nonce_str'] =$this->get_rand_str( 16 );  //随机字符串，不长于32位
        $data['partner_trade_no']= '123456789'; //商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
        $data['openid']= $openid; //商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
        $data['amount']= $price; //企业付款金额，单位为分
        $data['desc']= $desc; // 企业付款操作说明信息。必填。
        $data['spbill_create_ip']= '203.171.235.238'; // 该IP同在商户平台设置的IP白名单中的IP没有关联，该IP可传用户端或者服务端的IP。

        $data['sign']= $this->make_sign( $data ); //签名

        $xml = $this->array_to_xml( $data );
        $res = $this->curl_post_ssl( "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers", $xml );

        return $this->xml_to_array( $res );

    }


}