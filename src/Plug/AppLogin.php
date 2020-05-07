<?php

namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\CurlTrait;
use Qcth\Wechat\Traits\TokenTrait;

/**
 * 小程序登陆
 * Class AppLogin
 * @package qcth\laravel_open\plug
 */
class AppLogin extends Common {
    use CurlTrait,TokenTrait;


	//获取用户的基本信息的
	public function get_userinfo($code,$encryptedData,$iv) {

		//请求微信
		$data = $this->request($code,$encryptedData,$iv);

		return json_decode($data,TRUE);
	}
	
	
	//请求微信
	private function request($code,$encryptedData,$iv){


		$url="https://api.weixin.qq.com/sns/component/jscode2session?appid={$this->config['small']['authorizer_appid']}&js_code={$code}&grant_type=authorization_code&component_appid={$this->config['component']['component_appid']}&component_access_token={$this->component_access_token()}";
		
		$data=json_decode($this->curl($url),true);

		if(!$data['session_key']){
			return array('code'=>0,"msg"=>"微信解密ｋｅｙ错误");
		}


		// 解密
		return $this->decryptData($encryptedData, $iv, $data['session_key']);
		
	
	}
	
	
	
	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @param $data string 解密后的原文
	 * 
	 * @$sessionkey 通过code换取的　密钥
	 *	$appid  小程序appid    
	 * @return int 成功0，失败返回对应的错误码
	 */
	private function decryptData($encryptedData, $iv, $sessionKey) {

		if (strlen($sessionKey) != 24) {
			return FALSE;
		}
		$aesKey = base64_decode($sessionKey);
	
		if (strlen($iv) != 24) {
			return FALSE;
		}
		$aesIV = base64_decode($iv);
	
		$aesCipher = base64_decode($encryptedData);
	
		$result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
	
		$dataObj = json_decode($result);
		if ($dataObj == NULL) {
			return FALSE;
		}
		if ($dataObj -> watermark -> appid != $this->config['small']['authorizer_appid']) {
			return FALSE;
		}

		return $result;
		
	}

	public function tt(){
	    return '测试方法';
    }
}
