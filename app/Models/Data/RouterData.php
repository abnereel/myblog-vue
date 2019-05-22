<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-24
 * Time: 10:08
 */

namespace App\Models\Data;


use App\Models\Dao\RouterDao;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;

/**
 * @Bean()
 */
class RouterData
{
    /**
     * @Inject()
     * @var RouterDao
     */
    private $routerDao;

    /**
     * 通过ID集获取路由集
     * @param array $ids ID数组
     * @param int $isPage 1:页面 0:功能
     * @return array|mixed
     */
    public function getRoutersByIds(Array $ids, $isPage = 1)
    {
        $routers = $this->routerDao->getRoutersByIds($ids, $isPage);
        if (empty($routers)) {
            return [];
        }
        return $routers;
    }
}
