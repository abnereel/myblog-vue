<template>
	<div class="page-edit" v-if="show">
		<Card class="edit-section">
			<Row class="top">
				<span class="title">添加管理员</span>
				<div class="close">
					<Icon type="ios-close" size="26" @click="!disabled ? handleClose(): ''" />
				</div>
			</Row>
			<Row class="bottom">
				<Form ref="form" :model="form" :rules="rules" :label-width="120">
					<FormItem label="用户账号：" prop="username">
						<Input v-model="form.username" :style="controlStyle" placeholder="请输入用户账号"></Input>
					</FormItem>
					<FormItem label="用户密码：" prop="password">
						<Input type="password" v-model="form.password"  :style="controlStyle" placeholder="请输入登录密码"></Input>
					</FormItem>
					<FormItem label="确认密码：" prop="password2">
						<Input type="password" v-model="form.password2"  :style="controlStyle" placeholder="请确认密码"></Input>
					</FormItem>
					<FormItem label="用户昵称：" prop="nickname">
						<Input v-model="form.nickname"  :style="controlStyle" placeholder="请输入昵称"></Input>
					</FormItem>
					<FormItem class="avator" label="头像：" prop="avator">
						<div class="container-avator">
							<avatar class="avator-image" :src="form.avator" shape="square" icon="ios-person" size="large" />
							<div class="avator-action">
								<Upload class="icon"
												ref="upload"
												action="//upload.qiniup.com"
												name="file"
												:show-upload-list = false
												:data="uploadObj"
												:format="['jpg','jpeg','png']"
												:max-size="10240"
												:on-success="handleUploadSuccess"
												:on-format-error="handleUploadFormatError"
												:on-exceeded-size="handleUploadMaxSize"
												:before-upload="handleUploadBefore">
									<Icon class="upload" type="ios-cloud-upload-outline"></Icon>
								</Upload>
								<Icon class="icon delete" type="ios-trash-outline" @click.native="handleRemove()"></Icon>
							</div>
						</div>
					</FormItem>
					<FormItem label="路由权限：">
						<Tree ref="tree" :data="tree" show-checkbox @on-check-change="handleCheckChange"></Tree>
					</FormItem>
					<FormItem label="状态：" prop="status">
						<RadioGroup v-model="form.status">
							<Radio label="normal">正常</Radio>
							<Radio label="hidden">禁止</Radio>
						</RadioGroup>
					</FormItem>
					<FormItem class="btn">
						<Button class="submit" type="primary" :disabled="disabled" @click="handleSubmit('form')">确认</Button>
						<Button :disabled="disabled" @click="handleReset()">重置</Button>
					</FormItem>
				</Form>
			</Row>
		</Card>
	</div>
</template>

<script>
	import { mapActions } from 'vuex'
	import { JSEncrypt } from 'jsencrypt'
	import Clone from 'clonedeep'
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			token: {
				type: String,
				default: ''
			},
			tree: {
				type: Array,
				default: () => ([])
			}
		},
		data () {
			return {
				disabled: false,
				controlStyle: 'width:500px;',
				uploadObj: {
					token: '',
					file: null
				},
				form: {
					id: 0,
					username: '',
					nickname: '',
					password: '',
					password2: '',
					avator: '',
					accessPages: '',
					accessActions: '',
					status: 'normal'
				},
				form_bk: {
					username: '',
					nickname: '',
					password: '',
					password2: '',
					avator: '',
					accessPages: '',
					accessActions: '',
					status: 'normal'
				},
				rules: {
					username: [
						{ required: true, message: '请输入用户账号', trigger: 'blur' }
					],
					nickname: [
						{ required: true, message: '请输入用户昵称', trigger: 'blur' },
						{ type: 'string', min: 2, max: 20, message: '最短2位，最长20位', trigger: 'blur' }
					],
					password: [
						{ required: true, message: '请输入用户密码', trigger: 'blur' },
						{ type: 'string', min: 6, max: 20, message: '最短6位，最长20位', trigger: 'blur' }
					],
					password2: [
						{ required: true, message: '请确认密码', trigger: 'blur' },
						{ type: 'string', min: 6, max: 20, message: '最短6位，最长20位', trigger: 'blur' }
					]
				}
			}
		},
		watch: {
			token () {
				this.uploadObj.token = this.token
			},
			show () {
				this.disabled = false
				this.form = this.form_bk
				if (!this.show) {
					this.form = this.form_bk
				}
			}
		},
		methods: {
			...mapActions([
				'getPublicKey',
				'addAdmin'
			]),
			handleSubmit (name) {
				this.$refs[name].validate((valid) => {
					if (valid) {
						if (!this.form.accessPages || !this.form.accessActions) {
							this.$Message.error('请选择路由权限')
							return false
						}
						if (this.form.password !== this.form.password2) {
							this.$Message.error('密码不一致')
						} else {
							this.getPublicKey().then(key => {
								this.disabled = true
								const encrypt = new JSEncrypt()
								encrypt.setPublicKey(key)
								const admin = Clone(this.form)
								admin.username = encrypt.encrypt(admin.username)
								admin.password = encrypt.encrypt(admin.password)
								delete admin.password2
								this.handleAdd(admin)
							}).catch(err => {
								this.disabled = false
								this.$Notice.error({
									title: err
								})
							})
						}
					} else {
						this.$Message.error('Fail!')
					}
				})
			},
			// 添加新管理员
			handleAdd (admin) {
				const msg = this.$Message.loading({
					content: '正在添加...',
					duration: 0
				})
				this.addAdmin(admin).then(res => {
					this.$emit('add', res)
					setTimeout(msg, 1)
					this.$Notice.success({
						title: '添加成功',
						duration: 1.5,
						onClose: () => {
							this.handleClose()
						}
					})
				}).catch(err => {
					this.disabled = false
					setTimeout(msg, 1)
					this.$Notice.error({
						'title': err
					})
				})
			},
			handleReset () {
				this.form = this.form_bk
			},
			handleClose () {
				this.$emit('close')
			},
			handleRemove () {
				this.form.avator = ''
			},
			handleUploadSuccess (res) {
				this.form.avator = this.$config.baseUrl.file + res.key
			},
			handleUploadFormatError () {
				this.$Notice.warning({
					title: '只可以上传图片'
				})
			},
			handleUploadMaxSize () {
				this.$Notice.warning({
					title: '上传图片超出限制大小'
				})
			},
			handleUploadBefore (file) {
				// this.uploadObj.file = file
			},
			// 勾选复选框的时候触发
			handleCheckChange (items) {
				const all = this.$refs.tree.getCheckedAndIndeterminateNodes()
				const actions = items.map((item) => {
					return item.id
				})
				let pages = []
				all.map((item) => {
					if (actions.indexOf(item.id) < 0) {
						pages.push(item.id)
					} else if (item.ispage) {
						pages.push(item.id)
					}
				})
				this.form.accessActions = actions.join(',')
				this.form.accessPages = pages.join(',')
			}
		}
	}
</script>

<style lang="less">
	.page-edit {
		position: absolute;
		z-index: 1000;
		top: 0;
		left: 0;
		width: 100vw;
		height: 100vh;
		background: rgba(0, 0, 0, .7);
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		.edit-section {
			width: 720px;
			min-height: 300px;
			max-height: 800px;
			overflow-y: visible;
			overflow-x: hidden;
			.top {
				width: inherit;
				height: 30px;
				display: flex;
				flex-direction: row;
				justify-content: flex-start;
				align-items: center;
				margin-bottom: 30px;
			}
			.top > .title {
				flex: 1;
				font-size: 20px;
				color: #3a3a3a;
				text-align: left;
			}
			.top > .close {
				flex: 1;
				text-align: right;
			}
			.top > .close > i {
				cursor: pointer;
			}
			.top > .close > i:hover {
				color: red;
			}
			.edit {
				border-bottom: 2px solid #ccc;
			}
			.bottom {
				margin: 20px 0;
				.btn {
					padding-top: 30px;
					.submit {
						margin-right: 10px;
					}
				}
				.container-avator {
					position: relative;
					z-index: 1;
					width: 100px;
					height: 100px;
					.avator-image {
						display: flex;
						flex-direction: row;
						justify-content: center;
						align-items: center;
						width: inherit;
						height: inherit;
						> i {
							font-size: 65px;
						}
					}
					.avator-action {
						position: absolute;
						top: 0;
						left: 0;
						z-index: 100;
						display: flex;
						flex-direction: row;
						justify-content: center;
						align-items: center;
						width: inherit;
						height: inherit;
						.icon {
							display: none;
							font-size: 30px;
							cursor: pointer;
							&.delete {
								margin-left: 20px;
							}
						}
						.ivu-upload-list {
							position: absolute;
							top: 0;
							left: 100px;
							font-size: 16px;
						}
					}
					.avator-action:hover {
						background: rgba(0, 0, 0, .6);
						-webkit-border-radius: 5px;
						-moz-border-radius: 5px;
						border-radius: 5px;
						.icon {
							display: block;
							color: #ffffff;
						}
					}
				}
			}
		}
	}
</style>
