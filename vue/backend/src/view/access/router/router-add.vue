<template>
	<div class="page-block" v-if="show">
		<Card class="page-section">
			<Row class="top">
				<span class="title">添加路由</span>
				<div class="close">
					<Icon type="ios-close" size="26" @click="!disabled ? handleClose(): ''" />
				</div>
			</Row>
			<Row class="bottom">
				<Form ref="form" :model="form" :rules="rules" :label-width="120">
					<FormItem label="路由类型：" prop="type">
						<RadioGroup v-model="form.type" type="button">
							<Radio label="page">页面</Radio>
							<Radio label="action">功能</Radio>
						</RadioGroup>
					</FormItem>
					<FormItem label="上级路由：" prop="pid">
						<Select :style="controlStyle" v-model="form.pid" placeholder="请选择上级路由">
							<Option value="0" key="0" label="无">
								<span>无</span>
							</Option>
							<Option v-for="router in routers" :label="router.title" :value="router.id" :key="router.id" v-if="router.type==='page'">
								<span>{{router.title}}</span>
							</Option>
						</Select>
					</FormItem>
					<FormItem label="路由标题：" prop="title">
						<Input v-model="form.title" :style="controlStyle" placeholder="请输入路由标题"></Input>
					</FormItem>
					<FormItem label="路由规则：" prop="name">
						<Input v-model="form.name" :style="controlStyle" placeholder="页面：路由节点名称，功能：路由接口正则"></Input>
					</FormItem>
					<FormItem label="路由图标：" prop="icon">
						<Input v-model="form.icon" :style="controlStyle" placeholder="请输入路由图标字符串"></Input>
					</FormItem>
					<FormItem label="路由备注：" prop="remark">
						<Input v-model="form.remark" :style="controlStyle" placeholder="请输入路由备注"></Input>
					</FormItem>
					<FormItem label="路由权重：" prop="weigh">
						<Input v-model="form.weigh" :style="controlStyle" placeholder="请输入路由权重值" value="0"></Input>
					</FormItem>
					<FormItem label="路由状态：" prop="status">
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
	import Clone from 'clonedeep'
	export default {
		props: {
			show: {
				type: Boolean,
				default: false
			},
			routers: {
				type: Array,
				default: () => []
			}
		},
		data () {
			return {
				disabled: false,
				controlStyle: 'width:500px;',
				form: {
					type: 'page',
					pid: 0,
					title: '',
					name: '',
					icon: '',
					remark: '',
					weigh: 0,
					status: 'normal'
				},
				form_bk: {
					type: 'page',
					pid: 0,
					title: '',
					name: '',
					icon: '',
					remark: '',
					weigh: 0,
					status: 'normal'
				},
				rules: {
					title: [
						{ required: true, message: '请输入路由标题', trigger: 'blur' },
						{ type: 'string', min: 2, max: 20, message: '最短2位，最长20位', trigger: 'blur' }
					],
					name: [
						{ required: true, message: '页面：路由节点名称，功能：路由接口正则', trigger: 'blur' }
					]
				}
			}
		},
		watch: {
			show () {
				this.disabled = false
				this.form = Clone(this.form_bk)
			}
		},
		methods: {
      ...mapActions([
        'addRouter'
      ]),
			handleReset () {
				this.form = Clone(this.form_bk)
			},
			// 关闭窗口
			handleClose () {
				this.$emit('close')
			},
			// 添加路由
			handleSubmit (name) {
				this.$refs[name].validate((valid) => {
					if (valid) {
						this.disabled = true
            const msg = this.$Message.loading({
              content: '正在添加...',
              duration: 0
            })
            this.addRouter(this.form).then(res => {
              setTimeout(msg, 1)
              this.$Notice.success({
                title: '添加成功',
                duration: 1.5,
                onClose: () => {
                  this.$emit('add', res)
                }
              })
            }).catch(err => {
              setTimeout(msg, 1)
              this.$Notice.error({
                'title': err,
                duration: 1.5,
                onClose: () => {
                  this.disabled = false
                }
              })
            })
					} else {
						this.$Message.error('Fail!')
					}
				})
			}
		}
	}
</script>

<style lang="less">
	.page-block {
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
		.page-section {
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
