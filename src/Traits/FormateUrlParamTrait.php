<?php

namespace Qcth\Wechat\Traits;


/**
 * 把数组格式成url参数
 * Trait FormateUrlParamTicketTrait
 *
 */
trait FormateUrlParamTrait {

    //格式化参数格式化成url参数 为生成签名服务
    protected function formate_url_param( $data ) {
        $buff = "";
        foreach ( $data as $k => $v ) {
            if ( $k != "sign" && $k != "key" && $v != "" && ! is_array( $v ) ) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim( $buff, "&" );

        return $buff;
    }
}
