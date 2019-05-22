<?php
/**
 * Created by 拾年磨一剑.
 * User: liqian
 * Date: 2019-01-22
 * Time: 21:08
 */

namespace App\Controllers\V1;


use App\Lib\StatusCode;
use Swoft\Bean\Annotation\Inject;
use Swoft\Core\RequestContext;
use Swoft\Http\Server\AttributeEnum;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Redis\Redis;

/**
 * @Controller(prefix="/v1")
 */
class V1Controller
{

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    /**
     * 获取公钥 （前端加密专用）
     * @RequestMapping(route="key", method={RequestMethod::GET})
     */
    public function getPublicKey()
    {
        $publickKey = $this->redis->get('pubkey');
        if (!$publickKey) {
            $result = $this->genKey();
            $privateKey = $result['private'];
            $publickKey = $result['public'];

            $this->redis->set('prikey', $privateKey);
            $this->redis->set('pubkey', $publickKey);
        }

        return ['key' => $publickKey];
    }

    /**
     * 输出统一接口
     * @param array $result
     * @param string $msg
     * @param int $code
     */
    protected function send($result = null, $code = StatusCode::OK, $msg = 'OK')
    {
        $result =  array(
            'code' => $code,
            'msg' => $msg,
            'result' => $result
        );

        return RequestContext::getResponse()
            ->withStatus($code)
            ->withAttribute(AttributeEnum::RESPONSE_ATTRIBUTE , $result);
    }

    /**
     * 获取私钥 （解密前端加密数据专用）
     * @return mixed
     */
    protected function getPrivateKey()
    {
        $privateKey = $this->redis->get('prikey');
        if (!$privateKey) {
            $result = $this->genKey();
            $privateKey = $result['private'];
            $publickKey = $result['public'];

            $this->redis->set('prikey', $privateKey);
            $this->redis->set('pubkey', $publickKey);
        }

        return $privateKey;
    }

    /**
     * 通过私钥解密
     * @param String $param 需要解密的字符串
     * @return String 解密后的内容
     */
    protected function decodeByPrivateKey(String $param)
    {
        $privateKey = $this->getPrivateKey();
        openssl_private_decrypt(base64_decode($param), $param, $privateKey);

        return $param;
    }

    /**
     * 非对称加密
     * 创建公钥和私钥
     * @return array
     */
    protected function genKey()
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        $res = openssl_pkey_new($config);// 创建公钥和私钥

        openssl_pkey_export($res, $privKey);// 导出私钥
        $pubKey = openssl_pkey_get_details($res);// 导出公钥
        $pubKey = $pubKey["key"];

        return ['res' => $res, 'private' => $privKey, 'public' => $pubKey];
    }
}
