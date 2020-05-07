<?php

namespace Qcth\Wechat\Plug;

/**
 * Class Common  插件父类
 * @package qcth\laravel_open\plug
 */
class Common{

    protected $config; //配置项数组
    //标识是小程序还是公众平台
    protected $type_name;

    public function __construct($config=null){

        if(!is_null($config)){
            //赋值配置项
            $this->config=$config;
        }

        //子类初始化
        if(method_exists($this,'_init')){
            $this->_init();
        }

        //设置标识
        $this->set_type_name();
    }

    //设置标识,,区分是小程序还是公众平台
    private function set_type_name(){

        $arr=explode('\\',static::class);

        //当前调用的类名
        $class_name=end($arr);

        //类前缀
        $class_pre=strtolower(substr($class_name,0,6));

        if($class_pre=='wechat'){   //微信
            $this->type_name='weixin';
        }else{                    //小程序
            $this->type_name='small';
        }

    }
}