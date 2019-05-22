<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Exception;

use App\Lib\StatusCode;
use Swoft\App;
use Swoft\Bean\Annotation\ExceptionHandler;
use Swoft\Bean\Annotation\Handler;
use Swoft\Exception\RuntimeException;
use Exception;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\Exception\BadMethodCallException;
use Swoft\Exception\ValidatorException;
use Swoft\Http\Server\Exception\BadRequestException;

/**
 * the handler of global exception
 *
 * @ExceptionHandler()
 * @uses      Handler
 * @version   2018年01月14日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SwoftExceptionHandler
{

    /**
     * 异常输出统一接口
     * @param Response $response
     * @param array $data
     * @return Response
     */
    public function handler(Response $response, Array $data):Response
    {
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            //->withHeader('Access-Control-Max-Age', 1728000)
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

        $code = isset($data['code']) ? $data['code'] : StatusCode::INVALID_REQUEST;
        $msg = isset($data['msg']) ? $data['msg'] : 'Error';

        return $response->json(['error' => $msg], $code);
    }

    /**
     * @Handler(LoginException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerLoginException(Response $response, \Throwable $throwable)
    {
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();

        $data = ['msg' => $exception, 'code' => $code];
        return $this->handler($response, $data);
    }

    /**
     * @Handler(ParamException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerParamException(Response $response, \Throwable $throwable)
    {
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();

        $data = ['msg' => $exception, 'code' => $code];
        return $this->handler($response, $data);
    }

    /**
     * @Handler(TokenException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerTokenException(Response $response, \Throwable $throwable)
    {
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();

        $data = ['msg' => $exception, 'code' => $code];
        return $this->handler($response, $data);
    }

    /**
     * @Handler(Exception::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerException(Response $response, \Throwable $throwable)
    {
        $file      = $throwable->getFile();
        $line      = $throwable->getLine();
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();

        $properties = App::getProperties();
        $debug = $properties->get('debug');

        if (!$debug) {
            $exception = '服务器错误';
        }

        $data = ['msg' => $exception, 'code' => 500];
        App::error(json_encode($data));
        return $this->handler($response, $data);
    }

    /**
     * @Handler(RuntimeException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerRuntimeException(Response $response, \Throwable $throwable)
    {
        $code      = $throwable->getCode();
        $exception = $throwable->getMessage();
        $data = ['msg' => $exception, 'code' => $code];

        return $this->handler($response, $data);
    }

    /**
     * @Handler(ValidatorException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerValidatorException(Response $response, \Throwable $throwable)
    {
        $exception = $throwable->getMessage();
        $code      = $throwable->getCode();
        $data = ['msg' => $exception, 'code' => $code];

        return $this->handler($response, $data);
    }

    /**
     * @Handler(BadRequestException::class)
     *
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerBadRequestException(Response $response, \Throwable $throwable)
    {
        $exception = $throwable->getMessage();
        $code      = $throwable->getCode();
        $data = ['msg' => $exception, 'code' => $code];

        return $this->handler($response, $data);
    }

    /**
     * @Handler(BadMethodCallException::class)
     *
     * @param Request    $request
     * @param Response   $response
     * @param \Throwable $throwable
     *
     * @return Response
     */
    public function handlerViewException(Request $request, Response $response, \Throwable $throwable)
    {
        $name  = $throwable->getMessage(). $request->getUri()->getPath();
        $notes = [
            'New Generation of PHP Framework',
            'High Performance, Coroutine and Full Stack',
        ];
        $links = [
            [
                'name' => 'Home',
                'link' => 'http://www.swoft.org',
            ],
            [
                'name' => 'Documentation',
                'link' => 'http://doc.swoft.org',
            ],
            [
                'name' => 'Case',
                'link' => 'http://swoft.org/case',
            ],
            [
                'name' => 'Issue',
                'link' => 'https://github.com/swoft-cloud/swoft/issues',
            ],
            [
                'name' => 'GitHub',
                'link' => 'https://github.com/swoft-cloud/swoft',
            ],
        ];
        $data  = compact('name', 'notes', 'links');

        return view('exception/index', $data);
    }

}
