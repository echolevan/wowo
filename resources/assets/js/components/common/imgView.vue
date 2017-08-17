<template>
    <div>
        <img :src="img"  @click="handleView(img)" class="view_img" alt="">
        <Modal title="查看图片" v-model="visible">
            <img :src="imgName" v-if="visible" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                imgName: '',
                visible: false,
            }
        },
        props: ['img'],
        methods: {
            handleView (imgName) {
                this.imgName = imgName;
                this.visible = true;
            },
        }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">


</style>
