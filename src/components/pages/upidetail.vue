<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <a class="button is-info is-outlined" :href="requestUpdateUrl">Request Update Data</a>
        </div>
        <div class="column is-12">
            <DynamicBlock
                v-bind:block-dep="{
                    ajaxUri: `v1/upi/${this.uid}?getLocation=true`,
                    blockType: 'upi'
                }"
            />
        </div>
        <div class="column is-12">
            <DynamicBlock
                v-bind:block-dep="{
                    ajaxUri: `v1/produksi/upi/${this.uid}?getProdukName=true&getProdukProduksi=true&getPemasaran=true`,
                    blockType: 'upi_produksi'
                }"
            />
        </div>
        <div class="column is-6">
            <DynamicBlock
                v-bind:block-dep="{
                    ajaxUri: `v1/sarpras/upi/${this.uid}`,
                    blockType: 'upi_sarpras'
                }"
            />
        </div>
        <div class="column is-6">
            <DynamicBlock
                v-bind:block-dep="{
                    ajaxUri: `v1/tenaga-kerja/upi/${this.uid}`,
                    blockType: 'upi_tenaga_kerja'
                }"
            />
        </div>
        <div class="column is-12">
            <div class="box" v-if="showPembinaan">
                <div class="title is-4">Riwayat Pembinaan</div>
                <DynamicTable
                    :enable-add="{
                        valid: false,
                        addDep: {}
                    }"
                    :table-dep="{
                        fieldType: 'riwayat_pembinaan',
                        ajaxUri: `v1/kunjungan/all?getDetailPembinaMutu=true&upiId=${this.uid}`,
                        showLimit: 10,
                        deleteUrl: 'v1/kunjungan/'
                    }"
                    :enable-edit="{
                        valid: false,
                        editDep: {}
                    }"
                />
            </div>
        </div>
    </div>
</template>
<script>
import DynamicBlock from '../dynamicblock'
import DynamicTable from '../dynamictable'

export default {
    components: {
        DynamicBlock,
        DynamicTable
    },
    data: function() {
        return {
            requestUpdateUrl: `${BASE_URL}/web/upi/edit/${this.uid}`,
            showError: false,
            showData: false,
            showPembinaan: false
        }
    },
    props: {
        ajaxUri: String,
        uid: Number,
    },
    methods: {},
    mounted() {
        this.showPembinaan = true
    }
}
</script>
