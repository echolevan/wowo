<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>网站管理</Breadcrumb-item>
            <Breadcrumb-item>公告管理</Breadcrumb-item>
        </Breadcrumb>
        <div>
            <Form ref="formItem" :model="formItem" :label-width="100" :rules="ruleValidate" style="width:800px">
                <Form-item label="网站公告"  prop="notice">
                    <Input type="textarea" v-model="formItem.notice"></Input>
                </Form-item>

                <Button type="primary" :loading="loading" @click="add_to('formItem')" class="pull-right">
                    <span v-if="!loading">确定</span>
                    <span v-else>Loading...</span>
                </Button>

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
                loading: false,
                ruleValidate: {
                    notice: [
                        {required: true, message: '公告内容不能为空', trigger: 'blur'}
                    ]
                }
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get('/admin/tool/index').then(res => {
                    this.formItem.notice = res.data.tools['notice'].value ? res.data.tools['notice'].value : ''
                })
            },
            add_to(name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put('/admin/tool/create',{data:this.formItem}).then(res => {
                            if(res.data.sta === 1){
                                this.$Message.success(res.data.msg);
                            }
                        })
                    } else {
                        this.$Message.error('更新失败');
                    }
                    this.loading = false;
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
