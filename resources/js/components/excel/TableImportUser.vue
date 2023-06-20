<template>
    <b-modal id="modalImportUser" size="xl" title="Cập nhật nhân viên" height="90%">
        <customize-excel :datas="datas"></customize-excel>
    </b-modal>
</template>

<script>
import CustomizeExcel from './CustomizeExcel.vue';
export default {
    props: {
        datas: Array,
    },
    components: { CustomizeExcel },

}
</script>
