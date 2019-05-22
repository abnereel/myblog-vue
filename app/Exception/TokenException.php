<?php
/**
 * Created by 拾年磨一剑.
 * User: liqian
 * Date: 2019-01-22
 * Time: 21:26
 */

namespace App\Exception;


use App\Lib\StatusCode;

class TokenException extends \Exception
{
    protected $code = StatusCode::UNAUTHORIZED;
    protected $message = 'Token Error';
}
