<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="box">
                <div class="has-text-centered" v-if="showLoading">
                    <span class="icon is-large">
                        <i class="fas fa-2x fa-cog fa-spin"></i>
                    </span>
                    <span class="title is-3">Loading Data</span>
                </div>
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
                                    updateUrl: `v1/user/update/${this.uid}`,
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
                                    <a href="javascript:void(0)" @click="showEditPembinaMutu=!showEditPembinaMutu" class="button is-warning is-small">
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
                                v-if="showEditPembinaMutu"
                                v-bind:modal-dep="{
                                    title: 'Update Data',
                                    submit: 'Update',
                                    modalType: 'addpembinamutu',
                                    isEdit: true,
                                    fetchEditUrl: `v1/pembina-mutu/${this.pembinaMutuData.id}`,
                                    updateUrl: `v1/pembina-mutu/update/${this.pembinaMutuData.id}`,
                                    extra: { userId: parseInt(this.uid) }
                                }"
                                @toggle-close="showEditPembinaMutu=!showEditPembinaMutu"
                                @update-table="getData"
                            />

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
                            <div class="field">
                                <label class="label"> Deskripsi </label>
                                <div class="control">
                                    {{ pembinaMutuData.deskripsi }}
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
                        addDep: {
                            title: 'Tambah Riwayat',
                            submit: 'Submit Data',
                            modalType: 'addriwayatpendidikan',
                            createUrl: `v1/pendidikan/create/pembina-mutu/${this.pembinaMutuData.id}`,
                            extra: {
                                pembinaMutuId: parseInt(this.pembinaMutuData.id)
                            }
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
        <div class="column is-12">
            <div class="box" v-if="showJabatan">
                <div class="title is-4">Riwayat Jabatan</div>
                <DynamicTable
                    :enable-add="{
                        valid: true,
                        addDep: {
                            title: 'Tambah Riwayat',
                            submit: 'Submit Data',
                            modalType: 'addriwayatjabatan',
                            createUrl: `v1/jabatan/create/pembina-mutu/${this.pembinaMutuData.id}`,
                            extra: {
                                pembinaMutuId: parseInt(this.pembinaMutuData.id)
                            }
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
                        editDep: {
                            title: 'Update Data',
                            submit: 'Update',
                            modalType: 'addriwayatjabatan',
                            isEdit: true,
                            fetchEditUrl: `v1/jabatan/`,
                            updateUrl: `v1/jabatan/update/`,
                            extra: { pembinaMutuId: parseInt(this.pembinaMutuData.id) }
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
                        addDep: {
                            title: 'Tambah Riwayat',
                            submit: 'Submit Data',
                            modalType: 'addriwayatpelatihan',
                            createUrl: `v1/pelatihan/create/pembina-mutu/${this.pembinaMutuData.id}`,
                            extra: {
                                pembinaMutuId: parseInt(this.pembinaMutuData.id)
                            }
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
                        editDep: {
                            title: 'Update Data',
                            submit: 'Update',
                            modalType: 'addriwayatpelatihan',
                            isEdit: true,
                            fetchEditUrl: `v1/pelatihan/`,
                            updateUrl: `v1/pelatihan/update/`,
                            extra: { pembinaMutuId: parseInt(this.pembinaMutuData.id) }
                        }
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showKunjungan">
                <div class="title is-4">Riwayat Kunjungan</div>
                <NewDynamicTable
                    :tableDep="{
                        ajaxUri: `v1/kunjungan/all?getDetailPembinaMutu=true&pembinaMutuId=${this.pembinaMutuData.id}`,
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
                                fieldName: 'Nama UPI',
                                objectField: 'nama_upi',
                                link: {
                                    path: '/web/upi/get/{upi_id}',
                                    parser: [ { replace: 'upi_id', with: 'upi_id' } ]
                                }
                            },
                            {
                                fieldName: 'Provinsi',
                                objectField: 'nama_provinsi'
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
    </div>
</template>

<script>
import dayjs from 'dayjs'
import DynamicTable from '../dynamictable'
import DynamicModalForm from '../forms/dynamicmodalform'
import NewDynamicTable from '../dynamic/table'

export default {
    components: {
        DynamicTable,
        DynamicModalForm,
        NewDynamicTable
    },
    data: function() {
        return {
            showLoading: true,
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
    props: {
        ajaxUri: String,
        uid: Number,
    },
    methods: {
        getPembinaMutu(id = 0) {
            const url = `${BASE_API_URL}/v1/pembina-mutu/${id}`

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.getUserData(data.user_id)

                        this.pembinaMutuData = data

                        if (this.rpAdd && this.rpAdd.extra)  {
                            this.rpAdd.extra.pembinaMutuId = parseInt(data.id)
                        }

                        this.showPendidikan = true
                        this.showJabatan = true
                        this.showPelatihan = true
                        this.showKunjungan = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        getUserData(user_id) {
            const url = `${BASE_API_URL}/v1/user/${user_id}`

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    this.showLoading = false

                    if (data !== null) {
                        this.showData = true

                        data.role = 'pembina_mutu'
                        data.registration_date = dayjs(data.created_at).format('DD - MM - YYYY')

                        this.userData = data
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
        getData() {
            this.getPembinaMutu(this.uid)
        },
    },
    mounted() {
        let vm = this
        setTimeout(function(){ vm.getData() }, 800);
    }
};
</script>

<style>
.same-height {
    min-height: 500px !important;
    max-height: 500px !important;
}
</style>