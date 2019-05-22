import Vue from 'vue'
import Vuex from 'vuex'

import common from './module/common'
import user from './module/user'
import app from './module/app'
import access from './module/access'
import router from './module/router'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    //
  },
  mutations: {
    //
  },
  actions: {
    //
  },
  modules: {
  	common,
    user,
    app,
		access,
		router
  }
})
