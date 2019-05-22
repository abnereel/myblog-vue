import { getAdmins, addAdmin, updateAdmin, deleteAdmin } from '@/api/access'

export default {
	state: {
		admins: []
	},
	mutations: {
		setAdmins (state, admins) {
			state.admins = admins
		}
	},
	actions: {
		// 获取管理员列表
		getAdmins ({ state, commit }, { page, size }) {
			return new Promise((resolve, reject) => {
				getAdmins(page, size).then(res => {
					commit('setAdmins', res.data.list)
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
		// 添加管理员
		addAdmin ({ commit }, admin) {
			return new Promise((resolve, reject) => {
				addAdmin(admin).then(res => {
					commit('setAdmins', res.data.list)
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
		// 更新管理员信息
		updateAdmin ({ commit }, admin) {
			return new Promise((resolve, reject) => {
				updateAdmin(admin).then(res => {
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
		// 删除管理员
		deleteAdmin ({ commit }, id) {
			return new Promise((resolve, reject) => {
				deleteAdmin(id).then(res => {
					resolve(res.data)
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
