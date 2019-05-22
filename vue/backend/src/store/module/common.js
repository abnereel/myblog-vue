import { getUploadToken, getKey } from '@/api/common'
import { setPublicKey, getPublicKey } from '@/libs/util'

export default {
	state: {
		publicKey: '',
		uploadToken: ''
	},
	mutations: {
		setPublicKey (state, key) {
			state.publicKey = key
			setPublicKey(key)
		},
		setUploadToken (state, obj) {
			state.uploadToken = obj
		}
	},
	actions: {
		// 获取公钥
		getPublicKey ({ commit }) {
			return new Promise((resolve, reject) => {
				const key = getPublicKey()
				if (!key) {
					getKey().then(res => {
						const data = res.data
						commit('setPublicKey', data.key)
						resolve(data.key)
					}).catch(err => {
						reject(err)
					})
				} else {
					resolve(key)
				}
			})
		},
		// 获取上传凭证
		getUploadToken ({ state, commit }) {
			return new Promise((resolve, reject) => {
				const uploadToken = state.uploadToken
				const currTime = (new Date()).getTime() / 1000
				if (uploadToken && uploadToken.expires > currTime) {
					resolve(state.uploadToken.token)
				} else {
					getUploadToken().then(res => {
						const data = res.data
						commit('setUploadToken', {
							token: data.token,
							expires: currTime + 3600
						})
						resolve(data.token)
					}).catch(err => {
						if (err.error) {
							reject(err.error)
						} else {
							reject(err)
						}
					})
				}
			})
		}
	}
}
