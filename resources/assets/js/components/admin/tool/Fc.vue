<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>网站管理</Breadcrumb-item>
            <Breadcrumb-item>分成管理</Breadcrumb-item>
        </Breadcrumb>
        <div>
            <Form ref="formItem" :model="formItem" :label-width="100" style="width:800px">
                <Form-item label=插件分成>
                    <InputNumber :max="100" :min="0" v-model="formItem.fc"></InputNumber>%
                </Form-item>

                <Button type="primary" :loading="loading" @click="add_to('formItem')" class="pull-right">
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
                    fc: 100
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
                    this.formItem.fc = res.data.tools['fc'] ? parseInt(res.data.tools['fc'].value) : 0
                })
            },
            add_to(name) {
                this.loading = true;
                axios.put('/admin/tool/create',{data: this.formItem}).then(res => {
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
