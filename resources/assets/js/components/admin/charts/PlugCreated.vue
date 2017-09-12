<template>
    <div>
        <DatePicker type="daterange" placeholder="选择日期" v-model="time"></DatePicker>
        <Button type="primary" @click="_init">确认</Button>
        <ve-line :data="chartData"></ve-line>
    </div>
</template>

<script>
    import VeLine from 'v-charts/lib/line'

    export default {
        data() {
            return {
                chartData: [],
                time: ''
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.post('/admin/charts/plugCreated', {time: this.time}).then(res => {
                    this.chartData = {
                        columns: res.data.columns,
                        rows: res.data.data
                    }
                })


            }
        },
        components: {VeLine}
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
