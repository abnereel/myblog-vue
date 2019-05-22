<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-25
 * Time: 10:17
 */

namespace App\Exception;


use App\Lib\StatusCode;

class ParamException extends \Exception
{
    protected $code = StatusCode::INVALID_REQUEST;
    protected $message = '参数错误';
}
