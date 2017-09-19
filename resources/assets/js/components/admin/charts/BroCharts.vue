<template>
    <div>
        <ve-pie :data="chartData" :settings="chartSettings"></ve-pie>
    </div>
</template>

<script>
    import VePie from 'v-charts/lib/pie'
    export default {
        data() {
            return {
                chartData: {},
                chartSettings: {}
            }
        },
        mounted() {
            this._init()
        },
        methods: {
            _init() {
                axios.get('/admin/charts/bro_charts').then(res => {
                    console.log(res)
                    this.chartData = {
                        columns: res.data.columns,
                        rows: res.data.data
                    }
                    this.chartSettings = {
                        dimension: res.data.columns[0],
                        metrics: res.data.columns[1],
                        dataType: 'KMB',
                        selectedMode: 'single',
                        hoverAnimation: false,
                        radius: 100,
                        offsetY: 200
                    }
                    console.log(this.chartData)
                })


            }
        },
        components: {VePie}
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
