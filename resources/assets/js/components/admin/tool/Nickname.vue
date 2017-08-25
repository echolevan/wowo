<template>
    <div>
        <Form :model="formS" inline>
            <div class="pull-right">
                <Form-item>
                    <Input v-model.trim="formS.name" placeholder="禁用的昵称" @keyup.13.native="create_nickname"></Input>
                </Form-item>
                <Button type="primary" class="pull-right" @click="create_nickname">确定</Button>
            </div>
        </Form>
        <div v-for="(v, k) in list" style="display: inline-block">
            <Tag closable  class="my_tag_font" @on-close="handleClose(v.id)">{{v.value}}</Tag>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                list: [],
                formS: {
                    name: ''
                }
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get('/admin/tool/nickname/list').then(res => {
                    this.list = res.data
                })
            },
            create_nickname() {
                if(this.formS.name === ''){
                    this.$Message.error('请先填写名称')
                    return false
                }
                axios.get(`/admin/tool/nickname/create_nickname/${this.formS.name}`).then(res => {
                    if(res.data.sta === 1){
                        this._init()
                        this.formS.name = ''
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            handleClose(id) {
                axios.delete(`/admin/tool/nickname/del_nickname/${id}`).then(res => {
                    if(res.data.sta === 1){
                        this._init()
                        this.$Message.success(res.data.msg)
                    }else{
                        this.$Message.error(res.data.msg)
                    }
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
