<template>
    <div>
        <DatePicker type="daterange" placeholder="选择日期" v-model="time"></DatePicker>
        <Button type="primary" @click="___init">确定</Button>
        <ve-line :data="chartData" :settings="chartSettings"></ve-line>
    </div>
</template>

<script>
    import VeLine from 'v-charts/lib/line'

    export default {
        data() {
            return {
                chartData: [],
                chartSettings: {},
                time: ''
            }
        },
        mounted() {
            this.___init()
        },
        methods: {
            ___init() {
                axios.post('/admin/charts/draws', {time: this.time}).then(res => {
                    this.chartData = {
                        columns: res.data.columns,
                        rows: res.data.data
                    }
                    this.chartSettings = {
                        area: true
                    }
                })


            }
        },
        components: {VeLine}
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
