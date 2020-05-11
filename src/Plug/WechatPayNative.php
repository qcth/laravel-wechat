<?php

namespace Qcth\Wechat\Plug;

use Qcth\Wechat\Traits\GetRandStrTrait;
use Qcth\Wechat\Traits\MakeSignTrait;
use Qcth\Wechat\Traits\XmlTrait;

/**
 * 微信扫码支付
 * Class Pay_native
 * @package qcth\laravel_open\plug
 */
class WechatPayNative extends Common {
    use GetRandStrTrait,MakeSignTrait,XmlTrait;


    //统一下单返回结果
    protected $order = [ ];
    /**
     * 公众号支付
     *
     * @param $order_data
     * $order_data说明
     * $order_data['total_fee']=1;//支付金额单位分
     * $order_data['body']='会员充值';//商品描述
     * $order_data['out_trade_no']='会员充值';//定单号
     */
    public function order_pay( $data ) {

        //获取订单号
        $res = $this->unifiedorder( $data );
        if ( $res['return_code'] != 'SUCCESS' ) {

            return '错误在：'.$res['return_msg'];

        }
        if ( ! isset( $res['result_code'] ) || $res['result_code'] != 'SUCCESS' ) {
            return '错误 ：'.$res['err_code_des'];

        }

        //内部订单号
        $data['out_trade_no']=$data['out_trade_no'];
        //微信订单号
        $data['weixin_order_number']=$res['prepay_id'];
        //待付款ｕｒｌ
        $data['code_url']=$res['code_url'];
        return $data;

    }

    //微信返回订单号  $data传商品名称，价格等
    private  function unifiedorder( $data ) {
        $data['appid']      = $this->config['weixin']['authorizer_appid'];
        $data['mch_id']     = $this->config['weixin']['mch_id'];
        //$data['notify_url'] = c( 'wechat.notify_url' );  //后期把回调地址，加到微信配置项数据库中
        $data['nonce_str']  = $this->get_rand_str( 16 );
        $data['trade_type'] = 'NATIVE';


        $data['sign']       = $this->make_sign( $data );
        $xml                = $this->array_to_xml( $data );


        $res                = $this->curl( "https://api.mch.weixin.qq.com/pay/unifiedorder", $xml );

        return $this->xml_to_array( $res );

    }

    //支付成功后的通知信息
    public function getNotifyMessage() {

        return $this->xml_to_array( file_get_contents("php://input") );
    }
}
