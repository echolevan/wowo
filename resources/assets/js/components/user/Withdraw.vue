<template>
    <div>
        <Tooltip placement="top-end">
            <div slot="content">
                <p>当金币数量等同于200人民币时可申请提现</p>
                <p>(新注册用户30日内不能提现)</p>
            </div>
            <Button type="ghost" size="small" @click="withdraw">提现</Button>
        </Tooltip>

        <Modal v-model="modal1">
            <p slot="header">
                <span>提现</span>
            </p>
            <p>支付宝:{{userInfo.alipay}}</p>
            请输入提现金额：
            <InputNumber :max="Math.floor(userInfo.gold / 10)" :min="1" v-model="drawMoney"
                         @on-change="change_other"></InputNumber>
            <br>
            <p>
                您将提现 <span class="drawMoney normal_font"
                           :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
            >{{drawMoney * 10}}</span> 金币,金币余额 <span class="drawMoney normal_font"
                                                     :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
            >{{userInfo.gold - drawMoney * 10}}</span>,将会获得 <span class="drawMoney normal_font"
                                                                  :class="{'bl_font_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
            >{{drawMoney}}</span> 元
            </p>

            <div slot="footer">
                <Button type="primary" :loading="loading"
                        :class="{'bl_button_color': (userInfo && userInfo.camp && userInfo.camp === 2 ) || (!userInfo &&choice_cmap === '2')}"
                        @click="ok()">
                    <span>确认提现</span>
                </Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                modal1: false,
                loading: false,
                drawMoney: 1,
                maxMoney: 1,
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        methods: {
            withdraw() {
                axios.get('/user/check_withdraw').then(res => {
                    if (res.data.sta === 0) {
                        myDialog(res.data.msg , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    } else {
                        this.drawMoney = 1
                        this.modal1 = true
                        // tixian
                    }
                })
            },
            ok() {
                this.loading = true
                axios.post('withdraws',{drawMoney: this.drawMoney}).then(res => {
                    if(res.data.sta === 1){
                        this.modal1 = false
                        myDialog('提现成功，请等待1-3个工作日',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                        this.$store.commit('change_userInfo', res.data.info)
                    }else{
                        myDialog(res.data.msg , (this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                    }
                })
                this.loading = false
            },
            change_other() {
                if (!(/^\d+$/.test(this.drawMoney))) {
                    this.drawMoney = Math.round(this.drawMoney)
                }
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .drawMoney
        font-size 16px

</style>
