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
				<Button class="button" type="error" @click="del">
					<Icon type="md-trash" :size="fontSize"/>
					<span class="text">删除</span>
				</Button>
			</Row>
			<Table
				border
				highlight-row
				class="table-data"
				:columns="columns"
				:data="routers"
				@on-current-change="handleCurrentChange"
			>
			</Table>
    </Card>
		<RouterAdd :show="showAddPage" :routers="routers" v-on:close="handleCloseAdd" v-on:add="handleAdd"></RouterAdd>
		<RouterEdit :show="showEditPage" :router="selected" :routers="routers" v-on:close="handleCloseEdit" v-on:edit="handleEdit"></RouterEdit>
  </div>
</template>

<script>
	import RouterAdd from './router-add'
  import RouterEdit from './router-edit'
	import { mapActions } from 'vuex'
	export default {
		data () {
			return {
			  selected: {},
				showAddPage: false,
				showEditPage: false,
				loading: '',
				fontSize: 18,
				columns: [
					{
						title: '路由标题',
						key: 'title'
					},
					{
						title: '路由规则',
						key: 'name'
					},
          {
            title: '类型',
            key: 'ispage',
            align: 'center',
						width: 100,
						render: (h, params) => {
              if (params.row.ispage) {
                return (<span style="color:#57a3f3">页面</span>)
							} else {
              	return (<span>功能</span>)
              }
						}
          },
					{
						title: '权重',
						key: 'weigh',
						align: 'center',
            width: 100
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
						title: '修改时间',
						key: 'updatetime',
						align: 'center'
					}
				],
				routers: []
			}
		},
		mounted () {
			this.getRouterList()
		},
		components: {
			RouterAdd,
      RouterEdit
		},
		methods: {
			...mapActions([
				'getRouters',
				'deleteRouter'
			]),
			getRouterList () {
				this.getRouters().then(res => {
				  this.selected = {}
				  this.loading = ''
					this.routers = res
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
			refresh () {
        this.runShowLoading()
        setTimeout(() => {
          this.getRouterList()
        }, 800)
			},
			add () {
				this.showAddPage = true
			},
			edit () {
        if (JSON.stringify(this.selected) === '{}') {
          this.$Notice.warning({
            title: '请选择需要编辑的数据'
          })
        } else {
          this.showEditPage = true
        }
			},
			del () {
        if (JSON.stringify(this.selected) !== '{}') {
          this.$Modal.confirm({
            title: '提示',
            content: '确定要删除选中的数据吗？',
            onOk: () => {
							const filter = ['home', 'access', 'access_admin', 'access_router']
              const flag = filter.every((item) => {
							  if (this.selected.name.toLowerCase() === item) {
                  this.$Notice.warning({
                    'title': this.selected.title + '不能被删除'
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
                this.deleteRouter(this.selected.id).then(res => {
                  setTimeout(msg, 1)
                  this.$Notice.success({
                    'title': '删除成功',
                    duration: 1.5,
                    onClose: () => {
											let delIndex = null
											this.routers.forEach((item, index) => {
												if (item.id === this.selected.id) {
													delIndex = index
												}
											})
											this.routers.splice(delIndex, 1)
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
			// 添加
      handleAdd (routers) {
				this.routers = routers
				this.showAddPage = false
			},
      // 编辑
      handleEdit (routers) {
        this.routers = routers
        this.showEditPage = false
      },
			// 关闭添加窗口
      handleCloseAdd () {
			  this.showAddPage = false
      },
      // 关闭编辑窗口
      handleCloseEdit () {
        this.showEditPage = false
      },
			// 选中表格一行数据
      handleCurrentChange (currentRow) {
			  currentRow.title = currentRow.title.replace(currentRow.spacer + ' ', '')
			  this.selected = currentRow
      }
		}
	}
</script>

<style lang="less">
	.action {
		.button {
			margin-right: 2px;
			.loading {
				-webkit-animation: my-loading .8s linear infinite;
				-o-animation: my-loading .8s linear infinite;
				animation: my-loading .8s linear infinite;
			}
		}
	}
	.table-data {
		margin-top: 10px;
		cursor: pointer;
	}
</style>
