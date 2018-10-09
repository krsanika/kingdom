<?php

namespace Kingdom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Kingdom\AdminBundle\Services\GMemcached;

/*
 * 上書き用コントローラー。Symfony基本コントローラーじゃなくてこっちを継承してね。
 * 作者：くるさにか
 */

class Controller extends BaseController
{

    const DEFAULT_PAGE = 5;
    
}
