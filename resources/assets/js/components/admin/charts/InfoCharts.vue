<!--suppress ALL -->
<template>
    <div>
        <tbody v-if="info">
        <tr>
            <th colspan="6">服务器实时数据</th>
        </tr>
        <tr>
            <td width="13%">服务器当前时间</td>
            <td width="37%"><span id="stime">{{info.sysinfo.time}}</span></td>
            <td width="13%">服务器已运行时间</td>
            <td width="37%" colspan="3"><span id="uptime">{{info.sysinfo.uptime}}</span></td>
        </tr>
        <tr>
            <td width="13%" v-if="info.sysinfo.cpu">CPU型号 [{{info.sysinfo.cpu.num}}核]</td>
            <td width="87%" colspan="5" v-if="info.sysinfo.cpu">{{info.sysinfo.cpu.model}}
            </td>
        </tr>
        <tr>
            <td>硬盘使用状况</td>
            <td colspan="5">
                总空间 {{info.dt}}&nbsp;G，
                已用 <font color="#333333"><span id="useSpace">{{info.du}}</span></font>&nbsp;G，
                空闲 <font color="#333333"><span id="freeSpace">{{info.df}}</span></font>&nbsp;G，
                使用率 <span id="hdPercent">{{info.hdp}}</span>%
                <div class="bar">
                    <div id="barhdPercent" class="barli_black" :style="`width:${info.hdp}%`">&nbsp;</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>内存使用状况</td>
            <td colspan="5">
                物理内存：共
                <font color="#CC0000">{{info.sysinfo.memtotal}} G </font>
                , 已用
                <font color="#CC0000"><span id="UsedMemory">{{info.sysinfo.memUsed}} G</span></font>
                , 空闲
                <font color="#CC0000"><span id="FreeMemory">{{info.sysinfo.Free}} G</span></font>
                , 使用率
                <span id="memPercent">{{info.sysinfo.memPercent}}</span>
                <div class="bar">
                    <div id="barmemPercent" class="barli_green" :style="`width:${info.sysinfo.memPercent}%`">&nbsp;</div>
                </div>

                Cache化内存为 <span id="CachedMemory">{{info.sysinfo.memCached}} G</span>
                , 使用率
                <span id="memCachedPercent">{{info.sysinfo.memCachedPercent}}</span>
                %	| Buffers缓冲为  <span id="Buffers">{{info.sysinfo.memBuffers}} G</span>
                <div class="bar">
                    <div id="barmemCachedPercent" class="barli_blue" :style="`width:${info.sysinfo.memCachedPercent}%`">&nbsp;</div>
                </div>

                真实内存使用
                <span id="memRealUsed">{{info.sysinfo.memRealUsed}} G</span>
                , 真实内存空闲
                <span id="memRealFree">{{info.sysinfo.memRealFree}} G</span>
                , 使用率
                <span id="memRealPercent">{{info.sysinfo.memRealPercent}}</span>
                %
                <div class="bar_1">
                    <div id="barmemRealPercent" class="barli_1" :style="`width:${info.sysinfo.memRealPercent}%`">&nbsp;</div>
                </div>

            </td>
        </tr>
        <tr>
            <td>系统平均负载</td>
            <td colspan="5" class="w_number"><span id="loadAvg">{{info.sysinfo.loadAvg}}</span></td>
        </tr>

        <tr v-if="networkinfo">

            <td width="13%" v-if="networkinfo.info">{{networkinfo.info[1][0]}} : </td>
            <td width="29%" v-if="networkinfo.NetInput">入网: <font color='#CC0000'><span>{{networkinfo.NetInput[2]}}</span></font></td>
            <td width="14%" v-if="networkinfo.NetInputSpeed">实时: <font color='#CC0000'><span>{{NetInputSpeed_2}}B/s</span></font></td>
            <td width="29%" v-if="networkinfo.NetOut">出网: <font color='#CC0000'><span>{{networkinfo.NetOut[2]}}</span></font></td>
            <td width="14%" v-if="networkinfo.NetOutSpeed">实时: <font color='#CC0000'><span>{{NetOut_3}}B/s</span></font></td>

        </tr>

        <tr v-if="networkinfo">

            <td width="13%" v-if="networkinfo.info">{{networkinfo.info[1][0]}} : </td>
            <td width="29%" v-if="networkinfo.NetInput">入网: <font color='#CC0000'><span>{{networkinfo.NetInput[3]}}</span></font></td>
            <td width="14%" v-if="networkinfo.NetInput">实时: <font color='#CC0000'><span>{{NetInputSpeed_3}}B/s</span></font></td>
            <td width="29%" v-if="networkinfo.NetInput">出网: <font color='#CC0000'><span>{{networkinfo.NetOut[3]}}</span></font></td>
            <td width="14%" v-if="networkinfo.NetInput">实时: <font color='#CC0000'><span>{{NetOut_3}}B/s</span></font></td>

        </tr>

        </tbody>
    </div>
</template>

<script>
    export default {
        data() {
           return {
               networkinfo: {

               },
               NetInputSpeed_2: 0,
               NetInputSpeed_3: 0,
               NetOut_2: 0,
               NetOut_3: 0,
           }
        },
        props: ['info'],
        mounted: function () {
            let NetInputSpeed_2 = 0
            let NetInputSpeed_3 = 0
            let NetOut_2 = 0
            let NetOut_3 = 0
            let getNet = setInterval(() => {
                axios.get('/admin/charts/networkinfo').then(res => {
                    console.log(res)
                    if (NetInputSpeed_2 === 0) {
                        this.NetInputSpeed_2 = 0
                        NetInputSpeed_2 = res.data.NetInputSpeed[2]
                    } else {
                        this.NetInputSpeed_2 = res.data.NetInputSpeed[2] - NetInputSpeed_2
                    }

                    if (NetInputSpeed_3 === 0) {
                        this.NetInputSpeed_3 = 0
                        NetInputSpeed_3 = res.data.NetInputSpeed[3]
                    } else {
                        this.NetInputSpeed_3 = res.data.NetInputSpeed[3] - NetInputSpeed_3
                    }

                    if (NetOut_2 === 0) {
                        this.NetOut_2 = 0
                        NetOut_2 = res.data.NetOut[2]
                    } else {
                        this.NetOut_2 = res.data.NetOut[2] - NetOut_2
                    }

                    if (NetOut_3 === 0) {
                        this.NetOut_3 = 0
                    } else {
                        NetOut_3 = res.data.NetOut[3]
                        this.NetOut_3 = res.data.NetOut[3] - NetOut_3
                    }

                    this.networkinfo = res.data
                })
            }, 2000)
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../../common/table.css"

</style>
