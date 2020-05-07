<?php

namespace Qcth\Wechat\Plug;


/**
 * Class Encrypt 解密
 * @package qcth\laravel_open\plug
 */
class Decrypt extends Common {


    /**
     * 解密xml
     * @param null $post_data  要解密的 post数据
     * @param null $url_param  地址栏参数
     * @return bool|\SimpleXMLElement  返回解密后的 xml 对象
     */
    public function decrypt_xml($post_data=null,$url_param=null){

        //微信post提交到第三方平台的数据,不能为空
        if(is_null($post_data)){
            return false;
        }
        //微信请求第三方地址是,携带的get参数,不能为空
        if(is_null($url_param)){
            return false;
        }


        //-----------------配置参数  start--------------------------
        $encodingaeskey=$this->config['component']['msg_key'];
        $token=$this->config['component']['msg_token'];
        $appid=$this->config['component']['component_appid'];
        $timestamp  = $url_param['timestamp'];
        $nonce = $url_param["nonce"];
        $msg_signature  = $url_param['msg_signature'];

        //-----------------配置参数  end--------------------------

        //------------- 提取密文字符串 start---------------------------

        //转成xml对象
        $xml_obj=simplexml_load_string( $post_data, 'SimpleXMLElement', LIBXML_NOCDATA );
        //密文字符串
        $encrypt=$xml_obj->Encrypt;

        //------------- 提取密文字符串 end---------------------------


        //-------------验证签名 start-----------------------------

        //验证安全签名，验证成功，返回真，失败时，返回假
        $validata=$this->getSHA1($token, $timestamp, $nonce, $encrypt);

        if($validata!=$msg_signature){

            return false;
        }

        //-------------验证签名 end-----------------------------

        //解密后的字符串
        $de_str= $this->decrypt($encrypt, $appid,$encodingaeskey);

        //解密后的字符串,转 xml 对象, 并返回
        return  simplexml_load_string( $de_str, 'SimpleXMLElement', LIBXML_NOCDATA );



    }

    /**
     * 对密文进行解密
     * @param string $encrypt 需要解密的密文
     * @return string 解密得到的明文
     * @param string $key 公众平台后台，用户设置的密钥
     */
    private function decrypt($encrypt, $appid,$key){

        //php7解密
        $key = base64_decode($key . "=");
        $iv = substr($key, 0, 16);
        $decrypted = openssl_decrypt($encrypt,'AES-256-CBC',substr($key, 0, 32),OPENSSL_ZERO_PADDING,$iv);

        //去除补位字符
        $result = $this->decode($decrypted);


        //去除16位随机字符串,网络字节序和AppId
        if (strlen($result) < 16)
            return "";
        $content = substr($result, 16, strlen($result));
        $len_list = unpack("N", substr($content, 0, 4));
        $xml_len = $len_list[1];
        $xml_content = substr($content, 4, $xml_len);
        $from_appid = substr($content, $xml_len + 4);

        if ($from_appid != $appid){
            return false;
        }

        return $xml_content;

    }

    /**
     * 用SHA1算法生成安全签名
     * @param string $token 票据
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $encrypt 密文消息
     *
     */
    private function getSHA1($token, $timestamp, $nonce, $encrypt_msg){

        //排序

        $array = array($encrypt_msg, $token, $timestamp, $nonce);
        sort($array, SORT_STRING);
        $str = implode($array);

        return sha1($str);

    }

    /**
     * 对解密后的明文进行补位删除
     * @param decrypted 解密后的明文
     * @return 删除填充补位后的明文
     */
    private function decode($text)
    {

        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

}