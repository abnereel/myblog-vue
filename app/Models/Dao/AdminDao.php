<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-24
 * Time: 10:03
 */

namespace App\Models\Dao;


use App\Models\Entity\Admin;
use Swoft\Bean\Annotation\Bean;

/**
 * @Bean()
 */
class AdminDao
{
    public function findById(Int $id)
    {
        return Admin::findById($id)->getResult();
    }

    public function findOne(Array $condition)
    {
        return Admin::findOne($condition)->getResult();
    }

    public function findOneByUsername($username)
    {
        return Admin::findOne(['username' => $username])->getResult();
    }

    public function findAll($offset, $limit)
    {
        return Admin::findAll(
            [],
            [
                'offset' => $offset,
                'limit' => $limit
            ]
        )->getResult();
    }

    public function updateById($attributes, $id)
    {
        return Admin::updateOne($attributes, ['id' => $id]);
    }

    public function count()
    {
        return Admin::count()->getResult();
    }

    public function add(Array $data)
    {
        return Admin::query()->insert($data)->getResult();
    }
}
