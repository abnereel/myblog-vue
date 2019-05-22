<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Models\Entity;

use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Required;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Model;
use Swoft\Db\Types;

/**
 * 用户实体
 *
 * @Entity()
 * @Table(name="user")
 * @uses      User
 * @version   2017年08月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class User extends Model
{
    /**
     * 主键ID
     *
     * @Id()
     * @Column(name="id", type=Types::INT)
     * @var null|int
     */
    private $id;

    /**
     * 名称
     *
     * @Column(name="name", type=Types::STRING, length=32)
     * @Required()
     * @var null|string
     */
    private $name;

    /**
     * 年龄
     *
     * @Column(name="age", type=Types::INT)
     * @var int
     */
    private $age = 0;

    /**
     * 创建时间
     *
     * @Column(name="createtime", type="int")
     * @var int
     */
    private $createtime = 0;

    /**
     * 更新时间
     *
     * @Column(name="updatetime", type="int")
     * @var string
     */
    private $updatetime = 0;

    /**
     * 非数据库字段，未定义映射关系
     *
     * @var mixed
     */
    private $otherProperty;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age)
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getCreatetime(): int
    {
        return $this->createtime;
    }

    /**
     * @param int $createtime
     */
    public function setCreatetime(int $createtime)
    {
        $this->createtime = $createtime;
    }

    /**
     * @return int
     */
    public function getUpdatetime(): int
    {
        return $this->updatetime;
    }

    /**
     * @param string $updatetime
     */
    public function setUpdatetime(int $updatetime)
    {
        $this->updatetime = $updatetime;
    }

    /**
     * @return mixed
     */
    public function getOtherProperty()
    {
        return $this->otherProperty;
    }

    /**
     * @param mixed $otherProperty
     */
    public function setOtherProperty($otherProperty)
    {
        $this->otherProperty = $otherProperty;
    }
}
