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
        <div class="column is-half-desktop">
            <DynamicBlock
                v-bind:block-dep="{
                    ajaxUri: `v1/sarpras/upi/${this.uid}`,
                    blockType: 'upi_sarpras'
                }"
            />
        </div>
        <div class="column is-half-desktop">
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
                <NewDynamicTable
                    :tableDep="{
                        ajaxUri: `v1/kunjungan/all?getDetailPembinaMutu=true&upiId=${this.uid}`,
                        showLimit: 2,
                        renderObject: [
                            {
                                fieldName: 'Kegiatan',
                                objectField: 'kegiatan',
                                link: {
                                    path: '/web/laporan/get/{id}',
                                    parser: [ { replace: 'id', with: 'id' } ]
                                }
                            },
                            {
                                fieldName: 'Nama Pembina Mutu',
                                objectField: 'nama_pembina_mutu',
                                link: {
                                    path: '/web/pembina-mutu/get/{pembina_mutu_id}',
                                    parser: [ { replace: 'pembina_mutu_id', with: 'pembina_mutu_id' } ]
                                }
                            },
                            {
                                fieldName: 'Unit Kerja',
                                objectField: 'unit_kerja_terakhir'
                            },
                            {
                                fieldName: 'Tanggal Kunjungan',
                                objectField: 'tanggal_kunjungan'
                            }
                        ]
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showPembinaan">
                <div class="title is-4">Permintaan Perubahan Data</div>
                <NewDynamicTable
                    :tableDep="{
                        ajaxUri: `v1/upi/all/perubahan-upi?upi_id=${this.uid}`,
                        showLimit: 10,
                        renderObject: [
                            {
                                fieldName: 'Link',
                                type: 'setting',
                                content: [
                                    {
                                        text: 'Lihat data perubahan',
                                        class: 'is-success is-outlined',
                                        useLink: {
                                            path: '/web/upi/get/{id}',
                                            parser: [ { replace: 'id', with: 'id' } ]
                                        },
                                    },
                                ]
                            },
                            {
                                fieldName: 'Status',
                                objectField: 'status'
                            },
                            {
                                fieldName: 'Tanggal Permintaan',
                                objectField: 'created_at'
                            },
                            {
                                fieldName: 'Pembina Mutu',
                                objectField: 'nama_pembina_mutu'
                            },
                            {
                                fieldName: 'Kunjungan Terkait',
                                objectField: 'nama_kunjungan'
                            }
                        ]
                    }"
                />
            </div>
        </div>
    </div>
</template>
<script>
import DynamicBlock from '../dynamicblock'
import NewDynamicTable from '../dynamic/table'

export default {
    components: {
        DynamicBlock,
        NewDynamicTable
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
