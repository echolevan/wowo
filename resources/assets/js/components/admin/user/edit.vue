<template>
    <div>
        <Modal
                v-model="model_edit"
                title="编辑用户">
            <Form :model="formItem" :label-width="80" class="div_center from_main" ref="formItem" :rules="ruleValidate">
                <Form-item label="名称" prop="name">
                    <Input v-model="formItem.name" placeholder="请输入"  :disabled="is_disabled"></Input>
                </Form-item>
                <Form-item label="手机" prop="tel">
                    <Input v-model="formItem.tel" placeholder="请输入" :maxlength="maxlength" :disabled="is_disabled"></Input>
                </Form-item>
                <Form-item label="邮箱" prop="email">
                    <Input v-model="formItem.email" placeholder="请输入"   :disabled="is_disabled"></Input>
                </Form-item>
                <Form-item label="阵营" prop="camp">
                    <Select v-model="formItem.camp" :disabled="is_disabled">
                        <Option v-for="(v, k) in configCamp" :value="k" :key="k">{{ v }}</Option>
                    </Select>
                </Form-item>
                <Form-item label="头像" prop="avatar">
                    <!--// see img-->
                    <div class="small-upload-list" v-show="formItem.avatar !== ''">
                        <img :src="formItem.avatar">
                        <div class="small-upload-list-cover">
                            <Icon type="ios-eye-outline" @click.native="handleView(formItem.avatar)"></Icon>
                            <Icon type="ios-trash-outline" @click.native="handleRemove(1)"></Icon>
                        </div>
                    </div>
                    <!--// upload-->
                    <Upload
                            :disabled="is_disabled"
                            ref="upload"
                            :show-upload-list="false"
                            :on-success="handleSuccess"
                            type="drag"
                            :headers='{ "X-CSRF-TOKEN" : csrfToken}'
                            action="/admin/upload_tag_img"
                            style="display: inline-block;width:58px;" v-show="formItem.avatar === ''">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="camera" size="20"></Icon>
                        </div>
                    </Upload>
                </Form-item>
            </Form>
            <div slot="footer">
                <Button type="ghost" @click="is_disabled = !is_disabled">{{is_disabled ? '允许' : '禁止'}}编辑</Button>
                <Button type="primary"  :disabled="is_disabled" :loading="modal_loading" @click="edit_ok('formItem')">
                    <span>确定</span>
                </Button>
            </div>
        </Modal>

        <Modal title="查看图片" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    export default {
        data() {
            const validateAvatar = (rule, value, callback) => {
                setTimeout(() => {
                    if(this.formItem.avatar === ''){
                        callback(new Error('请上传头像'));
                    }else{
                        callback();
                    }
                }, 10);
            };
            const validateUserName = (rule, value, callback) => {
                if(this.formItem.name !== ''){
                    axios.post('/check/user_name',{name: this.formItem.name, id: this.formItem.id}).then(res => {
                        if(res.data.sta === 0){
                            callback(new Error('用户名已经存在'));
                        }else{
                            callback();
                        }
                    })
                }else{
                    callback();
                }
            };
            const validateEmail = (rule, value, callback) => {
                if(this.formItem.name !== ''){
                    axios.post('/check/user_email',{email: this.formItem.email, id: this.formItem.id}).then(res => {
                        if(res.data.sta === 0){
                            callback(new Error('邮箱已经存在'));
                        }else{
                            callback();
                        }
                    })
                }else{
                    callback();
                }
            };
            const validateTel = (rule, value, callback) => {
                if(value !== ''){
                    let tel = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                    if(!tel.test(value))
                    {
                        callback(new Error('请输入正确的手机号'));
                    }else{
                        axios.post('/check/user_tel',{tel: this.formItem.tel, id: this.formItem.id}).then(res => {
                            if(res.data.sta === 0){
                                callback(new Error('手机号已经存在'));
                            }else{
                                callback();
                            }
                        })
                    }
                }else{
                    callback();
                }
            };
            return {
                modal_loading: false,
                model_edit: false,
                visible: false,
                is_disabled: true,
                maxlength: 11,
                imgName: '',
                formItem: {
                    id: '',
                    name: '',
                    email: '',
                    tel: '',
                    camp: '',
                    avatar: ''
                },
                ruleValidate: {
                    name: [
                        {required: true, message: '姓名不能为空', trigger: 'blur'},
                        {max: 30, message: '姓名最长30', trigger: 'change'},
                        {validator: validateUserName,trigger: 'blur'},
                    ],
                    email: [
                        {required: true, message: '邮箱不能为空', trigger: 'blur'},
                        {validator: validateEmail,trigger: 'blur'},
                    ],
                    camp: [
                        {required: true, message: '阵营不能为空', trigger: 'change'},
                    ],
                    avatar: [
                        {validator: validateAvatar, required: true, trigger: 'change'},
                    ],
                    tel: [
                        {validator: validateTel, trigger: 'change'},
                    ]
                },
                csrfToken : window.Laravel.csrfToken,
                configCamp: configCamp,
            }
        },
        methods: {
            edit_ok(name) {
                this.modal_loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.$emit('subEdit');
                    }
                    this.modal_loading = false;
                })
            },
            handleSuccess (res, file) {
                this.formItem.avatar = res.url
            },
            handleView (name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove(){
                this.formItem.avatar = ''
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .small-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .small-upload-list img{
        width: 100%;
        height: 100%;
    }
    .small-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .small-upload-list:hover .small-upload-list-cover{
        display: block;
    }
    .small-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }

</style>
