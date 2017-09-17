<template>
    <div>
        <Form ref="formTel" :model="formTel" :rules="telCustom" :label-width="100" style="width: 500px">
            <FormItem label="原手机号码" prop="oldTel" v-show="userInfo.tel !== '0'">
                <Input type="text" v-model="userInfo.tel" disabled></Input>
            </FormItem>
            <FormItem label="新手机号码" prop="newTel">
                <iCol span="16">
                    <Input type="text" placeholder="新手机号码" :maxlength="maxlength" v-model="formTel.newTel"></Input>
                </iCol>
                <iCol span="8" class="pull-right">
                    <Button type="ghost" class="pull-right" :disabled="is_dis" @click="send_msg">
                        <span v-if="!is_dis">发送验证码</span>
                        <span v-else>{{rest_time}}s后可再次发送</span>
                    </Button>
                </iCol>
                <div style="clear:both"></div>
            </FormItem>
            <FormItem label="验证码" prop="code">
                <Input type="text" placeholder="验证码" v-model="formTel.code" :maxlength="maxlengthTwo"></Input>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="handleSubmit('formTel')"
                        class="pull-right"   :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                >确定</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            const validateTel = (rule, value, callback) => {
                if(value !== ''){
                    let tel = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                    if(!tel.test(value))
                    {
                        callback(new Error('请输入正确的手机号码'));
                    }else{
                        if(this.formTel.newTel === this.userInfo.tel){
                            callback(new Error('新手机不能与原手机相同'));
                        }else{
                            axios.post('/check/user_tel',{tel: this.formTel.newTel}).then(res => {
                                if(res.data.sta === 0){
                                    callback(new Error('手机号码已存在'));
                                }else{
                                    callback();
                                }
                            })
                        }

                    }
                }else{
                    callback();
                }
            };
            return {
                formTel: {
                    newTel: '',
                    code: '',
                },
                is_dis: false,
                maxlength: 11,
                maxlengthTwo: 6,
                rest_time: 60,
                telCustom: {
                    newTel: [
                        { required:true,message:'新手机号码不能为空',trigger:'blur' },
                        {validator: validateTel, trigger: 'change'},
                        {validator: validateTel, trigger: 'blur'},
                    ],
                    code: [
                        { required:true,message:'验证码不能为空',trigger:'blur' }
                    ]
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        methods: {
            handleSubmit(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.post('user/update_tel', {tel: this.formTel.newTel, code: this.formTel.code}).then(res => {
                            if(res.data.sta === 0){
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            }else {
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                this.$store.commit('change_userInfo', res.data.info)
                                this.formTel.newTel = ''
                                this.formTel.code = ''
                            }
                        })
                    }
                })
            },
            send_msg() {
                this.$refs.formTel.validateField('newTel',(v)=>{
                    if(!v){
                        this.rest_time = 60
                        this.is_dis = true
                        this.sub_time()
                        axios.get(`user/send_msg/${this.formTel.newTel}/1`).then(res=>{
                            if(res.data.sta === 0){
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                if(res.data.timeOut){
                                    this.rest_time = res.data.timeOut
                                }
                            }
                        })
                    }
                })
            },
            sub_time(){
                this.send_msg_djs = setInterval(()=>{
                    this.rest_time --
                    this.check_is_out()
                },1000)
            },
            check_is_out(){
                if(this.rest_time <= 0){
                    clearInterval(this.send_msg_djs)
                    this.is_dis = false
                }
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
