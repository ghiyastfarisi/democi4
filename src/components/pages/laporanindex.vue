<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enableAdd="{
                    valid: true,
                    displayLink: 'Buat Laporan Baru',
                    useModal: {
                        title: 'Buat Laporan Baru',
                        submit: 'Simpan Data',
                        modalType: 'addlaporan',
                        createUrl: `v1/kunjungan/create`,
                        extra: {}
                    }
                }"
                :tableDep="{
                    ajaxUri: `v1/kunjungan/all?getDetailPembinaMutu=true`,
                    deleteUrl: 'v1/kunjungan/',
                    showLimit: 25,
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
                            fieldName: 'UPI',
                            objectField: 'nama_upi'
                        },
                        {
                            fieldName: 'Provinsi',
                            objectField: 'nama_provinsi'
                        },
                        {
                            fieldName: 'Tanggal',
                            objectField: 'tanggal_kunjungan'
                        },
                        {
                            fieldName: 'Setting',
                            type: 'setting',
                            content: [
                                {
                                    icon: 'fas fa-times',
                                    class: 'is-danger',
                                    type: 'delete',
                                    useModal: {
                                        sourceId: 'id'
                                    },
                                }
                            ]
                        }
                    ]
                }"
            />
        </div>
    </div>
</template>

<script>
import DynamicTable from '../dynamic/table'

export default {
    components: {
        DynamicTable,
    }
};
</script>
