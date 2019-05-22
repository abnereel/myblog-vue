<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-25
 * Time: 15:01
 */

namespace App\Lib;


class StatusCode
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;
    const INVALID_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const NOT_ACCEPTABLE = 406;
    const GONE = 410;
    const UNPROCESSABLE_ENTITY = 422;
    const SERVER_ERROR = 500;
}
