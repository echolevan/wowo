<template>
    <div>
        <Modal
                v-model="modal_edit"
                :title="model_title">
            <Form :model="formItem" :label-width="100" ref="formItem" :rules="ruleValidate">
                <Form-item label="标题" prop="title">
                    <Input v-model="formItem.title" placeholder="请输入..."></Input>
                </Form-item>
                <Form-item label="下载方式" prop="type">
                    <Select v-model="formItem.type" style="width:200px">
                        <Option v-for="(v, k) in configBmDownloadType" :value="k" :key="k">{{ v }}</Option>
                    </Select>
                </Form-item>
                <Form-item label="上传"  prop="url">
                    <Upload action="/admin/upload_bm_plug"
                            :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                            :on-success="handlePlugSuccess"
                            :before-upload="handlePlugUpload"
                            :on-remove="removePlug"
                            :show-upload-list="false"
                            v-show="formItem.url === ''"
                    >
                        <Button type="ghost" icon="ios-cloud-upload-outline">上传文件</Button>
                    </Upload>
                    <p v-show="formItem.url !== ''" @click="removePlug" class="hover_hand">重新上传</p>
                </Form-item>

                <Form-item label="资源类型" prop="zy_type">
                    <Select v-model="formItem.zy_type" style="width:200px">
                        <Option v-for="(v, k) in configBmType" :value="k" :key="k">{{ v }}</Option>
                    </Select>
                </Form-item>
                <Form-item label="是否收费" v-show="formItem.type[0] < 3">
                    <i-Switch v-model="formItem.is_free" size="large" @on-change="swi">
                        <span slot="open">是</span>
                        <span slot="close">否</span>
                    </i-Switch>
                </Form-item>
                <Form-item label="价格(金币)" v-show="formItem.is_free" prop="gold">
                    <Input-number
                            :min="1"
                            v-model="formItem.gold"
                            @on-change="change_other"></Input-number>
                </Form-item>
            </Form>
            <div slot="footer">
                <Button type="primary" class="pull-right" :loading="loading_edit" @click="sub_ok('formItem')" >
                    <span>确定</span>
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
               setTimeout(() => {
                   if(this.formItem.url === ''){
                       callback(new Error('请先上传文件'));
                   }else{
                       callback();
                   }
               },10)
            };
            const validategold = (rule, value, callback) => {
                if (value === 0) {
                    if (this.formItem.is_free === true) {
                        callback(new Error('金币不能为空'));
                    } else {
                        callback();
                    }
                } else {
                    callback();
                }
            };
            return {
                configBmDownloadType: configBmDownloadType,
                configBmType: configBmType,
                csrfToken: window.Laravel.csrfToken,
                loading_edit: false,
                modal_edit: false,
                model_title: '添加资源',
                formItem: {
                    id: '',
                    title: '',
                    type: '',
                    url: '',
                    zy_type: '',
                    gold: 0,
                    is_free: false,
                },
                ruleValidate: {
                    title: [
                        {required: true, message: '标题不能为空', trigger: 'blur'},
                        {max: 60, message: '标题最长60字符', trigger: 'change'}
                    ],
                    type: [
                        {required: true, message: '下载方式不能为空'}
                    ],
                    zy_type: [
                        {required: true, message: '资源分类不能为空'}
                    ],
                    url: [
                        {required: true,validator: validateUrl}
                    ],
                    gold: [
                        {validator: validategold, trigger: 'change'}
                    ]
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
                    this.formItem.url = res.url
                    this.$Message.success('开始上传')
                }

            },
            handlePlugUpload() {
                if (this.formItem.url !== '') {
                    this.$Message.error('您已上传过文件，请先删除')
                    return false;
                }
                this.$Loading.start()
                this.$Message.success('开始上传')
            },
            removePlug() {
                this.formItem.url = ''
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
                    }
                })
                this.loading_edit = false
            },
            swi() {
                this.formItem.gold = 0
            },
            change_other() {
                if (!(/^\d+$/.test(this.formItem.gold))) {
                    this.formItem.gold = Math.round(this.formItem.gold)
                }
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
