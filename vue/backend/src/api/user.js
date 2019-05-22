import axios from '@/libs/api.request'

export const login = ({ userName, password }) => {
  const data = {
    userName,
    password
  }
  return axios.request({
    url: 'login',
    data,
    method: 'post'
  })
}

export const getUserInfo = () => {
  return axios.request({
    url: 'userinfo',
    method: 'get'
  })
}

export const logout = () => {
  return axios.request({
    url: 'logout',
    method: 'post'
  })
}

export const getKey = () => {
	return axios.request({
		url: 'key',
		method: 'get'
	})
}
