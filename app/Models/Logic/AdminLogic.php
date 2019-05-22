<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-24
 * Time: 10:11
 */

namespace App\Models\Logic;


use App\Exception\LoginException;
use App\Exception\ParamException;
use App\Models\Data\AdminData;
use App\Models\Data\RouterData;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
use Swoft\Exception\Exception;

/**
 * 用户逻辑层
 * 同时可以被controller server task使用
 *
 * @Bean()
 */
class AdminLogic
{
    /**
     * @Inject()
     * @var AdminData
     */
    private $adminData;

    /**
     * @Inject()
     * @var RouterData
     */
    private $routerData;

    /**
     * 检查账号是否可以登录，若可以，则返回账号信息（去掉 密码、盐）
     * @param String $username 登录账号
     * @param String $password 登录密码
     * @return |null
     * @throws LoginException
     */
    public function isCanLogin(String $username, String $password)
    {
        if (!$username || !$password) {
            throw new ParamException();
        }
        $admin = $this->isExist(['username' => $username]);
        if (!$admin) {
            throw new LoginException('用户不存在');
        }
        if ($admin['status'] !== 'normal') {
            throw new LoginException('该用户当前不可登录');
        }
        $pwd = $this->passwdEncrypt($password, $admin['salt']);
        if ($pwd != $admin['password']) {
            throw new LoginException('密码错误');
        }
        unset($admin['password'], $admin['salt']);

        return $admin;
    }

    /**
     * 获取管理员权限
     * @param array $admin 管理员信息
     * @param int $isPage 1:页面 0:功能
     * @return array
     * @throws ParamException
     */
    public function setAdminAccess(Array $admin, Int $isPage = 1)
    {
        if (empty($admin)) {
            throw new ParamException();
        }
        if ($admin['accessPages'] === '*' || $admin['accessActions'] === '*') {// 超级管理员
            $admin['access'] = ['*'];
            $admin['accessPages'] = ['*'];
            $admin['accessActions'] = ['*'];
        } else {// 普通管理员
            if ($admin['accessPages']) {
                $admin['accessPages'] = explode(',', $admin['accessPages']);
                $admin['accessActions'] = explode(',', $admin['accessActions']);
                $routers = $this->routerData->getRoutersByIds($admin['accessPages'], $isPage);

                $access = [];
                foreach ($routers as $router) {
                    $access[] = $router['name'];
                }
                $admin['access'] = $access;
            } else {// 权限为空
                $admin['access'] = [];
                $admin['accessPages'] = [];
                $admin['accessActions'] = [];
            }
        }

        return $admin;
    }

    /**
     * 通过ID更新管理员
     * @param array $attributes 需要更新的属性、数据
     * @param int $id 主键ID
     * @return bool
     * @throws Exception
     * @throws ParamException
     */
    public function updateAdminById(Array $attributes, Int $id)
    {
        if (empty($attributes) || !$id) {
            throw new ParamException();
        }
        $r = $this->adminData->updateById($attributes, $id);
        if (!$r) {
            throw new Exception('更新失败');
        }

        return true;
    }

    /**
     * 通过ID获取管理员
     * @param Int $id
     * @return array
     * @throws ParamException
     */
    public function getAdminById(Int $id):Array
    {
        $admin = $this->adminData->findById($id);

        return $this->formattAdmin($admin);
    }

    /**
     * 检查管理员是否存在，若存在，则返回该管理员信息
     * @param array $condition
     * @return array|bool|mixed
     * @throws ParamException
     */
    public function isExist(Array $condition)
    {
        $admin = $this->adminData->findOne($condition);
        if (empty($admin)) {
            return false;
        }

        return $this->formattAdmin($admin);
    }

    /**
     * 获取管理员列表
     * @param Int $offset 偏移位
     * @param Int $limit 获取的数量
     * @return array
     */
    public function getAdminsList(Int $offset, Int $limit):Array
    {
        $admins = $this->adminData->findAll($offset, $limit);
        foreach ($admins as &$admin) {
            $admin = $this->formattAdmin($admin);
        }

        return $admins;
    }

    /**
     * 获取管理员数量
     * @return Int
     */
    public function getAdminsCount():Int
    {
        return $this->adminData->count();
    }

    /**
     * 格式化admin数据输出
     * @param array $admin
     * @return mixed
     */
    private function formattAdmin(Array $admin):Array
    {
        if (empty($admin)) {
            return [];
        }
        // unset($admin['password'], $admin['salt']);
        $admin['_checked'] = false;
        $admin['logintime'] = date('Y-m-d H:i:s', $admin['logintime']);
        return $admin;
    }

    /**
     * 生成密码密文
     * @param string $password 密码
     * @param string $salt 盐
     * @return string 密码密文
     */
    public function passwdEncrypt(String $password, String $salt):String
    {
        return md5(md5($password).$salt);
    }
}
