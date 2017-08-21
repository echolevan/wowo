<template>
    <div>
        <Modal
                v-model="modal_edit"
                :title="model_title">
            <Form :model="formItem" :label-width="80" ref="formItem" :rules="ruleValidate">
                <Form-item label="名称" prop="title">
                    <Input v-model="formItem.title" placeholder="请输入"></Input>
                </Form-item>
                <Form-item label="类型" prop="type">
                    <Select v-model="formItem.type" style="width:200px">
                        <Option v-for="(v, k) in configBtType" :value="k" :key="k">{{ v }}</Option>
                    </Select>
                </Form-item>
                <Form-item label="云盘地址" prop="url" v-show="formItem.type === '2'">
                    <Input v-model="formItem.url" placeholder="请输入"></Input>
                </Form-item>
                <Form-item label="上传插件" v-show="formItem.type === '1'" prop="bm_url">
                    <Upload action="/admin/upload_bm_plug"
                            :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                            :on-success="handlePlugSuccess"
                            :before-upload="handlePlugUpload"
                            :on-remove="removePlug"
                            :show-upload-list="false"
                            v-show="formItem.bm_url === ''"
                    >
                        <Button type="ghost" icon="ios-cloud-upload-outline">上传文件</Button>
                    </Upload>
                    <p v-show="formItem.bm_url !== ''" @click="removePlug" class="hover_hand">需要重新上传BT点我删除</p>
                </Form-item>
            </Form>
            <div slot="footer">
                <Button type="primary" class="pull-right" :loading="loading_edit" @click="sub_ok('formItem')" >
                    <span>提交</span>
                </Button>
                <div style="clear: both"></div>
            </div>
        </Modal>
    </div>
</template>

<script>
    export default {
        data() {
            const validateUrl = (rule, value, callback) => {
                if(this.formItem.type === '2'){
                    if(this.formItem.url === ''){
                        callback(new Error('请填写云盘地址'));
                    }else{
                        callback();
                    }
                }else{
                    callback();
                }
            };
            const validateBmUrl = (rule, value, callback) => {
               setTimeout(() => {
                   if(this.formItem.type === '1'){
                       if(this.formItem.bm_url === ''){
                           callback(new Error('请先上传BT文件'));
                       }else{
                           callback();
                       }
                   }else{
                       callback();
                   }
               },10)
            };
            return {
                configBtType: configBtType,
                csrfToken: window.Laravel.csrfToken,
                loading_edit: false,
                modal_edit: false,
                model_title: '新增插件',
                formItem: {
                    id: '',
                    title: '',
                    type: '',
                    url: '',
                    bm_url: '',
                },
                ruleValidate: {
                    title: [
                        {required: true, message: '插件标题不能为空', trigger: 'blur'},
                        {max: 60, message: '插件标题最长60', trigger: 'change'}
                    ],
                    type: [
                        {required: true, message: '插件分类不能为空', trigger: 'change'}
                    ],
                    url: [
                        {validator: validateUrl, trigger: 'change'}
                    ],
                    bm_url: [
                        {validator: validateBmUrl, trigger: 'change'}
                    ],
                }
            }
        },
        methods: {
            clear_from(name) {
                this.$refs[name].resetFields();
            },
            handlePlugSuccess(res, file) {
                if (res.sta === 0) {
                    this.$Loading.error()
                    this.$Message.error(res.msg)
                } else {
                    this.$Loading.finish()
                    this.formItem.bm_url = res.url
                    this.$Message.success('开始完成')
                }

            },
            handlePlugUpload() {
                if (this.formItem.bm_url !== '') {
                    this.$Message.error('您已经上传了文件，请先删除')
                    return false;
                }
                this.$Loading.start()
                this.$Message.success('开始上传')
            },
            removePlug() {
                this.formItem.bm_url = ''
            },
            sub_ok(name){
                this.loading_edit = true
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        if(this.formItem.id === ''){
                            this.$emit('success')
                        }else{
                            this.$emit('edit_success')
                        }
                    } else {
                        this.$Message.error('表单验证失败!')
                    }
                })
                this.loading_edit = false
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
