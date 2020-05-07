<?php

namespace Qcth\Wechat;


/**
 * 微信插件统一入口
 * 在控制器中使用示例：Wechat::AppLogin($config)->login();
 */

class Index{

    private $link=null;

   
    
    public function __call($plug_name, $arguments=null){

        //参数个数
        switch (count($arguments)){
            case 1:
                $shared=true;
                break;
            case 2:
                $shared=$arguments[1];
                break;
            default:
                return '错误：第一个参数为配置项数组，第二个为可选参数，默认为单例模式，如果不需要单例模式，请传false';
        }


        $class_name='\qcth\laravel_wechat_open\plug\\'.ucfirst(trim($plug_name));


        if(empty($this->link[$class_name])){

            return $this->link[$class_name]=new $class_name(...$arguments);
        }

        if(!$shared){

            return new $class_name(...$arguments);
        }

        return $this->link[$class_name];
    }

}