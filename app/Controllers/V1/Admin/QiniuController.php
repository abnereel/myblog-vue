<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-02-21
 * Time: 12:51
 */

namespace App\Controllers\V1\Admin;


use Qiniu\Auth;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * @Controller(prefix="/v1")
 */
class QiniuController extends BaseController
{
    protected $backet = 'myblog';
    protected $accessKey = 'n-3fe4NLEAxMsomekDlARjTpCQqJQD5pIaun13CC';
    protected $secretKey = 'tcLrVq65TgYVQU_29IYN_CF9ckFzcRvB4Sg-j8aM';

    /**
     * 获取七牛云上传凭证
     * @RequestMapping(route="uptoken", method={RequestMethod::GET})
     * @return array
     */
    public function getUploadToken():Array
    {
        $auth = new Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->backet);

        return ['token' => $token];
    }
}
