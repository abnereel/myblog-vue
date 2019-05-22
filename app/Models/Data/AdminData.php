<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-24
 * Time: 10:08
 */

namespace App\Models\Data;


use App\Exception\ParamException;
use App\Models\Dao\AdminDao;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;

/**
 * @Bean()
 */
class AdminData
{
    /**
     * @Inject()
     * @var AdminDao
     */
    private $adminDao;

    /**
     * 通过id获取管理员信息
     * @param Int $id 管理员ID
     * @return array
     * @throws ParamException
     */
    public function findById(Int $id):Array
    {
        if (!$id) {
            throw new ParamException();
        }
        $admin = $this->adminDao->findById($id);
        if (!$admin) {
            return [];
        }
        return $admin->toArray();
    }

    /**
     * 获取一个管理员信息
     * @param array $condition
     * @return array
     * @throws ParamException
     */
    public function findOne(Array $condition):Array
    {
        if (empty($condition)) {
            throw new ParamException();
        }
        $admin = $this->adminDao->findOne($condition);
        if (!$admin) {
            return [];
        }
        return $admin->toArray();
    }

    /**
     * 获取所有管理员（正常、禁用）
     * @param Int $offset 偏移位
     * @param Int $limit 个数
     * @return Array
     */
    public function findAll(Int $offset = 0, Int $limit = 0):Array
    {
        $admins = $this->adminDao->findAll($offset, $limit);
        if (empty($admins)) {
            return [];
        }
        return $admins->toArray();
    }

    /**
     * 获取管理员个数
     * @return Int
     */
    public function count():Int
    {
        return $this->adminDao->count();
    }

    /**
     * 通过ID更新一条数据
     * @param array $attributes
     * @param $id
     * @return bool
     * @throws ParamException
     */
    public function updateById(Array $attributes, Int $id)
    {
        if (empty($attributes) || !$id) {
            throw new ParamException();
        }
        $r = $this->adminDao->updateById($attributes, $id);
        if ($r > 0) {
            return true;
        }
        return false;
    }
}
