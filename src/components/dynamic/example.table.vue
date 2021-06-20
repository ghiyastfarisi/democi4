// this is example page of using table.vue
<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enableAdd="{
                    valid: true,
                    displayLink: 'Buat UPI Baru',
                    useLink: {
                        title: 'Form User Pembina Mutu',
                        submit: 'Submit Data',
                        modalType: 'userform',
                        createUrl: `v1/pendidikan/create/pembina-mutu/${this.pembinaMutuData.id}`,
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }
                }"
                :tableDep="{
                    ajaxUri: `v1/upi/all?getLocationName=true`,
                    showLimit: 25,
                    renderObject: [
                        {
                            fieldName: 'Nama',
                            objectField: 'nama_perusahaan',
                            link: {
                                path: '/web/upi/get/{id}',
                                parser: [ { replace: 'id', with: 'id' } ]
                            }
                        },
                        {
                            fieldName: 'NIB',
                            objectField: 'nib'
                        },
                        {
                            fieldName: 'Kecamatan',
                            objectField: 'location_district_name'
                        },
                        {
                            fieldName: 'Kota Kabupaten',
                            objectField: 'location_regency_name'
                        },
                        {
                            fieldName: 'Setting',
                            type: 'setting',
                            content: [
                                {
                                    text: 'View',
                                    class: 'is-info is-light',
                                    useLink: {
                                        path: '/web/upi/get/{id}',
                                        parser: [ { replace: 'id', with: 'id' } ]
                                    },
                                },
                                {
                                    icon: 'fas fa-edit',
                                    class: 'is-primary',
                                    type: 'edit',
                                    useModal: {
                                        sourceId: 'id'
                                    },
                                },
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
    },
    data: function() {
        return {
            showError: false,
            showData: false,
            showPendidikan: false,
            showJabatan: false,
            showPelatihan: false,
            showKunjungan: false,
            showEditAkun: false,
            showEditPembinaMutu: false,
            userData: {
                role: 'pembina_mutu'
            },
            pembinaMutuData: {
                nama_lengkap: '',
                nip: '',
                no_hp: '',
                keahlian: '',
                deskripsi: '-'
            },
        }
    },
    props: {},
    methods: {},
    mounted() {}
};
</script>
