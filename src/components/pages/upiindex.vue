<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enable-add="{
                    valid: false,
                    addDep: {
                        title: 'Tambah UPI',
                        submit: 'Submit Data',
                        modalType: 'addriwayatpendidikan',
                        createUrl: `v1/pendidikan/create/pembina-mutu/${this.pembinaMutuData.id}`,
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }
                }"
                :table-dep="{
                    fieldType: 'upi',
                    ajaxUri: `v1/upi/all?getLocationName=true`,
                    showLimit: 20,
                    deleteUrl: 'v1/upi/',
                    detailUrl: 'upi/get/'
                }"
                :enable-edit="{
                    valid: false,
                    editDep: {
                        title: 'Update Data',
                        submit: 'Update',
                        modalType: 'addriwayatpendidikan',
                        isEdit: true,
                        fetchEditUrl: `v1/pendidikan/`,
                        updateUrl: `v1/pendidikan/update/`,
                        extra: { pembinaMutuId: parseInt(this.pembinaMutuData.id) }
                    }
                }"
            />
        </div>
    </div>
</template>

<script>
import DynamicTable from '../dynamictable'
import DynamicModalForm from '../forms/dynamicmodalform'

export default {
    components: {
        DynamicTable,
        DynamicModalForm,
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
