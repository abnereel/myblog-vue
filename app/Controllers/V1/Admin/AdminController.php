<?php
/**
 * Created by 拾年磨一剑.
 * User: liqian
 * Date: 2019-01-22
 * Time: 21:21
 */

namespace App\Controllers\V1\Admin;


use App\Exception\ParamException;
use App\Models\Entity\Admin;
use App\Models\Logic\AdminLogic;
use Swoft\Bean\Annotation\Inject;
use Swoft\Exception\Exception;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * @Controller(prefix="/v1")
 */
class AdminController extends BaseController
{

    /**
     * @Inject()
     * @var AdminLogic
     */
    private $adminLogic;
    private $showFields = ['id', 'username', 'nickname', 'avator', 'access_pages', 'access_actions', 'logintime', 'status'];

    /**
     * 登录
     * @RequestMapping(route="login", method={RequestMethod::POST})
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $userName = $request->json('userName');
        $password = $request->json('password');
        if (!$userName || !$password) {
            throw new ParamException();
        }

        // 解密
        $userName = $this->decodeByPrivateKey($userName);
        $password = $this->decodeByPrivateKey($password);

        $admin = $this->adminLogic->isCanLogin($userName, $password);// 检查是否可以登录
        $admin = $this->adminLogic->setAdminAccess($admin);// 权限处理
        $this->adminLogic->updateAdminById(['logintime' => time(), 'updatetime' => time()], $admin['id']);// 更新登录时间
        $admin['token'] = $this->genToken($admin);// 生成token

        return $admin;
    }

    /**
     * 登出
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $token = $request->query('token', '');
        if ($token !== '') {
            $jwt = $this->decodeToken($token);
            $this->redis->delete('token_'.$jwt['id']);
        }
    }

    /**
     * 获取用户信息
     * @RequestMapping(route="userinfo", method={RequestMethod::GET})
     * @param Request $request
     * @return array
     */
    public function getUserInfo(Request $request):Array
    {
        $token = $request->getHeader('token')[0];
        if ($token !== '') {
            return $this->decodeToken($token);
        }
        return [];
    }

    /**
     * 获取管理员列表
     * @RequestMapping(route="admins", method={RequestMethod::GET})
     */
    public function getAdmins(Request $request)
    {
        $page = $request->query('page', '1');
        $size = $request->query('size', '10');
        $start = ($page - 1) * $size;

        $admins = $this->adminLogic->getAdminsList($start, $size);
        $total = $this->adminLogic->getAdminsCount();

        return ['list' => $admins, 'total' => $total];
    }

    /**
     * 更新管理员信息
     * @RequestMapping(route="admin/{id}", method={RequestMethod::PUT})
     * @param Request $request
     */
    public function updateAdmin(Request $request, Int $id)
    {
        $admin = $request->query('admin', null);
        if (!$admin) {
            throw new ParamException();
        }
        $admin = json_decode($admin, true);

        $data = [];
        $data['nickname'] = $admin['nickname'];
        $data['updatetime'] = time();
        $data['avator'] = $admin['avator'];
        $data['status'] = $admin['status'];
        if ($admin['username'] !== 'super_admin') {
            $data['access_pages'] = $admin['accessPages'];
            $data['access_actions'] = $admin['accessActions'];
        }

        $this->adminLogic->updateAdminById($data, $id);
        $admin = $this->adminLogic->getAdminById($id);

        return $admin;
    }

    /**
     * 添加管理员
     * @RequestMapping(route="admin", method={RequestMethod::POST})
     * @param Request $request
     * @throws ParamException
     */
    public function addAdmin(Request $request)
    {
        $data = $request->query('admin', null);
        if (!$data) {
            throw new ParamException('缺少admin参数');
        }
        $data = json_decode($data, true);
        $username 		= $data['username'];
        $password 		= $data['password'];
        $nickname 		= $data['nickname'];
        $accessPages   	= $data['accessPages'];
		$accessActions  = $data['accessActions'];
        $avator   		= $data['avator'];
        if (!$username || !$password) {
            throw new ParamException();
        }

        $username = $this->decodeByPrivateKey($username);
        $password = $this->decodeByPrivateKey($password);

        //检查用户是否存在
        $admin = $this->adminLogic->isExist(['username' => $username]);
        if (!$admin) {
            throw new ParamException('该用户已存在');
        }

        $salt = random(6);
        $password = $this->passwdEncrypt($password, $salt);

        $admin = new Admin();
        $admin['username']   	= $username;
        $admin['password']   	= $password;
        $admin['nickname']   	= $nickname;
        $admin['avator']     	= $avator;
        $admin['accessPages'] 	= $accessPages;
		$admin['accessActions'] = $accessActions;
        $admin['salt']       	= $salt;
        $admin['createtime'] 	= time();
        $admin['status']     	= 'normal';
        $id = $admin->save()->getResult();
        if ($id <= 0) {
            throw new Exception('添加失败');
        }

        return $this->getAdmins($request);
    }

    /**
     * 删除管理员
     * @RequestMapping(route="admin/{id}", method={RequestMethod::DELETE})
     * @param Request $request
     * @param int $id 管理员ID
     * @return string
     */
    public function deleteAdmin(Int $id)
    {
        if (!$id) {
            throw new ParamException('缺少id参数');
        }
        $r = Admin::deleteById($id)->getResult();
        if (!$r) {
            throw new Exception('删除失败');
        }

        return '';
    }
}
