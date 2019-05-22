import { getRouters, addRouter, updateRouter, deleteRouter, getRouterTree, getRouterTreeByUser } from '@/api/router'

export default {
	state: {
		routers: [],
		routerTree: []
	},
	mutations: {
		setRouters (state, routers) {
			state.routers = routers
		},
    setRouterTree (state, tree) {
			state.routerTree = tree
    }
	},
	actions: {
		// 获取路由列表
		getRouters ({ commit }) {
			return new Promise((resolve, reject) => {
				getRouters().then(res => {
					commit('setRouters', res.data)
					resolve(res.data.list)
				}).catch(err => {
					if (err.error) {
						reject(err.error)
					} else {
						reject(err)
					}
				})
			})
		},
		// 添加路由
		addRouter ({ commit }, router) {
			return new Promise((resolve, reject) => {
				addRouter(router).then(res => {
          commit('setRouters', res.data)
          resolve(res.data.list)
				}).catch(err => {
					if (err.error) {
						reject(err.error)
					} else {
						reject(err)
					}
				})
			})
		},
		// 更新路由信息
		updateRouter ({ commit }, router) {
			return new Promise((resolve, reject) => {
				updateRouter(router).then(res => {
          commit('setRouters', res.data)
          resolve(res.data.list)
				}).catch(err => {
					if (err.error) {
						reject(err.error)
					} else {
						reject(err)
					}
				})
			})
		},
		// 删除路由
		deleteRouter ({ commit }, id) {
			return new Promise((resolve, reject) => {
				deleteRouter(id).then(res => {
          resolve(res.data)
				}).catch(err => {
					if (err.error) {
						reject(err.error)
					} else {
						reject(err)
					}
				})
			})
		},
		// 获取路由树
    getRouterTree ({ commit }) {
			return new Promise((resolve, reject) => {
        getRouterTree().then(res => {
        	commit('setRouterTree', res.data.tree)
        	resolve(res.data.tree)
        }).catch(err => {
          if (err.error) {
            reject(err.error)
          } else {
            reject(err)
          }
        })
			})
    },
		// 获取用户路由树
		getRouterTreeByUser ({ commit }, id) {
			return new Promise((resolve, reject) => {
				getRouterTreeByUser(id).then(res => {
					resolve(res.data.tree)
				}).catch(err => {
					if (err.error) {
						reject(err.error)
					} else {
						reject(err)
					}
				})
			})
		}
	}
}
