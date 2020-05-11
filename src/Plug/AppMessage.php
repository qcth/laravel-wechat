<?php

namespace Qcth\Wechat\Plug;


use Qcth\Wechat\Traits\CurlTrait;

/**
 * 消息处理
 * Class Message
 * @package qcth\laravel_open\plug
 */
class AppMessage extends Common {

    use CurlTrait;

    //回复文本消息
    public function text( $content,$xml_obj ) {
        $xml= '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>';

        $text = sprintf( $xml, $xml_obj->FromUserName, $xml_obj->ToUserName, time(), 'text', $content );

        //回复xml
        $this->reply_xml($text);
    }

    //回复图片消息
    public function image( $media_id ,$xml_obj) {
        $xml= '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>';

        $text = sprintf( $xml, $xml_obj->FromUserName, $xml_obj->ToUserName, time(),'image', $media_id );

        //回复xml
        $this->reply_xml($text);
    }

    //回复语音消息
    public function voice( $media_id ,$xml_obj) {
        $xml= '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>
</xml>';

        $text = sprintf( $xml, $xml_obj->FromUserName, $xml_obj->ToUserName, time(),'voice', $media_id );
        //回复xml
        $this->reply_xml($text);
    }

    //回复视频消息
    public function video( $video ,$xml_obj) {

        $xml= '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video>
</xml>';

        $text = sprintf( $xml, $xml_obj->FromUserName, $xml_obj->ToUserName, time(),'video', $video['media_id'], $video['title'], $video['desc'] );
        //回复xml
        $this->reply_xml($text);
    }

    //回复音乐消息
    public function music( $music ,$xml_obj) {
        $xml= '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>
</xml>';

        $text = sprintf( $xml, $xml_obj->FromUserName, $xml_obj->ToUserName, time(),'music', $music['title'], $music['description'], $music['musicurl'], $music['hqmusicurl'], $music['thumbmediaid'] );

        //回复xml
        $this->reply_xml($text);
    }

    //回复图文信息
    public function news( $news,$xml_obj ) {
        $xml= '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
%s
</Articles>
</xml>';

        $item = '<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>';

        $items = '';
        foreach ( (array) $news as $n ) {
            $items .= sprintf( $item, $n['title'], $n['discription'], $n['picurl'], $n['url'] );
        }

        $text = sprintf( $xml, $xml_obj->FromUserName, $xml_obj->ToUserName, time(),'news', count( $news ), $items );

        //回复xml
        $this->reply_xml($text);
    }

    //输出加密后的xml
    private function reply_xml($xml){

        header( 'Content-type:application/xml' );

        //加密xml
        $encrypt= new Encrypt($this->config);

        //输出加密后的xml
        echo $encrypt->encrypt_xml($xml);die;

    }

    //群发消息正式发送
    public function sendall( $data ) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $this->authorizer_access_token();

        $content = $this->curl( $url, json_encode( $data, JSON_UNESCAPED_UNICODE ) );



        return json_decode($content,true);
    }





    //群发消息预览发送
    public function preview( $data ) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=' . $this->authorizer_access_token();

        $content = $this->curl( $url, json_encode( $data, JSON_UNESCAPED_UNICODE ) );

        return json_decode( $content, true );


    }
}
