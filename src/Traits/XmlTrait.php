<?php

namespace qcth\Wechat\Traits;


/**
 * Trait CurlTrait 数组转xml 或 xml转数组
 * 
 */
trait XmlTrait {

    /**
     * 将xml转为array,不分析XML属性等数据
     *
     * @param $xml
     *
     * @return mixed
     * @throws \Exception
     */
    public function xml_to_array( $xml ) {
        if ( ! $xml ) {
            throw new \Exception( "xml数据异常！" );
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader( true );

        return json_decode( json_encode( simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA ) ), true );
    }

    /**
     * 生成xml字符,不能分析复杂的XML数据比如有属性的XML
     * @return string
     * @throws \Exception
     */
    public function array_to_xml( $data, $level = 0 ) {
        if ( ! is_array( $data )
            || count( $data ) <= 0
        ) {
            throw new \Exception( "数组数据异常！" );
        }
        if ( $level == 0 ) {
            $xml = "<xml>";
        }
        foreach ( $data as $key => $val ) {
            if ( is_array( $val ) ) {
                $xml .= "<" . $key . ">" . $this->arrayToXml( $val, 1 ) . "</" . $key . ">";
            } else if ( is_numeric( $val ) ) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        if ( $level == 0 ) {
            $xml .= "</xml>";
        }

        return $xml;
    }
}