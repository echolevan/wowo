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
                总空间 <span style="color: #CC0000">{{info.dt.toFixed(3)}}&nbsp;G</span>，
                已用 <font color="#CC0000"><span id="useSpace">{{info.du.toFixed(3)}}</span></font>&nbsp;G，
                空闲 <font color="#CC0000"><span id="freeSpace">{{info.df.toFixed(3)}}</span></font>&nbsp;G，
                使用率 <span id="hdPercent" class="use_class">{{info.hdp}}</span>%
                <div class="bar">
                    <div id="barhdPercent" class="barli_black" :style="`width:${info.hdp}%`">&nbsp;</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>内存使用状况</td>
            <td colspan="5">
                物理内存：共
                <font color="#CC0000">{{ (info.sysinfo.memTotal / 1024).toFixed(3) }} G </font>
                , 已用
                <font color="#CC0000"><span id="UsedMemory">{{ (info.sysinfo.memUsed / 1024).toFixed(3) }} G</span></font>
                , 空闲
                <font color="#CC0000"><span id="FreeMemory">{{ (info.sysinfo.memFree / 1024).toFixed(3)}} G</span></font>
                , 使用率
                <span id="memPercent " class="use_class">{{info.sysinfo.memPercent}}%</span>
                    <div id="barmemPercent" class="barli_green" :style="`width:${info.sysinfo.memPercent}%`">&nbsp;
                    </div>

                Cache化内存为 <span id="CachedMemory">{{ (info.sysinfo.memCached / 1024).toFixed(3) }} G</span>
                , 使用率
                <span id="memCachedPercent " class="use_class">{{info.sysinfo.memCachedPercent}}</span>
                %	| Buffers缓冲为  <span id="Buffers">{{ (info.sysinfo.memBuffers / 1024).toFixed(3) }} G</span>
                    <div id="barmemCachedPercent" class="barli_blue" :style="`width:${info.sysinfo.memCachedPercent}%`">
                        &nbsp;
                    </div>

                真实内存使用
                <span id="memRealUsed">{{(info.sysinfo.memRealUsed / 1024).toFixed(3)}} G</span>
                , 真实内存空闲
                <span id="memRealFree">{{(info.sysinfo.memRealFree / 1024).toFixed(3)}} G</span>
                , 使用率
                <span id="memRealPercent " class="use_class">{{info.sysinfo.memRealPercent}}</span>
                %
                <div id="barmemRealPercent" class="barli_1" :style="`width:${info.sysinfo.memRealPercent}%`">
                    &nbsp;
                </div>

            </td>
        </tr>
        <tr>
            <td>系统平均负载</td>
            <td colspan="5" class="w_number"><span id="loadAvg" style="color: #f800fe;">{{info.sysinfo.loadAvg}}</span></td>
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
