<template>
    <div>
        <ve-pie :data="chartData" :settings="chartSettings" :legend-visible="false" :height="'265px'"></ve-pie>
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
                axios.get('/admin/charts/sys_charts').then(res => {
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
                        offsetY: 130
                    }
                })
            }
        },
        components: {VePie}
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
