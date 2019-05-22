<?php
/**
 * Created by PhpStorm.
 * User: Abner
 * Date: 2019-01-24
 * Time: 10:03
 */

namespace App\Models\Dao;


use App\Models\Entity\Router;
use Swoft\Bean\Annotation\Bean;

/**
 * @Bean()
 */
class RouterDao
{
    public function getRoutersByIds(Array $ids, $isPage = 1)
    {
        return Router::query()
            ->condition(['id' => $ids, 'ispage' => $isPage])
            ->get()
            ->getResult();
    }
}
