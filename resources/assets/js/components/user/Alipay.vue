<template>
    <div>
        <Form ref="fromAlipay" :model="fromAlipay" :rules="AlipayCustom" :label-width="100" style="width: 500px">
            <FormItem label="原支付宝" prop="oldAlipay" v-show="userInfo.alipay">
                <Input type="text" v-model="userInfo.alipay" disabled></Input>
            </FormItem>
            <FormItem label="原姓名" prop="oldAlipay" v-show="userInfo.alipay_name">
                <Input type="text" v-model="userInfo.alipay_name" disabled></Input>
            </FormItem>
            <FormItem label="新支付宝" prop="newAlipay">
                <Input type="text" placeholder="手机号码或邮箱"  v-model="fromAlipay.newAlipay"></Input>
                <div style="clear:both"></div>
            </FormItem>
            <FormItem label="姓名" prop="alipayName">
                <Input type="text" placeholder="姓名"  v-model="fromAlipay.alipayName"></Input>
                <div style="clear:both"></div>
            </FormItem>
            <FormItem label="验证码" prop="code">
                <iCol span="16">
                    <Input type="text" placeholder="验证码" v-model="fromAlipay.code" :maxlength="maxlengthTwo"></Input>
                </iCol>
                <iCol span="8" class="pull-right">
                    <Button type="ghost" class="pull-right" :disabled="is_dis" @click="send_msg">
                        <span v-if="!is_dis">获取验证码</span>
                        <span v-else>{{rest_time}}s后可再次获取</span>
                    </Button>
                </iCol>
                <div style="clear:both"></div>
            </FormItem>
            <FormItem>
                <Button type="primary"
                        class="pull-right"   :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                        @click="handleSubmit('fromAlipay')">确定</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            const validateAlipay = (rule, value, callback) => {
                if(value !== ''){
                    if(this.fromAlipay.newAlipay === this.userInfo.alipay){
                        callback(new Error('新支付宝不能与原支付宝相同'));
                    }else{
                        callback();
                    }
                }else{
                    callback();
                }
            };
            return {
                fromAlipay: {
                    newAlipay: '',
                    code: '',
                    alipayName: ''
                },
                is_dis: false,
                maxlength: 11,
                maxlengthTwo: 6,
                rest_time: 60,
                AlipayCustom: {
                    newAlipay: [
                        { required:true,message:'新支付宝不能为空',trigger:'blur' },
                        {validator: validateAlipay, trigger: 'blur'},
                    ],
                    code: [
                        { required:true,message:'验证码不能为空',trigger:'blur' }
                    ],
                    alipayName: [
                        { required:true,message:'姓名不能为空',trigger:'blur' }
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
                        axios.post('user/update_Alipay', {Alipay: this.fromAlipay.newAlipay, code: this.fromAlipay.code, alipayName: this.fromAlipay.alipayName}).then(res => {
                            if(res.data.sta === 0){
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            }else {
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                                this.$store.commit('change_userInfo', res.data.info)
                                this.fromAlipay.newAlipay = ''
                                this.fromAlipay.alipayName = ''
                                this.fromAlipay.code = ''
                            }
                        })
                    }
                })
            },
            send_msg() {
                if(this.userInfo.tel === '0'){
                    myDialog('请先绑定手机号码',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    return false
                }
                this.$refs.fromAlipay.validateField('newAlipay',(v)=>{
                    if(!v){
                        this.rest_time = 60
                        this.is_dis = true
                        this.sub_time()
                        axios.get(`user/send_msg/${this.userInfo.tel}/3/${this.fromAlipay.newAlipay}`).then(res=>{
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
