import axios from '@/libs/api.request'

export const getAdmins = (page, size) => {
	return axios.request({
		url: 'admins',
		params: {
			page,
			size
		},
		method: 'get'
	})
}

export const addAdmin = (admin) => {
	return axios.request({
		url: 'admin',
		params: {
			admin
		},
		method: 'post'
	})
}

export const updateAdmin = (admin) => {
	return axios.request({
		url: 'admin/' + admin.id,
		params: {
			admin
		},
		method: 'put'
	})
}

export const deleteAdmin = (id) => {
	return axios.request({
		url: 'admin/' + id,
		method: 'delete'
	})
}
