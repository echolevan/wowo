<template>
    <div>
        <Form ref="formTel" :model="formTel" :rules="telCustom" :label-width="100" style="width: 500px">
            <FormItem label="原始手机" prop="oldTel" v-show="userInfo.tel !== '0'">
                <Input type="text" v-model="userInfo.tel" disabled></Input>
            </FormItem>
            <FormItem label="新手机" prop="newTel">
                <iCol span="16">
                    <Input type="text" placeholder="新手机" :maxlength="maxlength" v-model="formTel.newTel"></Input>
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
                <Button type="primary" @click="handleSubmit('formTel')">提交</Button>
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
                            callback(new Error('新手机不能与原始手机相同'));
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
                        { required:true,message:'新手机不能为空',trigger:'blur' },
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
                                myDialog(res.data.msg)
                            }else {
                                myDialog(res.data.msg)
                                this.$store.commit('change_userInfo', res.data.info)
                                this.formItem.newTel = ''
                                this.formItem.code = ''
                            }
                        })
                    }
                })
            },
            send_msg() {
                let tel = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                if(!tel.test(this.formTel.newTel)){
                    myDialog('请输入正确的手机号')
                    return false
                }
                this.rest_time = 60
                this.is_dis = true
                this.sub_time()
                axios.get(`user/send_msg/${this.formTel.newTel}/1`).then(res=>{
                    if(res.data.sta === 0){
                        myDialog(res.data.msg)
                        if(res.data.timeOut){
                            this.rest_time = res.data.timeOut
                        }
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
