<!--suppress ALL -->
<template>
    <div>
        <tbody v-if="info">
        <tr>
            <td width="20%">服务器当前时间</td>
            <td width="20%"><span id="stime">{{info.sysinfo.time}}</span></td>
            <td width="20%">服务器已运行时间</td>
            <td width="30%" colspan="3"><span id="uptime">{{info.sysinfo.uptime}}</span></td>
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
                <font color="#CC0000">{{info.sysinfo.memTotal}} G </font>
                , 已用
                <font color="#CC0000"><span id="UsedMemory">{{info.sysinfo.memUsed}} G</span></font>
                , 空闲
                <font color="#CC0000"><span id="FreeMemory">{{info.sysinfo.memFree}} G</span></font>
                , 使用率
                <span id="memPercent">{{info.sysinfo.memPercent}}</span>
                <div class="bar">
                    <div id="barmemPercent" class="barli_green" :style="`width:${info.sysinfo.memPercent}%`">&nbsp;
                    </div>
                </div>

                Cache化内存为 <span id="CachedMemory">{{Math.floor(info.sysinfo.memCached / 1024)}} G</span>
                , 使用率
                <span id="memCachedPercent">{{info.sysinfo.memCachedPercent}}</span>
                %	| Buffers缓冲为  <span id="Buffers">{{ Math.floor(info.sysinfo.memBuffers / 1024) }} G</span>
                <div class="bar">
                    <div id="barmemCachedPercent" class="barli_blue" :style="`width:${info.sysinfo.memCachedPercent}%`">
                        &nbsp;
                    </div>
                </div>

                真实内存使用
                <span id="memRealUsed">{{Math.floor(info.sysinfo.memRealUsed / 1024)}} G</span>
                , 真实内存空闲
                <span id="memRealFree">{{Math.floor(info.sysinfo.memRealFree / 1024)}} G</span>
                , 使用率
                <span id="memRealPercent">{{info.sysinfo.memRealPercent}}</span>
                %
                <div class="bar_1">
                    <div id="barmemRealPercent" class="barli_1" :style="`width:${info.sysinfo.memRealPercent}%`">
                        &nbsp;
                    </div>
                </div>

            </td>
        </tr>
        <tr>
            <td>系统平均负载</td>
            <td colspan="5" class="w_number"><span id="loadAvg">{{info.sysinfo.loadAvg}}</span></td>
        </tr>
        </tbody>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                networkinfo: {},
                NetInputSpeed_2: 0,
                NetInputSpeed_3: 0,
                NetOut_2: 0,
                NetOut_3: 0,
            }
        },
        props: ['info'],
        mounted() {
            setTimeout(() => {
                console.log(this.info)
            },3000)
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
    @import "../../../../common/table.css"

</style>
