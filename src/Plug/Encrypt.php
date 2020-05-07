<?php

namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\GetRandStrTrait;

/**
 * Class Encrypt 加密
 * @package qcth\laravel_open\plug
 */
class Encrypt extends Common {

    use GetRandStrTrait;


    /**
     * 加密消息
     * @param $unencrypted_xml  明文xml
     */
    public function encrypt_xml($unencrypted_xml){

        //密钥
        $encodingaeskey=$this->config['component']['msg_key'];
        $timeStamp=$_GET['timestamp'];
        $nonce = $_GET["nonce"];
        $token=$this->config['component']['msg_token'];
        $appid=$this->config['component']['component_appid'];


        //加密
        $encrypt = $this->encrypt($unencrypted_xml, $appid,$encodingaeskey);

        //生成安全签名
        $signature = $this->getSHA1($token, $timeStamp, $nonce, $encrypt);

        //生成发送的xml
        $encrypt_xml = $this->generate($encrypt, $signature, $timeStamp, $nonce);


        return $encrypt_xml;
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @return string 加密后的密文\
    $encodingaeskey  密钥
     */
    private function encrypt($text, $appid,$encodingaeskey){

        $encodingaeskey = base64_decode($encodingaeskey . "=");

        //获得16位随机字符串，填充到明文之前
        $random = $this->get_rand_str(16);
        $text = $random . pack("N", strlen($text)) . $text . $appid;

        $iv = substr($encodingaeskey, 0, 16);

        $text = $this->encode($text);

        $encrypted = openssl_encrypt($text,'AES-256-CBC',substr($encodingaeskey, 0, 32),OPENSSL_ZERO_PADDING,$iv);

        return $encrypted;

    }

    /**
     * 生成xml消息
     * @param string $encrypt 加密后的消息密文
     * @param string $signature 安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     */
    private function generate($encrypt, $signature, $timestamp, $nonce){

        $format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
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
     * 对需要加密的明文进行填充补位
     * @param $text 需要进行填充补位操作的明文
     * @return 补齐明文字符串
     */
    private  function encode($text){

        $block_size = 32;
        $text_length = strlen($text);
        //计算需要填充的位数
        $amount_to_pad = $block_size - ($text_length % $block_size);
        if ($amount_to_pad == 0) {
            $amount_to_pad = $block_size;
        }
        //获得补位所用的字符
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }
}