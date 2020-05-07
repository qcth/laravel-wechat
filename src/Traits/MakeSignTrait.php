<?php

namespace qcth\Wechat\Traits;


/**
 * 微信签名
 * Trait MakeSignTrait
 * 
 */
trait MakeSignTrait {

    use FormateUrlParamTrait;
    //生成签名
    public function make_sign($data,$type=0) {


        //签名步骤一：按字典序排序参数
        ksort( $data );
        $string = $this->formate_url_param( $data );
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->config['weixin']['key'];
        //签名步骤三：MD5加密
        $string = md5( $string );
        //签名步骤四：所有字符转为大写
        $result = strtoupper( $string );

        return $result;
    }

}