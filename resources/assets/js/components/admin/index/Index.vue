<template>
    <div>
        <div class="row sb_index">
            <div class="col-sm-6 col-md-3">
                <div class="panel panel-success panel-stat">
                    <div class="panel-heading" style="color: #fff;">

                        <div class="stat">
                            <div class="row">
                                <div class="col-xs-4">
                                    <img src="/images/admin/is-user.png" alt="">
                                </div>
                                <div class="col-xs-8">
                                    <small class="stat-label">注册用户</small>
                                    <h1>{{info.user_count}}</h1>
                                </div>
                            </div><!-- row -->

                            <div class="mb15"></div>
                            <br/>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">联盟用户</small>
                                    <h4>{{info.user_lm}}</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stat-label">部落用户</small>
                                    <h4>{{info.user_bl}}</h4>
                                </div>
                            </div><!-- row -->
                        </div><!-- stat -->

                    </div><!-- panel-heading -->
                </div><!-- panel -->
            </div><!-- col-sm-6 -->

            <div class="col-sm-6 col-md-3">
                <div class="panel panel-danger panel-stat">
                    <div class="panel-heading" style="color: #fff;">

                        <div class="stat">
                            <div class="row">
                                <div class="col-xs-4">
                                    <img src="/images/admin/is-user.png" alt="">
                                </div>
                                <div class="col-xs-8">
                                    <small class="stat-label">付费用户</small>
                                    <h1>{{info.ff}}</h1>
                                </div>
                            </div><!-- row -->
                            <div class="mb15"></div>
                            <br/>

                            <div v-for="(v, k) in lv" style="float: left;padding: 0 15px">
                                <small class="stat-label">Lv{{k + 1}}</small>
                                <h4 v-if='info.lv[k]'>{{info.lv[k].length}}</h4>
                                <h4 v-else>0</h4>
                            </div>
                            <div style="clear: both"></div>
                        </div><!-- stat -->

                    </div><!-- panel-heading -->
                </div><!-- panel -->
            </div><!-- col-sm-6 -->

            <div class="col-sm-6 col-md-3">
                <div class="panel panel-primary panel-stat">
                    <div class="panel-heading" style="color: #fff;
    background-color: #428bca;
    border-color: #428bca;">

                        <div class="stat">
                            <div class="row">
                                <div class="col-xs-4">
                                    <img src="/images/admin/is-document.png" alt="">
                                </div>
                                <div class="col-xs-8">
                                    <small class="stat-label">资源总数</small>
                                    <h1>{{info.plug_count}}</h1>
                                </div>
                            </div><!-- row -->

                            <div class="mb15"></div>
                            <br/>

                            <div class="col-xs-4">
                                <small class="stat-label">游戏插件</small>
                                <h4>{{info.plug_plug}}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stat-label">WA</small>
                                <h4>{{info.plug_wa}}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stat-label">TMW</small>
                                <h4>{{info.plug_tmw}}</h4>
                            </div>
                            <div style="clear: both"></div>
                        </div><!-- stat -->

                    </div><!-- panel-heading -->
                </div><!-- panel -->
            </div><!-- col-sm-6 -->

            <div class="col-sm-6 col-md-3">
                <div class="panel panel-dark panel-stat">
                    <div class="panel-heading" style="color: #fff;">

                        <div class="stat">
                            <div class="row">
                                <div class="col-xs-4">
                                    <img src="/images/admin/is-money.png" alt="">
                                </div>
                                <div class="col-xs-8">
                                    <small class="stat-label">资金总额</small>
                                    <h1>{{info.recharge - info.draws}}</h1>
                                </div>
                            </div><!-- row -->

                            <div class="mb15"></div>
                            <br/>

                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">充值总额</small>
                                    <h4>{{info.recharge}}</h4>
                                </div>

                                <div class="col-xs-6">
                                    <small class="stat-label">提现总额</small>
                                    <h4>{{info.draws}}</h4>
                                </div>
                            </div><!-- row -->

                        </div><!-- stat -->

                    </div><!-- panel-heading -->
                </div><!-- panel -->
            </div><!-- col-sm-6 -->
        </div>

        <Row>
            <iCol span="12">
                <Button type="primary" @click="ref_s" style="margin-bottom: 30px">刷新服务器状态</Button>
                <info-charts :info="info.service_info"></info-charts>
            </iCol>
            <iCol span="6">
                <p class="title_charts">设备类型</p>
                <sys-charts></sys-charts>
            </iCol>
            <iCol span="6">
                <p class="title_charts">浏览器类型</p>
                <bro-charts></bro-charts>
            </iCol>
        </Row>


        <Row>
            <iCol span="12">
                <p class="title_charts">用户注册</p>
                <user-created></user-created>
            </iCol>
            <iCol span="12">
                <p class="title_charts">用户登录</p>
                <login-charts></login-charts>
            </iCol>

        </Row>

        <Row>
            <iCol span="12">
                <p class="title_charts">资源发布</p>
                <plug-created></plug-created>
            </iCol>
            <iCol span="12">
                <p class="title_charts">资源购买</p>
                <order-charts></order-charts>
            </iCol>
        </Row>

        <Row>
            <iCol span="12">
                <p class="title_charts">充值</p>
                <recharge-charts></recharge-charts>
            </iCol>
            <iCol span="12">
                <p class="title_charts">提现</p>
                <draws-charts></draws-charts>
            </iCol>
        </Row>

    </div>
</template>

<script>
    import UserCreated from '../charts/UserCreated'
    import PlugCreated from '../charts/PlugCreated'
    import OrderCharts from '../charts/OrderCharts'
    import RechargeCharts from '../charts/RechargeCharts'
    import LoginCharts from '../charts/LoginCharts'
    import DrawsCharts from '../charts/DrawsCharts'
    import InfoCharts from '../charts/InfoCharts'
    import SysCharts from '../charts/SysCharts'
    import BroCharts from '../charts/BroCharts'

    export default {
        data() {
            return {
                info: {

                },
                lv: [],
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get('/admin/charts/index').then(res => {
                    this.info = res.data.res
                    this.lv = res.data.lv
                })
            },
            ref_s(){
                axios.get('/admin/charts/service_info').then(res => {
                    this.info.service_info = res.data
                })
            }
        },
        components: {UserCreated, PlugCreated, OrderCharts, RechargeCharts, LoginCharts, DrawsCharts,InfoCharts,SysCharts,BroCharts}
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .title_charts
        width 100%
        text-align center
        font-size 16px
        font-weight bold

    .panel {
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        background: #fcfcfc;
        border: 0;
        -moz-box-shadow: 0 3px 0 rgba(12, 12, 12, 0.03);
        -webkit-box-shadow: 0 3px 0 rgba(12, 12, 12, 0.03);
        box-shadow: 0 3px 0 rgba(12, 12, 12, 0.03);
    }

    .panel-heading,
    .panel-footer {
        background: #fff;
        border-color: #eee;
    }

    .panel-heading {
        padding: 20px;
    }

    .panel-default > .panel-heading {
        border-color: #e7e7e7;
        background-color: #fff;
    }

    .panel-heading-empty {
        background: none;
        border-bottom: 0;
    }

    .panel-heading p {
        font-size: 13px;
        margin-top: 10px;
        margin-bottom: 0;
        color: #77818e;
    }

    .panel-primary .panel-heading,
    .panel-success .panel-heading,
    .panel-warning .panel-heading,
    .panel-danger .panel-heading,
    .panel-info .panel-heading {
        border-bottom: 0;
    }

    .panel-success .panel-heading {
        background-color: #1CAF9A;
    }

    .panel-warning .panel-heading {
        background-color: #F0AD4E;
    }

    .panel-danger .panel-heading {
        background-color: #D9534F;
    }

    .panel-info .panel-heading {
        background-color: #5BC0DE;
    }

    .panel-dark .panel-heading {
        background-color: #1d2939;
    }

    .panel-btns a {
        margin-left: 8px;
        float: right;
        color: #000;
        display: inline-block;
        font-weight: bold;
        opacity: 0.4;
        font-size: 16px;
        -moz-transition: all 0.2s ease-out 0s;
        -webkit-transition: all 0.2s ease-out 0s;
        transition: all 0.2s ease-out 0s;
    }

    .panel-btns a.minimize {
        font-size: 18px;
    }

    .panel-btns a:hover {
        text-decoration: none;
        opacity: 0.5;
        cursor: pointer;
    }

    .panel-btns a.minimize:active,
    .panel-btns a.minimize:focus {
        text-decoration: none;
    }

    .panel-title {
        font-size: 18px;
        color: #111;
        /*font-family: 'LatoBold'; */
    }

    .panel-title-alt {
        font-size: 13px;
        font-family: 'LatoBold';
        text-transform: uppercase;
        margin: 0;
    }

    .panel-title-alt + p {
        margin-top: 5px !important;
    }

    .panel-primary .panel-title,
    .panel-success .panel-title,
    .panel-warning .panel-title,
    .panel-danger .panel-title,
    .panel-info .panel-title,
    .panel-dark .panel-title {
        color: #fff;
    }

    .panel-success .panel-btns a,
    .panel-danger .panel-btns a,
    .panel-dark .panel-btns a {
        color: #fff;
    }

    .panel-body {
        padding: 20px;
    }

    .panel-body-nopadding {
        padding: 0;
    }

    .panel-footer {
        padding: 20px;
    }

    .panel-footer:after {
        clear: both;
        display: block;
        content: '';
    }

    .panel-heading .nav-tabs {
        margin-bottom: -20px;
        border-bottom: 0;
    }

    .panel-heading .nav-tabs li {
        margin-right: 5px;
    }

    .panel-heading .nav-tabs li a {
        color: #666;
    }

    .panel-heading .nav-tabs li a:hover,
    .panel-heading .nav-tabs li a:active,
    .panel-heading .nav-tabs li a:focus {
        background-color: #fff;
        border-color: #fff;
        border-bottom: 0;
        color: #111;
    }

    .panel-heading .nav-tabs li.active a {
        background: #fcfcfc;
        color: #111;
    }

    .panel-heading .nav-tabs li.active a:hover,
    .panel-heading .nav-tabs li.active a:active,
    .panel-heading .nav-tabs li.active a:focus {
        background: #fcfcfc;
        color: #111;
        border: 1px solid #ddd;
        border-bottom-color: #fcfcfc;
    }

    .panel-heading .nav-justified {
        margin-bottom: -21px;
    }

    .panel-heading .nav-justified li a {
        border-bottom: 0;
    }

    .panel-alt .panel-heading {
        padding: 20px 20px;
    }

    .panel-alt .panel-title {
        font-size: 13px;
        text-transform: uppercase;
        font-family: 'LatoBold';
    }

    .panel-alt .panel-heading p {
        margin-top: 5px;
        line-height: 18px;
    }

    .panel-alt .panel-btns a {
        margin-top: -3px;
    }

    .panel-alt .panel-footer {
        padding: 15px;
    }

    .panel-alt .panel-body {
        padding: 15px;
    }

    .panel-primary .panel-heading p,
    .panel-warning .panel-heading p,
    .panel-success .panel-heading p,
    .panel-danger .panel-heading p,
    .panel-info .panel-heading p {
        color: #fff;
        opacity: 0.6;
    }

</style>
