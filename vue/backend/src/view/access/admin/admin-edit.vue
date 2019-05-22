<template>
	<div class="page-edit" v-if="show">
		<Card class="edit-section">
			<Row class="top">
				<span class="title">编辑管理员信息</span>
				<div class="close">
					<Icon type="ios-close" size="26" @click="!disabled ? handleClose(): ''" />
				</div>
			</Row>
			<Row class="bottom">
				<Form ref="form" :model="form" :rules="rules" :label-width="120">
					<FormItem label="用户账号：" prop="username">
						<Input v-model="form.username" disabled :style="controlStyle"></Input>
					</FormItem>
					<FormItem label="用户昵称：" prop="nickname">
						<Input v-model="form.nickname"  :style="controlStyle" placeholder="请输入用户昵称"></Input>
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
	import {mapActions} from 'vuex'
	import Clone from 'clonedeep'
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			admin: {
				type: Object,
				default: () => ({})
			},
			token: {
				type: String,
				default: ''
			}
		},
		data () {
			return {
				disabled: false,
				controlStyle: 'width:500px;',
				admin_bk: null,
				uploadObj: {
					token: '',
					file: null
				},
				form: {
					id: 0,
					username: '',
					nickname: '',
					avator: '',
					access: '',
					status: ''
				},
				rules: {
					username: [
						{ required: true, message: '请输入用户账号', trigger: 'blur' }
					],
					nickname: [
						{ required: true, message: '请输入用户昵称', trigger: 'blur' },
						{ type: 'string', min: 2, max: 20, message: '最短2位，最长20位', trigger: 'blur' }
					]
				},
        tree: []
			}
		},
		watch: {
			admin () {
				this.admin_bk = Clone(this.admin)
				this.form = this.admin
			},
			token () {
				this.uploadObj.token = this.token
			},
			show () {
				this.disabled = false
				if (this.show) {
					this.getRouterTree(this.form.id)
				}
				if (!this.show) {
					this.tree = []
				}
			}
		},
		methods: {
			...mapActions([
				'getUploadToken',
				'getRouterTreeByUser'
			]),
			getRouterTree (id) {
				this.getRouterTreeByUser(id).then(res => {
					this.tree = res
				}).catch(err => {
					this.$Notice.error({
						title: 'error',
						desc: err
					})
				})
			},
			handleSubmit (name) {
				this.$refs[name].validate((valid) => {
					if (valid) {
						this.disabled = true
						this.$emit('update', this.form)
					} else {
						this.$Message.error('Fail!')
					}
				})
			},
			handleReset () {
				this.form = this.admin_bk
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
	.ivu-tree ul li {
		margin: 0;
	}
</style>
