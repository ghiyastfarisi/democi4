<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="box">
                <div v-cloak v-if="showError" class="notification is-danger">
                    <span class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    <span>
                        User not found
                    </span>
                </div>
                <div v-cloak v-if="showData" class="columns">
                    <div class="column is-half">
                        <div class="block">
                            <nav class="level">
                                <div class="level-left">
                                    <p class="title is-4 is-spaced">Akun</p>
                                </div>
                                <div class="level-right">
                                    <a href="javascript:void(0)" @click="showEditAkun=!showEditAkun" class="button is-warning is-small">
                                        <span class="icon">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span>
                                            Update
                                        </span>
                                    </a>
                                </div>
                            </nav>
                            <DynamicModalForm
                                v-if="showEditAkun"
                                v-bind:modal-dep="{
                                    title: 'Update Akun',
                                    submit: 'Update',
                                    modalType: 'adduser',
                                    isEdit: true,
                                    fetchEditUrl: `v1/user/${this.uid}`,
                                    updateUrl: 'v1/user/update',
                                    extra: { userId: this.uid }
                                }"
                                @toggle-close="showEditAkun=!showEditAkun"
                                @update-table="getData"
                            />

                            <div class="field">
                                <label class="label"> Username </label>
                                <div class="control">
                                    {{ userData.username }}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> Login Status </label>
                                <div class="control">
                                    {{ userData.login_status}}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> Registration Date </label>
                                <div class="control">
                                    {{ userData.registration_date }}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> Role </label>
                                <div class="control">
                                    {{ userData.role }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="block">
                            <nav class="level">
                                <div class="level-left">
                                    <p class="title is-4 is-spaced">Pembina Mutu</p>
                                </div>
                                <div class="level-right">
                                    <a href="javascript:void(0)" class="button is-warning is-small">
                                        <span class="icon">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span>
                                            Update
                                        </span>
                                    </a>
                                </div>
                            </nav>
                            <div class="field">
                                <label class="label"> Nama Lengkap </label>
                                <div class="control">
                                    {{ pembinaMutuData.nama_lengkap }}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> NIP </label>
                                <div class="control">
                                    {{ pembinaMutuData.nip }}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> Email </label>
                                <div class="control">
                                    {{ userData.username }}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> Keahlian </label>
                                <div class="control">
                                    {{ pembinaMutuData.keahlian }}
                                </div>
                            </div>
                            <div class="field">
                                <label class="label"> No Handphone </label>
                                <div class="control">
                                    {{ pembinaMutuData.no_hp }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showPendidikan">
                <div class="title is-4">Riwayat Pendidikan</div>
                <DynamicTable
                    :enable-add="{
                        valid: true,
                        title: 'Tambah Riwayat',
                        modalTitle: 'Form Riwayat Pendidikan',
                        modalType: 'addriwayatpendidikan',
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }"
                    :table-dep="{
                        fieldType: 'pm_pendidikan',
                        ajaxUri: `v1/pendidikan/pembina-mutu?pembina_mutu_id=${this.pembinaMutuData.id}`,
                        showLimit: 3,
                        deleteUrl: 'v1/pendidikan/'
                    }"
                    :enable-edit="{
                        valid: true,
                        title: 'Tambah Riwayat',
                        modalTitle: 'Form Riwayat Pendidikan',
                        modalType: 'addriwayatpendidikan',
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showJabatan">
                <div class="title is-4">Riwayat Jabatan</div>
                <DynamicTable
                    :enable-add="{
                        valid: true,
                        title: 'Tambah Riwayat',
                        modalTitle: 'Form Riwayat Jabatan',
                        modalType: 'addriwayatjabatan',
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }"
                    :table-dep="{
                        fieldType: 'pm_jabatan',
                        ajaxUri: `v1/jabatan/pembina-mutu?pembina_mutu_id=${this.pembinaMutuData.id}`,
                        showLimit: 3,
                        deleteUrl: 'v1/jabatan/'
                    }"
                    :enable-edit="{
                        valid: true,
                        title: 'Tambah Riwayat',
                        modalTitle: 'Form Riwayat Jabatan',
                        modalType: 'addriwayatjabatan',
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showPelatihan">
                <div class="title is-4">Riwayat Pelatihan</div>
                <DynamicTable
                    :enable-add="{
                        valid: true,
                        title: 'Tambah Riwayat',
                        modalTitle: 'Form Riwayat Pelatihan',
                        modalType: 'addriwayatpelatihan',
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }"
                    :table-dep="{
                        fieldType: 'pm_pelatihan',
                        ajaxUri: `v1/pelatihan/pembina-mutu?pembina_mutu_id=${this.pembinaMutuData.id}`,
                        showLimit: 3,
                        deleteUrl: 'v1/pelatihan/'
                    }"
                    :enable-edit="{
                        valid: true,
                        title: 'Tambah Riwayat',
                        modalTitle: 'Form Riwayat pelatihan',
                        modalType: 'addriwayatpelatihan',
                        extra: {
                            pembinaMutuId: parseInt(this.pembinaMutuData.id)
                        }
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showKunjungan">
                <div class="title is-4">Riwayat Kunjungan</div>
            </div>
        </div>
    </div>
</template>

<script>
var dayjs = require('dayjs')
var DynamicTable = require('./dynamictable').default
var DynamicModalForm = require('./forms/dynamicmodalform').default

module.exports = {
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
            userData: {
                role: 'pembina_mutu'
            },
            pembinaMutuData: {
                nama_lengkap: '',
                nip: '',
                no_hp: '',
                keahlian: ''
            },
        }
    },
    props: {
        ajaxUri: String,
        uid: Number,
    },
    methods: {
        getPembinaMutu(id = 0) {
            const url = BASE_API_URL + `/v1/pembina-mutu/user/` + id

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.pembinaMutuData = data

                        if (this.rpAdd && this.rpAdd.extra)  {
                            this.rpAdd.extra.pembinaMutuId = parseInt(data.id)
                        }

                        this.showPendidikan = true
                        this.showJabatan = true
                        this.showPelatihan = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        getData() {
            const url = BASE_API_URL + this.ajaxUri

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.showData = true

                        data.role = 'pembina_mutu'
                        data.registration_date = dayjs(data.created_at).format('DD - MM - YYYY')

                        this.userData = data

                        this.getPembinaMutu(this.uid)
                    } else {
                        this.showError = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
    },
    mounted() {
        let vm = this
        vm.getData()
    }
};
</script>

<style>
.same-height {
    min-height: 500px !important;
    max-height: 500px !important;
}
</style>