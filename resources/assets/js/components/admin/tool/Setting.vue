<template>
    <div>
        <Breadcrumb style="margin-bottom: 15px;font-size: 12px">
            <Breadcrumb-item>主页</Breadcrumb-item>
            <Breadcrumb-item>网站配置</Breadcrumb-item>
            <Breadcrumb-item>网站配置</Breadcrumb-item>
        </Breadcrumb>
        <div>
            <Form ref="formItem" :model="formItem" :label-width="100" :rules="ruleValidate" style="width:800px">
                <Form-item label="网站公告"  prop="notice">
                    <Input type="textarea" v-model="formItem.notice"></Input>
                </Form-item>

                <Form-item label="闲聊么开关">
                    <i-Switch v-model="formItem.xlm" size="large">
                        <span slot="open">是</span>
                        <span slot="close">否</span>
                    </i-Switch>
                </Form-item>

                <Form-item label="嘿市开关">
                    <i-Switch v-model="formItem.bm" size="large">
                        <span slot="open">是</span>
                        <span slot="close">否</span>
                    </i-Switch>
                </Form-item>


                <Button type="primary" :loading="loading" @click="add_to('formItem')" class="pull-right">
                    <span v-if="!loading">提交</span>
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
                    notice: '',
                    xlm: false,
                    bm: false,
                },
                loading: false,
                ruleValidate: {
                    notice: [
                        {required: true, message: '网站公告不能为空', trigger: 'blur'},
                        {max: 180, message: '网站公告最长180', trigger: 'change'},
                        {max: 180, message: '网站公告最长180', trigger: 'blur'},
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
                    this.formItem.xlm = res.data.tools['xlm'].value ? res.data.tools['xlm'].value === '0' ? false : true : false
                    this.formItem.bm = res.data.tools['bm'].value ? res.data.tools['bm'].value  === '0' ? false : true : false
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
                        this.$Message.error('表单验证失败!');
                    }
                    this.loading = false;
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
