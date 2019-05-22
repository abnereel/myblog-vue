<?php
/**
 * Token处理中间件
 */
namespace App\Middlewares;

use App\Exception\TokenException;
use Firebase\JWT\JWT;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;


/**
 * @Bean()
 */
class TokenMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = strtolower($request->getRequestTarget());

        switch ($action) {
            case '/'://默认
            case '/v1/key'://获取公钥
            case '/v1/login'://登录
            case strpos($action, 'devtool') !== false://DevTool工具
                break;
            default:
            	$token = $request->getHeader('token')[0];
                if ($token) {
                    $pubkey = cache()->get('token_pubkey');
                    $jwt = (array)JWT::decode($token, $pubkey, ['RS256']);
                    $redis_token = cache()->get('token_'.$jwt['id']);
                    if ($redis_token != $token) {
                        throw new TokenException();
                    }
                } else {
                    throw new TokenException();
                }
                break;
        }

        return $response = $handler->handle($request);
    }
}
