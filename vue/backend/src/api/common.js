import axios from '@/libs/api.request'

export const getUploadToken = (token) => {
	return axios.request({
		url: 'uptoken',
		method: 'get'
	})
}

export const getKey = () => {
	return axios.request({
		url: 'key',
		method: 'get'
	})
}
