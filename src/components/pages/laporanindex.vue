<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enable-add="{
                    valid: true,
                    addDep: {
                        title: 'Buat Laporan Baru',
                        submit: 'Simpan Data',
                        modalType: 'addlaporan',
                        createUrl: `v1/kunjungan/create`,
                        extra: {}
                    }
                }"
                :table-dep="{
                    fieldType: 'riwayat_kunjungan',
                    ajaxUri: `v1/kunjungan/all?getDetailPembinaMutu=true`,
                    showLimit: 10,
                    deleteUrl: 'v1/kunjungan/'
                }"
                :enable-edit="{
                    valid: true,
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
