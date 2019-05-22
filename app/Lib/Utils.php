<?php
/**
 * Created by 拾年磨一剑.
 * User: liqian
 * Date: 2019-02-26
 * Time: 21:46
 */

namespace App\Lib;


class Utils
{
    /**
     * 非递归生成路由树形数据
     * @param array $list
     * @return array
     */
    public static function routerTree($list, $access = [],  $isSuperAdmin = false)
    {
        $result = array(
            'id' => 0,
            'title' => '请选择路由权限',
            'expand' => true,
            'children' => []
        );

        if ($isSuperAdmin) {
            $result['disabled'] = true;
        }

        $tree = array();
        //第一步，将分类id作为数组key,并创建children单元
        foreach($list as $item){
            if (in_array($item['name'], ['home', 'error_401', 'error_404', 'error_500'])) {
            	$item['checked'] = true;
                $item['disabled'] = true;
			}
            if (in_array($item['id'], $access)) {
				$item['expand'] = true;
				$item['checked'] = true;
			}
            if ($isSuperAdmin) {
                $item['checked'] = true;
                $item['disabled'] = true;
            }
            $tree[$item['id']] = $item;
            $tree[$item['id']]['children'] = [];
        }
        //第二步，利用引用，将每个分类添加到父类children数组中，这样一次遍历即可形成树形结构。
        foreach($tree as $key=>$item){
            if($item['pid'] != 0){
                //把数据保存到父级的children数组内，只有保存的是地址引用，才能形成数据链
                $tree[$item['pid']]['children'][] = &$tree[$key];//注意：此处必须传引用否则结果不对
                if($tree[$key]['children'] == null){
                    unset($tree[$key]['children']); //如果children为空，则删除该children元素（可选）
                }
            }
        }
        //第三步，删除无用的非根节点数据
        foreach($tree as $key=>$item){
            if($item['pid'] != 0){
                unset($tree[$key]);
            }
        }

        foreach ($tree as $item) {
            $result['children'][] = $item;
        }

        return $result;
    }
}
