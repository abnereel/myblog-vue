<?php
/**
 * Created by 拾年磨一剑.
 * User: liqian
 * Date: 2019-01-22
 * Time: 21:21
 */

namespace App\Controllers\V1\Admin;


use App\Exception\ParamException;
use App\Lib\Tree;
use App\Lib\Utils;
use App\Models\Entity\Admin;
use App\Models\Entity\Router;
use Swoft\Exception\Exception;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * @Controller(prefix="/v1")
 */
class RouterController extends BaseController
{

    /**
     * 获取路由列表
     * @RequestMapping(route="routers", method={RequestMethod::GET})
     */
    public function getRouters()
    {
        $routers = Router::findAll()->getResult();
        $routers = empty($routers) ? $routers : $routers->toArray();

        return ['list' => $this->formattRouters($routers)];
    }

    /**
     * 更新路由信息
     * @RequestMapping(route="router/{id}", method={RequestMethod::PUT})
     * @param Request $request
     */
    public function updateRouter(Request $request, $id)
    {
        $router = $request->query('router', null);
        if (!$router) {
            throw new ParamException('缺少router参数');
        }
        $router = json_decode($router, true);

        $data = [];
        $data['pid'] = $router['pid'];
        $data['type'] = $router['type'];
        $data['title'] = $router['title'];
        $data['name'] = $router['name'];
        $data['icon'] = $router['icon'];
        $data['ispage'] = $router['ispage'];
        $data['remark'] = $router['remark'];
        $data['weigh'] = $router['weigh'];
        $data['status'] = $router['status'];
        $data['updatetime'] = time();

        $r = Router::updateOne($data, ['id' => $id])->getResult();
        if ($r <= 0) {
            throw new Exception('更新失败');
        }

		return $this->getRouters();
    }

    /**
     * 添加路由
     * @RequestMapping(route="router", method={RequestMethod::POST})
     * @param Request $request
     * @throws ParamException
     */
    public function addRouter(Request $request)
    {
        $data = $request->query('router', null);
        if (!$data) {
            throw new ParamException('缺少router参数');
        }
        $data = json_decode($data, true);
        $name = $data['name'];
        if (!$name) {
            throw new ParamException();
        }

        $router = new Router();
        $router['pid']   		= $data['pid'];
        $router['type']   		= $data['type'];
        $router['title']   		= $data['title'];
        $router['name']     	= $data['name'];
        $router['icon']     	= $data['icon'];
		$router['remark']   	= $data['remark'];
		$router['weigh']    	= $data['weigh'];
		$router['ispage']   	= $data['type'] == 'page' ? 1 : 0;
		$router['weigh']    	= $data['weigh'];
        $router['updatetime'] 	= time();
		$router['createtime'] 	= time();
        $router['status']   	= 'normal';
        $id = $router->save()->getResult();
        if ($id <= 0) {
            throw new Exception('添加失败');
        }

        return $this->getRouters();
    }

    /**
     * 删除路由
     * @RequestMapping(route="router/{id}", method={RequestMethod::DELETE})
     * @param Request $request
     * @param $id 路由ID
     * @return string
     */
    public function deleteRouter($id)
    {
        if (!$id) {
            throw new ParamException('缺少id参数');
        }

        $childs = Router::findAll(['pid' => $id])->getResult();
        if (!empty($childs)) {
        	throw new Exception('该路由下存在子路由，请先删除子路由');
		}

        $r = Router::deleteById($id)->getResult();
        if (!$r) {
            throw new Exception('删除失败');
        }

        return '';
    }

    /**
     * 获取路由树
     * @RequestMapping(route="router/tree", method={RequestMethod::GET})
     * @return array
     */
    public function getRouterTree()
	{
		//路由树
        $routers = Router::findAll()->getResult();
        $routers = empty($routers) ? $routers : $routers->toArray();

		$tree = Utils::routerTree($routers);

        return ['tree' => [$tree]];
    }

	/**
	 * 获取用户路由树
	 * @RequestMapping(route="router/tree/user/{id}", method={RequestMethod::GET})
	 * @return array
	 */
	public function getRouterTreeByUser($id)
	{
		if (!$id) {
			throw new ParamException();
		}

		//获取所有路由
		$routers = Router::findAll()->getResult();
		$routers = empty($routers) ? $routers : $routers->toArray();

		//获取用户权限
		$admin = Admin::query()->condition(['id' => $id])->one()->getResult();
		if (!$admin) {
			throw new Exception('用户不存在');
		}
		$admin = $admin->toArray();
		$isSuperAdmin = false;
		if ($admin['accessActions'] === '*') {
			$admin['accessActions'] = ['*'];
			$isSuperAdmin = true;
        } else {
            $admin['accessActions'] = explode(',', $admin['accessActions']);
        }

		//生成该用户的路由权限树
		$actionTree = Utils::routerTree($routers, $admin['accessActions'], $isSuperAdmin);

		return ['tree' => [$actionTree]];
	}

    /**
     * 格式化router数据输出
     * @param $router
     * @return mixed
     */
    private function formattRouters($routers)
    {
        foreach ($routers as &$router) {
            $router['updatetime'] = date('Y-m-d H:i:s', $router['updatetime']);
        }
		$tree = Tree::instance();
		$tree->init($routers, 'pid');
		$routers = $tree->getTreeList($tree->getTreeArray(0), 'title');

        return $routers;
    }
}
