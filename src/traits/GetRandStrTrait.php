<?php

namespace qcth\Wechat\Traits;


/**
 * 生成随机字符串
 * Trait GetRandStrTicketTrait
 *
 */
trait GetRandStrTrait {

    //产生随机字符串，默认32位
    public function get_rand_str( $length = 32 ) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str   = "";
        for ( $i = 0; $i < $length; $i ++ ) {
            $str .= substr( $chars, mt_rand( 0, strlen( $chars ) - 1 ), 1 );
        }

        return $str;
    }
}