<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-25
 * Time: 13:37
 */

namespace App\Exception;


use App\Lib\StatusCode;

class LoginException extends \Exception
{
    protected $code = StatusCode::UNAUTHORIZED;
    protected $message = '登录失败';
}
