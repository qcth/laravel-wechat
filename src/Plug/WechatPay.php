<?php


namespace Qcth\Wechat\Plug;



use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\GetRandStrTrait;
use Qcth\Wechat\Traits\MakeSignTrait;
use Qcth\Wechat\Traits\XmlTrait;

/**
 * 微信支付
 * Class Pay
 * @package qcth\laravel_open\plug
 */
class WechatPay extends Common {
    use GetRandStrTrait,MakeSignTrait,XmlTrait,CurlTrait;


	//统一下单返回结果
	protected $order = [ ];
	/**
	 * 公众号支付
	 *
	 * @param $order
	 * $data说明
	 * $data['total_fee']=1;//支付金额单位分
	 * $data['body']='会员充值';//商品描述
	 * $data['out_trade_no']='会员充值';//定单号
	 */
	public function order_pay( $order ) {
		
		//获取订单号
		$res = $this->unifiedorder( $order );
		if ( $res['return_code'] != 'SUCCESS' ) {
			
			return '错误在：'.$res['return_msg'];
			
		}
		if ( ! isset( $res['result_code'] ) || $res['result_code'] != 'SUCCESS' ) {
			return '错误 ：'.$res['err_code_des'];
			
		}
		//组装前端 js 需要的数据
		$data['appId']     = $this->config['weixin']['authorizer_appid'];
		$data['timeStamp'] = time();
		$data['nonceStr']  = $this->get_rand_str(16);
		$data['package']   = "prepay_id=" . $res['prepay_id'];  //微信返回的订单号
		$data['signType']  = "MD5";
		$data['paySign']   = $this->make_sign( $data );
		
		//以下两个，为网站自己用，
		//内部订单号
		$data['out_trade_no']=$order['out_trade_no'];
		//微信订单号
		$data['weixin_order_number']=$res['prepay_id'];
		return $data;
		
	}

	//微信返回订单号  $data传商品名称，价格等
	private  function unifiedorder( $data ) {
		$data['appid']      = $this->config['weixin']['authorizer_appid'];
		$data['mch_id']     = $this->config['weixin']['mch_id'];
		//$data['notify_url'] = c( 'wechat.notify_url' );  //后期把回调地址，加到微信配置项数据库中
		$data['nonce_str']  = $this->get_rand_str( 16 );
		$data['trade_type'] = 'JSAPI';
        $data['spbill_create_ip']=get_client_ip();

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