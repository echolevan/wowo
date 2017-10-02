<template>
    <div style="width: 500px" >
        <Form ref="formPassword" :model="formPassword" :rules="ruleFormPassword" label-position="left"
              :label-width="100">
            <Form-item label="原密码" prop="old_password">
                <Input type="password" v-model="formPassword.old_password"></Input>
            </Form-item>
            <Form-item label="新密码" prop="password">
                <Input type="password" v-model="formPassword.password"></Input>
            </Form-item>
            <Form-item label="确认密码" prop="passwordCheck">
                <Input type="password" v-model="formPassword.passwordCheck"
                       @keyup.13.native="rest_password('formPassword')"></Input>
            </Form-item>
        </Form>
        <Button type="primary" :loading="loading" class="pull-right" @click="rest_password('formPassword')"
                :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}">
            <span v-if="!loading">确定</span>
            <span v-else>Loading...</span>
        </Button>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {

            const validatePassword = (rule, value, callback) => {
                if (value !== '') {
                    if (value !== this.formPassword.password) {
                        callback(new Error("密码不一致"));
                    } else {
                        callback();
                    }
                }
            };
            const validateCheckPassword = (rule, value, callback) => {
                if (value !== '') {
                    axios.post('user/check_password', {password: value}).then((res) => {
                        if (res.data.sta === 0) {
                            callback(new Error(res.data.msg));
                        } else {
                            callback();
                        }
                    });
                }
            };
            return {
                loading: false,
                formPassword: {
                    old_password: '',
                    password: '',
                    passwordCheck: ''
                },
                ruleFormPassword: {
                    old_password: [
                        {required: true, message: '请输入原密码', trigger: 'blur'},
                        {validator: validateCheckPassword, trigger: 'blur'}
                    ],
                    password: [
                        {required: true, message: '请输入新密码', trigger: 'blur'},
                        {min: 8, max: 30, message: '密码长度8-30位', trigger: 'change'}
                    ],
                    passwordCheck: [
                        {required: true, message: '请输入确认密码', trigger: 'blur'},
                        {validator: validatePassword, trigger: 'blur'}
                    ]
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        methods: {
            rest_password(name) {
                this.loading = true;
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.post('user/update_password', {password: this.formPassword.password}).then((res) => {
                            if (res.data.sta === 0) {
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            } else {
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                this.formPassword.old_password = ''
                                this.formPassword.password = ''
                                this.formPassword.passwordCheck = ''
                            }
                            this.loading = false;
                        }).catch((err) => {
                            this.loading = false;
                            myDialog('出错了，请联系管理员',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                        });
                    } else {
                        this.loading = false;
                    }
                })
            }
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
