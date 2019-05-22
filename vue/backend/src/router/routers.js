import Main from '@/components/main'
import parentView from '@/components/parent-view'

/**
 * iview-admin中meta除了原生参数外可配置的参数:
 * meta: {
 *  title: { String|Number|Function }
 *         显示在侧边栏、面包屑和标签栏的文字
 *         使用'{{ 多语言字段 }}'形式结合多语言使用，例子看多语言的路由配置;
 *         可以传入一个回调函数，参数是当前路由对象，例子看动态路由和带参路由
 *  hideInMenu: (false) 设为true后在左侧菜单不会显示该页面选项
 *  notCache: (false) 设为true后页面不会缓存
 *  access: (null) 可访问该页面的权限数组，当前路由设置的权限会影响子路由
 *  icon: (-) 该页面在左侧菜单、面包屑和标签导航处显示的图标，如果是自定义图标，需要在图标名称前加下划线'_'
 *  beforeCloseName: (-) 设置该字段，则在关闭当前tab页时会去'@/router/before-close.js'里寻找该字段名对应的方法，作为关闭前的钩子函数
 * }
 */

export default [
  {
    path: '/login',
    name: 'login',
    meta: {
      title: 'Login - 登录',
      hideInMenu: true
    },
    component: () => import('@/view/login/login.vue')
  },
  {
    path: '/',
    name: '_home',
    redirect: '/home',
    component: Main,
    meta: {
      hideInMenu: false,
      notCache: true
    },
    children: [
      {
        path: '/home',
        name: 'home',
        meta: {
          hideInMenu: false,
          title: '首页',
          notCache: true,
          icon: 'md-home'
        },
        component: () => import('@/view/single-page/home')
      }
    ]
  },
  {
    path: '/access',
    name: 'access',
    component: Main,
    meta: {
    	hideInMenu: false,
      icon: 'md-contacts',
			title: '权限管理'
    },
    children: [
      {
        path: 'admin',
        name: 'access_admin',
        meta: {
          title: '管理员管理',
          icon: 'md-person'
        },
        component: () => import('@/view/access/admin/admin.vue')
      },
			{
				path: 'router',
				name: 'access_router',
				meta: {
					title: '路由管理',
					icon: 'ios-paper'
				},
				component: () => import('@/view/access/router/router.vue')
			},
			{
				path: 'log',
				name: 'access_log',
				meta: {
					title: '管理员日志',
					icon: 'ios-paper'
				},
				component: () => import('@/view/access/log.vue')
			}
    ]
  },
	{
		path: '/cms',
		name: 'cms',
		component: Main,
		meta: {
			hideInMenu: false,
			icon: 'ios-apps',
			title: 'CMS管理'
		},
		children: [
			{
				path: 'archive',
				name: 'cms_archive',
				meta: {
					title: '内容管理',
					icon: 'md-document'
				},
				component: () => import('@/view/cms/archives/archive.vue')
			},
			{
				path: 'channel',
				name: 'cms_channel',
				meta: {
					title: '栏目管理',
					icon: 'md-menu'
				},
				component: () => import('@/view/cms/channel/channel.vue')
			},
			{
				path: 'block',
				name: 'cms_block',
				meta: {
					title: '区块管理',
					icon: 'md-cube'
				},
				component: () => import('@/view/cms/block/block.vue')
			}
		]
	},
  {
    path: '/multilevel',
    name: 'multilevel',
    meta: {
			hideInMenu: true,
      icon: 'md-menu',
      title: '多级菜单'
    },
    component: Main,
    children: [
      {
        path: 'level_2_1',
        name: 'level_2_1',
        meta: {
          icon: 'md-funnel',
          title: '二级-1'
        },
        component: () => import('@/view/multilevel/level-2-1.vue')
      },
      {
        path: 'level_2_2',
        name: 'level_2_2',
        meta: {
          access: ['super_admin'],
          icon: 'md-funnel',
          showAlways: true,
          title: '二级-2'
        },
        component: parentView,
        children: [
          {
            path: 'level_2_2_1',
            name: 'level_2_2_1',
            meta: {
              icon: 'md-funnel',
              title: '三级'
            },
            component: () => import('@/view/multilevel/level-2-2/level-3-1.vue')
          }
        ]
      },
      {
        path: 'level_2_3',
        name: 'level_2_3',
        meta: {
          icon: 'md-funnel',
          title: '二级-3'
        },
        component: () => import('@/view/multilevel/level-2-3.vue')
      }
    ]
  },
	{
		path: '',
		name: 'doc',
		meta: {
			hideInMenu: true,
			title: '文档',
			href: 'https://lison16.github.io/iview-admin-doc/#/',
			icon: 'ios-book'
		}
	},
  {
    path: '/401',
    name: 'error_401',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/401.vue')
  },
  {
    path: '/500',
    name: 'error_500',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/500.vue')
  },
  {
    path: '*',
    name: 'error_404',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/404.vue')
  }
]
