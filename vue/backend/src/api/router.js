import axios from '@/libs/api.request'

export const getRouters = () => {
	return axios.request({
		url: 'routers',
		method: 'get'
	})
}

export const addRouter = (router) => {
	return axios.request({
		url: 'router',
		params: {
			router
		},
		method: 'post'
	})
}

export const updateRouter = (router) => {
	return axios.request({
		url: 'router/' + router.id,
		params: {
			router
		},
		method: 'put'
	})
}

export const deleteRouter = (id) => {
	return axios.request({
		url: 'router/' + id,
		method: 'delete'
	})
}

export const getRouterTree = () => {
	return axios.request({
		url: 'router/tree',
		method: 'get'
	})
}

export const getRouterTreeByUser = (id) => {
	return axios.request({
		url: 'router/tree/user/' + id,
		method: 'get'
	})
}
