<template>
	<Tree class="tree-block" ref="tree-page" :data="data" show-checkbox @on-check-change="handleCheckPageChange"></Tree>
</template>

<script>
    export default {
    	props: {
    		data: Array,
				default: () => ([])
			},
			data () {
    		return {
    			access: ''
				}
			},
			methods: {
				handleCheckPageChange () {
					const ids = items.map((item) => {
						return item.id
					})
					this.access = ids.join(',')
				}
			}
		}
</script>

<style lang="less">
	.ivu-tree ul li {
		margin: 0;
	}
</style>
