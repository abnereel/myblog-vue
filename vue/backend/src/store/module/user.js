import { login, logout, getUserInfo } from '@/api/user'
import { setToken, getToken } from '@/libs/util'

export default {
  state: {
    userName: '',
    userId: '',
    avatorImgPath: '',
    token: getToken(),
    access: [],
		accessPages: [],
		accessActions: [],
    hasGetInfo: false
  },
  mutations: {
    setAvator (state, avatorPath) {
      state.avatorImgPath = avatorPath
    },
    setUserId (state, id) {
      state.userId = id
    },
    setUserName (state, name) {
      state.userName = name
    },
    setAccess (state, access) {
      state.access = access
    },
		setAccessPages (state, accessPages) {
			state.accessPages = accessPages
		},
		setAccessActions (state, accessActions) {
			state.accessActions = accessActions
		},
    setToken (state, token) {
      state.token = token
      setToken(token)
    },
    setHasGetInfo (state, status) {
      state.hasGetInfo = status
    }
  },
  actions: {
    // 登录
    handleLogin ({ commit }, {userName, password}) {
      userName = userName.trim()
      return new Promise((resolve, reject) => {
        login({
          userName,
          password
        }).then(res => {
          const data = res.data
          commit('setToken', data.token)
          commit('setAvator', data.avator)
          commit('setUserName', data.nickname)
          commit('setUserId', data.id)
          commit('setAccess', data.access)
					commit('setAccessPages', data.accessPages)
					commit('setAccessActions', data.accessActions)
          commit('setHasGetInfo', true)
          resolve(data)
        }).catch(err => {
          if (err.error) {
            reject(err.error)
          } else {
            reject(err)
          }
        })
      })
    },
    // 退出登录
    handleLogOut ({ state, commit }) {
      return new Promise((resolve, reject) => {
        logout().then(() => {
          commit('setToken', '')
          commit('setAccess', [])
          resolve()
        }).catch(err => {
          if (err.error) {
            reject(err.error)
          } else {
            reject(err)
          }
        })
        // 如果你的退出登录无需请求接口，则可以直接使用下面三行代码而无需使用logout调用接口
        // commit('setToken', '')
        // commit('setAccess', [])
        // resolve()
      })
    },
    // 获取用户相关信息
    getUserInfo ({ state, commit }) {
      return new Promise((resolve, reject) => {
        try {
          getUserInfo().then(res => {
            const data = res.data
            commit('setAvator', data.avator)
            commit('setUserName', data.nickname)
            commit('setUserId', data.id)
            commit('setAccess', data.access)
						commit('setAccessPages', data.accessPages)
						commit('setAccessActions', data.accessActions)
            commit('setHasGetInfo', true)
            resolve(data)
          }).catch(err => {
            if (err.error) {
              reject(err.error)
            } else {
              reject(err)
            }
          })
        } catch (error) {
          reject(error)
        }
      })
    }
  }
}
