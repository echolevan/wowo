<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>网站管理</Breadcrumb-item>
            <Breadcrumb-item>公告管理</Breadcrumb-item>
        </Breadcrumb>
        <div>
            <Form ref="formItem" :model="formItem" :label-width="100" style="width:800px">
                <Form-item label="网站公告">
                    <Input type="textarea" v-model="formItem.notice"></Input>
                </Form-item>

                <Button type="primary" :loading="loading" @click="add_to('formItem')" class="pull-right">
                    <span v-if="!loading">确定</span>
                    <span v-else>Loading...</span>
                </Button>

                <div style="clear:both;margin-bottom:15px"></div>

            </Form>

            <Form ref="formItemOne" :model="formItemOne" :label-width="100" style="width:800px">
                <Form-item label="黑市公告">
                    <Input type="textarea" :rows="8" v-model="formItemOne.bm_notice"></Input>
                </Form-item>

                <Button type="primary" :loading="loading" @click="add_to('c')" class="pull-right">
                    <span v-if="!loading">确定</span>
                    <span v-else>Loading...</span>
                </Button>

                <div style="clear:both;margin-bottom:15px"></div>

            </Form>


        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                formItem: {
                    notice: ''
                },
                formItemOne: {
                    bm_notice: ''
                },
                loading: false
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get('/admin/tool/index').then(res => {
                    this.formItem.notice = res.data.tools['notice'] ? res.data.tools['notice'].value : ''
                    this.formItemOne.bm_notice = res.data.tools['bm_notice'] ? res.data.tools['bm_notice'].value : ''
                })
            },
            add_to(name) {
                this.loading = true;
                axios.put('/admin/tool/create',{data: name === 'formItem' ? this.formItem : this.formItemOne}).then(res => {
                    if(res.data.sta === 1){
                        this.$Message.success(res.data.msg);
                    }
                })
                this.loading = false;
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
