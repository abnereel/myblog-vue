<style lang="less">
  @import './login.less';
</style>

<template>
  <div class="login">
    <div class="login-con">
      <Card icon="log-in" title="欢迎登录" :bordered="false">
        <div class="form-con">
          <login-form @on-success-valid="handleSubmit"></login-form>
          <p class="login-tip">输入任意用户名和密码即可</p>
        </div>
      </Card>
    </div>
  </div>
</template>

<script>
import LoginForm from '_c/login-form'
import { mapActions } from 'vuex'
import { JSEncrypt } from 'jsencrypt'
export default {
  components: {
    LoginForm
  },
  methods: {
    ...mapActions([
      'handleLogin',
			'getPublicKey'
    ]),
    handleSubmit ({ userName, password }) {
			const name = userName
			const pass = password
    	this.getPublicKey().then(key => {
				const encrypt = new JSEncrypt()
				encrypt.setPublicKey(key)
				const userName = encrypt.encrypt(name)
				const password = encrypt.encrypt(pass)
				this.handleLogin({ userName, password }).then(res => {
					this.$router.push({
						name: this.$config.homeName
					})
				}).catch(err => {
					this.$Notice.error({
						title: err
					})
				})
			}).catch(err => {
				this.$Notice.error({
					title: err
				})
			})
    }
  }
}
</script>

<style>

</style>
