<template>
    <div>
        <Form ref="fromMail" :model="fromMail" :rules="mailCustom" :label-width="100" style="width: 500px">
            <FormItem label="原邮箱" prop="oldTel" v-show="userInfo.email">
                <Input type="text" v-model="userInfo.email" disabled></Input>
            </FormItem>
            <FormItem label="新邮箱" prop="newMail">
                <iCol span="16">
                    <Input type="text" placeholder="新邮箱" v-model="fromMail.newMail"></Input>
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
                <Input type="text" placeholder="验证码" v-model="fromMail.code" :maxlength="maxlengthTwo"></Input>
            </FormItem>
            <FormItem>
                <Button type="primary"
                        class="pull-right"   :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                        @click="handleSubmit('fromMail')">确定</Button>
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
                    if(this.fromMail.newMail === this.userInfo.email){
                        callback(new Error('新邮箱不能与原邮箱相同'));
                    }else{
                        axios.post('/check/user_email',{email: this.fromMail.newMail , id: this.userInfo.id}).then(res => {
                            if(res.data.sta === 0){
                                callback(new Error('邮箱已存在'));
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
                fromMail: {
                    newMail: '',
                    code: '',
                },
                is_dis: false,
                maxlengthTwo: 6,
                rest_time: 60,
                mailCustom: {
                    newMail: [
                        { required:true,message:'新邮箱不能为空',trigger:'blur' },
                        { type: 'email', message: '邮箱格式不正确', trigger: 'blur' },
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
                        axios.post('user/update_email', {email: this.fromMail.newMail, code: this.fromMail.code}).then(res => {
                            if(res.data.sta === 0){
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            }else {
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                this.$store.commit('change_userInfo', res.data.info)
                                this.fromMail.newMail = ''
                                this.fromMail.code = ''
                            }
                        })
                    }
                })
            },
            send_msg() {
                this.$refs.fromMail.validateField('newMail',(v)=>{
                    if(!v){
                        this.rest_time = 60
                        this.is_dis = true
                        this.sub_time()
                        axios.get(`user/send_email/${this.fromMail.newMail}`).then(res=>{
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
