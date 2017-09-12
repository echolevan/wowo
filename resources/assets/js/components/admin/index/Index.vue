<template>
    <div>
        <Row>
            <iCol span="12">
                <p class="title_charts">用户注册统计</p>
                <user-created></user-created>
            </iCol>
            <iCol span="12">
                <p class="title_charts">资源发布统计</p>
                <plug-created></plug-created>
            </iCol>
        </Row>

        <Row>
            <iCol span="12">
                <p class="title_charts">资源购买统计</p>
                <order-charts></order-charts>
            </iCol>
            <iCol span="12">
                <p class="title_charts">充值统计</p>
                <recharge-charts></recharge-charts>
            </iCol>
        </Row>

    </div>
</template>

<script>
    import UserCreated from '../charts/UserCreated'
    import PlugCreated from '../charts/PlugCreated'
    import OrderCharts from '../charts/OrderCharts'
    import RechargeCharts from '../charts/RechargeCharts'

    export default {
        data() {
            return {}
        },
        components: {UserCreated, PlugCreated, OrderCharts, RechargeCharts}
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    .title_charts
        width 100%
        text-align center
        font-size 16px
        font-weight bold

</style>
