<?php
namespace App\Models\Entity;

use Swoft\Db\Model;
use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Required;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Types;

/**
 * 路由表

 * @Entity()
 * @Table(name="router")
 * @uses      Router
 */
class Router extends Model
{
    /**
     * @var int $id 
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $type page为页面,action为功能
     * @Column(name="type", type="string", length=6, default="page")
     */
    private $type;

    /**
     * @var int $pid 父ID
     * @Column(name="pid", type="integer", default=0)
     */
    private $pid;

    /**
     * @var string $title 规则title
     * @Column(name="title", type="string", length=100, default="")
     */
    private $title;

    /**
     * @var string $name 规则名称
     * @Column(name="name", type="string", length=100, default="")
     */
    private $name;

    /**
     * @var string $icon 图标
     * @Column(name="icon", type="string", length=50, default="")
     */
    private $icon;

    /**
     * @var string $remark 备注
     * @Column(name="remark", type="string", length=255, default="")
     */
    private $remark;

    /**
     * @var int $ispage 是否为页面
     * @Column(name="ispage", type="tinyint", default=1)
     */
    private $ispage;

    /**
     * @var int $createtime 创建时间
     * @Column(name="createtime", type="integer", default=0)
     */
    private $createtime;

    /**
     * @var int $updatetime 更新时间
     * @Column(name="updatetime", type="integer", default=0)
     */
    private $updatetime;

    /**
     * @var int $weigh 权重
     * @Column(name="weigh", type="integer", default=0)
     */
    private $weigh;

    /**
     * @var string $status 状态
     * @Column(name="status", type="string", length=30, default="")
     */
    private $status;

    /**
     * @param int $value
     * @return $this
     */
    public function setId(int $value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * page为页面,action为功能
     * @param string $value
     * @return $this
     */
    public function setType(string $value): self
    {
        $this->type = $value;

        return $this;
    }

    /**
     * 父ID
     * @param int $value
     * @return $this
     */
    public function setPid(int $value): self
    {
        $this->pid = $value;

        return $this;
    }

    /**
     * 规则title
     * @param string $value
     * @return $this
     */
    public function setTitle(string $value): self
    {
        $this->title = $value;

        return $this;
    }

    /**
     * 规则名称
     * @param string $value
     * @return $this
     */
    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * 图标
     * @param string $value
     * @return $this
     */
    public function setIcon(string $value): self
    {
        $this->icon = $value;

        return $this;
    }

    /**
     * 备注
     * @param string $value
     * @return $this
     */
    public function setRemark(string $value): self
    {
        $this->remark = $value;

        return $this;
    }

    /**
     * 是否为页面
     * @param int $value
     * @return $this
     */
    public function setIspage(int $value): self
    {
        $this->ispage = $value;

        return $this;
    }

    /**
     * 创建时间
     * @param int $value
     * @return $this
     */
    public function setCreatetime(int $value): self
    {
        $this->createtime = $value;

        return $this;
    }

    /**
     * 更新时间
     * @param int $value
     * @return $this
     */
    public function setUpdatetime(int $value): self
    {
        $this->updatetime = $value;

        return $this;
    }

    /**
     * 权重
     * @param int $value
     * @return $this
     */
    public function setWeigh(int $value): self
    {
        $this->weigh = $value;

        return $this;
    }

    /**
     * 状态
     * @param string $value
     * @return $this
     */
    public function setStatus(string $value): self
    {
        $this->status = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * page为页面,action为功能
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 父ID
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * 规则title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 规则名称
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 图标
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * 备注
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * 是否为页面
     * @return mixed
     */
    public function getIspage()
    {
        return $this->ispage;
    }

    /**
     * 创建时间
     * @return int
     */
    public function getCreatetime()
    {
        return $this->createtime;
    }

    /**
     * 更新时间
     * @return int
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    /**
     * 权重
     * @return int
     */
    public function getWeigh()
    {
        return $this->weigh;
    }

    /**
     * 状态
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

}
