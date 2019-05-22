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
 * 管理员表

 * @Entity()
 * @Table(name="admin")
 * @uses      Admin
 */
class Admin extends Model
{
    /**
     * @var int $id ID
     * @Id()
     * @Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $username 用户名
     * @Column(name="username", type="string", length=20, default="")
     */
    private $username;

    /**
     * @var string $nickname 昵称
     * @Column(name="nickname", type="string", length=50, default="")
     */
    private $nickname;

    /**
     * @var string $password 密码
     * @Column(name="password", type="string", length=32, default="")
     */
    private $password;

    /**
     * @var string $salt 密码盐
     * @Column(name="salt", type="string", length=30, default="")
     */
    private $salt;

    /**
     * @var string $avator 头像
     * @Column(name="avator", type="string", length=100, default="")
     */
    private $avator;

    /**
     * @var string $email 电子邮箱
     * @Column(name="email", type="string", length=100, default="")
     */
    private $email;

    /**
     * @var string $accessPages 允许访问的页面
     * @Column(name="access_pages", type="text", length=65535)
     */
    private $accessPages;

    /**
     * @var string $accessActions 允许访问的功能
     * @Column(name="access_actions", type="text", length=65535)
     */
    private $accessActions;

    /**
     * @var int $loginfailure 失败次数
     * @Column(name="loginfailure", type="tinyint", default=0)
     */
    private $loginfailure;

    /**
     * @var int $logintime 登录时间
     * @Column(name="logintime", type="integer", default=0)
     */
    private $logintime;

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
     * @var string $token Session标识
     * @Column(name="token", type="string", length=59, default="")
     */
    private $token;

    /**
     * @var string $status 状态
     * @Column(name="status", type="string", length=30, default="normal")
     */
    private $status;

    /**
     * ID
     * @param int $value
     * @return $this
     */
    public function setId(int $value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * 用户名
     * @param string $value
     * @return $this
     */
    public function setUsername(string $value): self
    {
        $this->username = $value;

        return $this;
    }

    /**
     * 昵称
     * @param string $value
     * @return $this
     */
    public function setNickname(string $value): self
    {
        $this->nickname = $value;

        return $this;
    }

    /**
     * 密码
     * @param string $value
     * @return $this
     */
    public function setPassword(string $value): self
    {
        $this->password = $value;

        return $this;
    }

    /**
     * 密码盐
     * @param string $value
     * @return $this
     */
    public function setSalt(string $value): self
    {
        $this->salt = $value;

        return $this;
    }

    /**
     * 头像
     * @param string $value
     * @return $this
     */
    public function setAvator(string $value): self
    {
        $this->avator = $value;

        return $this;
    }

    /**
     * 电子邮箱
     * @param string $value
     * @return $this
     */
    public function setEmail(string $value): self
    {
        $this->email = $value;

        return $this;
    }

    /**
     * 允许访问的页面
     * @param string $value
     * @return $this
     */
    public function setAccessPages(string $value): self
    {
        $this->accessPages = $value;

        return $this;
    }

    /**
     * 允许访问的功能
     * @param string $value
     * @return $this
     */
    public function setAccessActions(string $value): self
    {
        $this->accessActions = $value;

        return $this;
    }

    /**
     * 失败次数
     * @param int $value
     * @return $this
     */
    public function setLoginfailure(int $value): self
    {
        $this->loginfailure = $value;

        return $this;
    }

    /**
     * 登录时间
     * @param int $value
     * @return $this
     */
    public function setLogintime(int $value): self
    {
        $this->logintime = $value;

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
     * Session标识
     * @param string $value
     * @return $this
     */
    public function setToken(string $value): self
    {
        $this->token = $value;

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
     * ID
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 用户名
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * 昵称
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * 密码
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * 密码盐
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * 头像
     * @return string
     */
    public function getAvator()
    {
        return $this->avator;
    }

    /**
     * 电子邮箱
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * 允许访问的页面
     * @return string
     */
    public function getAccessPages()
    {
        return $this->accessPages;
    }

    /**
     * 允许访问的功能
     * @return string
     */
    public function getAccessActions()
    {
        return $this->accessActions;
    }

    /**
     * 失败次数
     * @return int
     */
    public function getLoginfailure()
    {
        return $this->loginfailure;
    }

    /**
     * 登录时间
     * @return int
     */
    public function getLogintime()
    {
        return $this->logintime;
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
     * Session标识
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 状态
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

}
