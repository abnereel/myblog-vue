<template>
  <div>
    <Card>
      <Row class="action">
				<Button class="button" type="primary" @click="refresh">
					<Icon class="refresh" :class="{loading: loading}" type="md-refresh" :size="fontSize"/>
				</Button>
				<Button class="button" type="primary" @click="add">
					<Icon type="md-add" :size="fontSize"/>
					<span class="text">添加</span>
				</Button>
				<Button class="button" type="primary" @click="edit">
					<Icon type="md-create" :size="fontSize"/>
					<span class="text">编辑</span>
				</Button>
				<Button class="button" type="error" @click="remove">
					<Icon type="md-trash" :size="fontSize"/>
					<span class="text">删除</span>
				</Button>
      </Row>
      <Table border class="table-data" :loading="loading" :stripe="true" :columns="columns" :data="data" @on-selection-change="handleSelectChange" @on-row-click="handleRowClick"></Table>
			<Page v-if="total > 0" class="page" :page-size="pageSize" :total="total" @on-change="handleChangePage" />
    </Card>
		<AdminAdd :show="adminAdd" :tree="routerTree" :token="uploadToken" v-on:close="handleCloseAdd" v-on:add="handleAdd"></AdminAdd>
    <AdminEdit :show="adminEdit" :token="uploadToken" :admin="selected[0]" v-on:close="handleCloseEdit" v-on:update="handleUpdate"></AdminEdit>
  </div>
</template>

<script>
	import {mapActions} from 'vuex'
	import AdminAdd from './admin-add'
	import AdminEdit from './admin-edit'
  import Clone from 'clonedeep'
	export default {
		data () {
			return {
				selected: [],
				fontSize: 18,
				adminAdd: false,
				adminEdit: false,
				loading: 'loading',
				pageSize: 10,
				currPage: 1,
				uploadToken: '',
				columns: [
					{
						type: 'selection',
						width: 50,
						align: 'center'
					},
					{
						title: '头像',
						key: 'avator',
						align: 'center',
						width: 80,
						render: (h, params) => {
							return (
								<avatar src={params.row.avator} shape="square" icon="ios-person" size="large" />
							)
						}
					},
					{
						title: '用户名',
						key: 'username'
					},
					{
						title: '昵称',
						key: 'nickname'
					},
					{
						title: '状态',
						key: 'status',
						width: 100,
						align: 'center',
						render: (h, params) => {
							if (params.row.status === 'normal') {
								return (<span>正常</span>)
							} else if (params.row.status === 'hidden') {
								return (<span style="color:#e74c3c">禁用</span>)
							}
						}
					},
					{
						title: '最后登录时间',
						key: 'logintime'
					}
				],
				data: [],
				showList: [],
        routerTree: [],
        total: 0
			}
		},
		components: {
			AdminAdd,
			AdminEdit
		},
		created () {
			this.runShowLoading()
			this.getAdminList()
			this.getUpToken()
			this.getRoutersByTree()
		},
		methods: {
			...mapActions([
				'getAdmins',
        'updateAdmin',
				'deleteAdmin',
				'getUploadToken',
				'getRouterTree'
			]),
			getUpToken () {
				this.getUploadToken().then(res => {
					this.uploadToken = res
				})
			},
			// 获取管理员列表
			getAdminList () {
				const page = this.currPage
        const size = this.pageSize
				this.getAdmins({ page, size }).then(res => {
					this.selected = []
					this.stopShowLoading()
					this.data = res.list
          this.total = parseInt(res.total)
					this.loading = false
				}).catch(err => {
					this.$Notice.error({
						title: err
					})
				})
			},
			// 获取路由列表
      getRoutersByTree () {
				this.getRouterTree().then(res => {
					this.routerTree = res
				}).catch(err => {
					this.$Notice.error({
						title: err
					})
				})
			},
			runShowLoading () {
				this.loading = 'loading'
			},
			stopShowLoading () {
				this.loading = ''
			},
			// 刷新
			refresh () {
				this.runShowLoading()
				setTimeout(() => {
					this.getAdminList()
				}, 800)
			},
			// 添加
			add () {
				this.adminAdd = true
			},
			// 编辑
			edit () {
				if (this.selected.length === 1) {
					this.adminEdit = true
        } else if (this.selected.length > 1) {
					this.$Notice.warning({
						'title': '一次只可编辑一条数据'
					})
        } else {
					this.$Notice.warning({
            'title': '请选择需要编辑的数据'
          })
        }
			},
			// 删除
			remove () {
				if (this.selected.length >= 1) {
					this.$Modal.confirm({
						title: '提示',
						content: '确定要删除选中的数据吗？',
						onOk: () => {
							const flag = this.selected.every((item) => {
								if (item.username === 'super_admin') {
									this.$Notice.warning({
										'title': '超级管理员不能被删除'
									})
									return false
								}
								return true
							})
							if (flag) {
								const msg = this.$Message.loading({
									content: '正在删除..',
									duration: 0
								})
								const admin = this.selected[0]
								this.deleteAdmin(admin.id).then(res => {
									setTimeout(msg, 1)
									this.$Notice.success({
										'title': '删除成功',
										duration: 1.5,
										onClose: () => {
											this.selected = []
											let delIndex = null
											this.data.forEach((item, index) => {
												if (item.id === admin.id) {
													delIndex = index
												}
											})
											this.data.splice(delIndex, 1)
										}
									})
								}).catch(err => {
									setTimeout(msg, 1)
									this.$Notice.error({
										'title': err
									})
								})
							}
						}
					})
				} else {
					this.$Notice.warning({
						'title': '请选择需要删除的数据'
					})
				}
			},
			// 关闭添加窗口
			handleCloseAdd () {
				this.adminAdd = false
			},
			// 关闭编辑窗口
			handleCloseEdit () {
				this.adminEdit = false
			},
			// 添加新管理员
			handleAdd (res) {
				this.selected = []
				this.data = res.list
				this.total = parseInt(res.total)
			},
			// 提交更新
			handleUpdate (admin) {
				const msg = this.$Message.loading({
					content: '正在更新...',
					duration: 0
				})
				this.updateAdmin(admin).then(res => {
					this.data = this.data.map((item) => {
            if (item.id === res.id) {
            	return res
            } else {
            	return item
            }
          })
					setTimeout(msg, 1)
					this.$Notice.success({
            title: '更新成功',
						duration: 1.5,
            onClose: () => {
	            this.adminEdit = false
	            this.selected = []
            }
          })
        }).catch(err => {
					setTimeout(msg, 1)
        	this.$Notice.error({
            'title': err
          })
        })
      },
			// 选择数据
      handleSelectChange (rows) {
        this.selected = rows
      },
			// 一行数据被点击
			handleRowClick (row, index) {
				if (this.data[index]._disabled !== true) {
					this.data[index]._checked = !this.data[index]._checked
					let s = Clone(this.selected)
          if (this.data[index]._checked) {
          	s = s.concat(row)
          } else {
          	s.forEach((item, i) => {
          		if (item['id'] === row['id']) {
          			s.splice(i, 1)
              }
            })
          }
          this.selected = s
        }
      },
			// 切换分页
			handleChangePage (page) {
				this.runShowLoading()
        this.currPage = page
				this.getAdminList()
			}
		}
	}
</script>

<style lang="less">
  .action {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    height: 30px;
		.button {
			margin-right: 2px;
			.loading {
				-webkit-animation: my-loading .8s linear infinite;
				-o-animation: my-loading .8s linear infinite;
				animation: my-loading .8s linear infinite;
			}
		}
    > div {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      height: inherit;
      padding: 0 10px;
      background: #488CE9;
      color: #ffffff;
      text-align: center;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 3px;
      margin-right: 5px;
      cursor: pointer;
			.loading {
				-webkit-animation: my-loading .8s linear infinite;
				-o-animation: my-loading .8s linear infinite;
				animation: my-loading .8s linear infinite;
			}
			&.forbidden {
				background: gray;
			}
      &.delete {
        background: #e74c3c;
      }
    }
  }
	.page {
		margin-top: 10px;
	}
  .table-data {
    margin-top: 10px;
  }
	.grey {
		background: grey;
	}
</style>
