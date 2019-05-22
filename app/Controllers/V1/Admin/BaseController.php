<?php
/**
 * Created by 拾年磨一剑.
 * User: liqian
 * Date: 2019-01-22
 * Time: 21:21
 */

namespace App\Controllers\V1\Admin;

use App\Controllers\V1\V1Controller;
use Firebase\JWT\JWT;

/**
 * Admin模块基类
 */
class BaseController extends V1Controller
{
    /**
     * 生成密码密文
     * @param string $password 密码
     * @param string $salt 盐
     * @return string 密码密文
     */
    protected function passwdEncrypt(String $password, String $salt):String
    {
        return md5(md5($password).$salt);
    }

    /**
     * 对token进行解密
     * @param String $token
     * @return array
     */
    protected function decodeToken(String $token)
    {
        $pubkey = $this->getPublicKeyOfToken();
        $jwt = (array)JWT::decode($token, $pubkey, ['RS256']);

        return $jwt;
    }

    /**
     * 生成token
     * @param array $data
     * @return String
     */
    protected function genToken(Array $data):String
    {
        $config = [
            'id' => $data['id'],
            'nickname' => $data['nickname'],
            'accessPages' => $data['accessPages'],
            'accessActions' => $data['accessActions'],
            'access' => $data['access'],
            'avator' => $data['avator'],
            'exp' =>time() + 86400
        ];

        //生成token
        $token = JWT::encode($config, $this->getPrivateKeyOfToken(), 'RS256');

        $this->redis->set('token_'.$data['id'], $token);

        return $token;
    }

    /**
     * 获取公钥 （解密JWT token专用）
     * @return bool|mixed|string
     */
    protected function getPublicKeyOfToken():String
    {
        $publickKey = $this->redis->get('token_pubkey');
        if (!$publickKey) {
            $result = $this->genKey();
            $privateKey = $result['private'];
            $publickKey = $result['public'];

            $this->redis->set('token_prikey', $privateKey);
            $this->redis->set('token_pubkey', $publickKey);
        }

        return $publickKey;
    }

    /**
     * 获取私钥 （生成JWT token专用）
     * @return bool|mixed|string
     */
    protected function getPrivateKeyOfToken():String
    {
        $privateKey = $this->redis->get('token_prikey');
        if (!$privateKey) {
            $result = $this->genKey();
            $privateKey = $result['private'];
            $publickKey = $result['public'];

            $this->redis->set('token_prikey', $privateKey);
            $this->redis->set('token_pubkey', $publickKey);
        }

        return $privateKey;
    }
}
