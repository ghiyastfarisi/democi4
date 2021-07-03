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
                                v-bind:modalDep="{
                                    title: 'Update Akun',
                                    submit: 'Update',
                                    modalType: 'userform',
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
                                <figure class="image">
                                    <img v-if="pembinaMutuData.foto_profil!=''" :src="pembinaMutuData.foto_profil" style="max-width:180px;max-height:180px;">
                                </figure>
                            </div>
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
                    :tableDep="{
                        ajaxUri: `v1/pendidikan/pembina-mutu?pembina_mutu_id=${this.pembinaMutuData.id}`,
                        showLimit: 3,
                        renderObject: [
                            {
                                fieldName: 'Jenjang',
                                objectField:'jenjang'
                            },
                            {
                                fieldName: 'Institusi Pendidikan',
                                objectField:'institusi_pendidikan'
                            },
                            {
                                fieldName: 'Program Studi',
                                objectField:'program_studi'
                            },
                            {
                                fieldName: 'Tahun Lulus',
                                objectField:'tahun_lulus'
                            }
                        ]
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showJabatan">
                <div class="title is-4">Riwayat Jabatan</div>
                <DynamicTable
                    :tableDep="{
                        ajaxUri: `v1/jabatan/pembina-mutu?pembina_mutu_id=${this.pembinaMutuData.id}`,
                        showLimit: 3,
                        renderObject: [
                            {
                                fieldName: 'Jabatan',
                                objectField:'jabatan'
                            },
                            {
                                fieldName: 'Unit Kerja',
                                objectField:'unit_kerja'
                            },
                            {
                                fieldName: 'Instansi',
                                objectField:'instansi'
                            },
                            {
                                fieldName: 'Tahun Mulai',
                                objectField:'tahun_mulai'
                            },
                            {
                                fieldName: 'Tahun Selesai',
                                objectField:'tahun_selesai'
                            },
                            {
                                fieldName: 'Masih Menjabat',
                                objectField:'masih_menjabat'
                            }
                        ]
                    }"
                />
            </div>
        </div>
        <div class="column is-12">
            <div class="box" v-if="showPelatihan">
                <div class="title is-4">Riwayat Pelatihan</div>
                <DynamicTable
                    :tableDep="{
                        ajaxUri: `v1/pelatihan/pembina-mutu?pembina_mutu_id=${this.pembinaMutuData.id}`,
                        showLimit: 3,
                        renderObject: [
                            {
                                fieldName: 'Nama Pelatihan',
                                objectField:'nama_pelatihan'
                            },
                            {
                                fieldName: 'Penyelenggara',
                                objectField:'penyelenggara'
                            },
                            {
                                fieldName: 'Tahun Pelaksanaan',
                                objectField:'tahun_pelaksanaan'
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
import DynamicTable from '../dynamic/table'
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
            userData: {},
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
                        data.registration_date = dayjs(data.created_at).format('DD - MM - YYYY')

                        this.showData = true
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