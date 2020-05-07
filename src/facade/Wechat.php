<?php

namespace Qcth\Wechat\Facade;

use Illuminate\Support\Facades\Facade;

class Wechat extends  Facade
{
    protected static  function  getFacadeAccessor()
    {
        return 'Wechat';
    }
}