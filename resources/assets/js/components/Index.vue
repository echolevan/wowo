<template>
    <div>
        <v-nav v-show="$route.name !== 'index'"></v-nav>
        <div class="main content" :style="$route.name !== 'index' ? 'background-color: #fff' : '' ">
            <!--mode="out-in" enter-active-class="animated fadeIn" leave-active-class="animated zoomOutDown"-->
            <transition>
                <router-view></router-view>
            </transition>
        </div>
        <div class="footer" v-if="$route.name !== 'index'">
            <div class="foot">
                <ul>
                    <li>关于我们</li>
                    <li>|</li>
                    <li>加入我们</li>
                    <li>|</li>
                    <li><a href="mailto: shangwu@iwowcn.com">商务合作</a></li>
                </ul>
                Copyright © 2017 陕西熊猫人网络科技有限公司 嘿市网 版权所有
                <br>
                陕ICP备17015228号-1
            </div>
        </div>
        <div class="tool_bottom" v-show="$route.name !== 'index'">
            <div class="feedback hover_hand" @click="feedback_model = true">
                意见<br>反馈
            </div>
            <div class="goTop hover_hand">
                返回<br>顶部
            </div>
        </div>


        <div class="ad_form" v-show="$route.name !== 'index'">
            <div class="ad_form_left">
                <img src="/images/blcard.png" alt="">
                <img src="/images/blcard.png" alt="">
                <img src="/images/blcard.png" alt="">
            </div>
            <div class="ad_form_right">
                <img src="/images/blcard.png" alt="">
                <img src="/images/blcard.png" alt="">
                <img src="/images/blcard.png" alt="">
            </div>
        </div>
        
        
        <Modal v-model="feedback_model">
            <p slot="header" class="model_title">
             意见反馈
            </p>
            <div>
                <Form ref="formCustom" :model="formCustom" :rules="ruleCustom" :label-width="80">
                    <Form-item label="建议" prop="feedback">
                        <Input v-model="formCustom.feedback" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入您的建议"></Input>
                    </Form-item>
                    <Form-item label="昵称" prop="name">
                        <Input type="text" v-model="formCustom.name"></Input>
                    </Form-item>
                    <Form-item label="邮箱" prop="tel">
                        <Input type="text" v-model="formCustom.tel"></Input>
                    </Form-item>
                </Form>
            </div>
            <div slot="footer">
                <Button @click="go_feedback('formCustom')">确定</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import Nav from './nav/Nav.vue'
    import {mapState} from 'vuex'

    export default {
        data() {
            return {
                feedback_model: false,
                formCustom: {
                    feedback: '',
                    name: '',
                    tel: '',
                },
                ruleCustom: {
                    feedback: [
                        {required: true, message: '建议不能为空', trigger: 'blur'},
                        {max: 300, message: '建议最长300字符', trigger: 'blur'},
                        {max: 300, message: '建议最长300字符', trigger: 'blur'},
                    ]
                }
            }
        },
        computed: mapState([
            'userInfo', 'choice_cmap'
        ]),
        mounted() {
            let redirect = localStorage.getItem('redirect');
            if (redirect && redirect !== "/") {
                localStorage.setItem('redirect', "/")
                this.$router.push(redirect)
            }
            this.choice_camp()

            $('.goTop').css('opacity',0);
            $(window).on('scroll', function() {
                if ($('body').scrollTop() > 100) { /* 返回顶部按钮将在用户向下滚动100像素后出现 */
                    $('.goTop').css('opacity',1);
                } else {
                    $('.goTop').css('opacity',0);
                }
            });

            $('.goTop').click(function() {
                $("html, body").animate({scrollTop: 0}, 500);
            })


            let is_getAgentInfo = localStorage.getItem('getAgentInfo');
            if(!is_getAgentInfo){
                axios.get('/getAgentInfo')
                localStorage.setItem('getAgentInfo', "1")
            }
        },
        watch: {
            '$route'(to, from) {
                this.choice_camp()
                if(this.$router.name !== '/'){
                    $('iframe#xianliaome_window').css('display','block')
                }
            }
        },
        methods: {
            go_feedback(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        axios.put('/feedback',{data: this.formCustom}).then(res => {
                            if(res.data.sta === 1){
                                myDialog(res.data.msg,(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            }else{
                                myDialog('感谢您的建议',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            }
                            this.feedback_model = false
                            this.formCustom.feedback = ''
                            this.formCustom.name = ''
                            this.formCustom.tel = ''
                        }).catch(err => {
                            myDialog('感谢您的建议',(this.userInfo && this.userInfo.camp && this.userInfo.camp === 2 ) || (!this.userInfo && this.choice_cmap === '2') ? 'bl_button_color' : '')
                            this.feedback_model = false
                            this.formCustom.feedback = ''
                            this.formCustom.name = ''
                            this.formCustom.tel = ''
                        })
                    }
                })

            },
            choice_camp() {
                let choice_camp = window.localStorage.getItem('choice_camp')
                this.$store.commit('choice_camp', choice_camp)
            }
        },
        components: {
            'v-nav': Nav
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .main.content
        width 1240px
        padding: 15px
        margin 0 auto
    .footer
        border-top 1px solid #f5f5f5
        width 1240px
        padding: 15px
        margin 0 auto
        background-color #fff
        text-align center
        display flex
        justify-content center
        .foot
            ul
                margin 0 auto
                li
                    padding 0 5px
                    display inline-block

    .tool_bottom
        position fixed
        bottom 30px
        z-index 999999
        right 0
        div
            background rgba(0,0,0,0.5)
            color #fff
            text-align center
            margin-bottom 2px
            padding 5px


    .ad_form
        .ad_form_left
            position fixed
            left 15px
            top 0
            height 100%
            display flex
            flex-direction column
            justify-content center
            align-items center
            z-index 1400
            img
                width: 200px
                margin 15px 0
        .ad_form_right
            position fixed
            right 15px
            top 0
            height 100%
            display flex
            flex-direction column
            justify-content center
            z-index 1400
            align-items center
            img
                width: 200px
                margin 15px 0
</style>
