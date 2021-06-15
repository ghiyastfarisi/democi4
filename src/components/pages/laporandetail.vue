<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <a href="javascript:void(9)" class="button is-info is-outlined" @click="showModalEdit=!showModalEdit">Update Data</a>
            <DynamicModalForm
                v-if="showModalEdit"
                :modal-dep="{
                    title: 'Update Laporan Detail',
                    submit: 'Update Laporan',
                    modalType: 'addlaporan',
                    isEdit: true,
                    fetchEditUrl: `v1/kunjungan/${kunjunganId}?upiLabel=true`,
                    updateUrl: `v1/kunjungan/update/${kunjunganId}`,
                    extra: { kunjunganId: parseInt(kunjunganId) }
                }"
                @toggle-close="showModalEdit=!showModalEdit"
                @update-table="reload"
            />
        </div>
        <div class="column is-12">
            <DynamicBlock
                :key="compKey"
                :block-dep="{
                    ajaxUri: `v1/kunjungan/${kunjunganId}`,
                    blockType: 'laporan'
                }"
            />
        </div>
        <div class="column is-12">
            <DynamicBlock
                :key="compKey"
                :block-dep="{
                    ajaxUri: `v1/kunjungan/${kunjunganId}/file`,
                    blockType: 'laporanupload',
                    extra: {
                        kunjunganId
                    }
                }"
            />
        </div>
    </div>
</template>

<script>
import DynamicBlock from '../dynamicblock'
import DynamicModalForm from '../forms/dynamicmodalform'

export default {
    components: {
        DynamicBlock,
        DynamicModalForm
    },
    data: function() {
        return {
            compKey: 0,
            requestUpdateUrl: '',
            showModalEdit: false
        }
    },
    props: {
        kunjunganId: 0
    },
    methods: {
        reload() {
            this.compKey += 1
        }
    },
    mounted() {}
};
</script>
