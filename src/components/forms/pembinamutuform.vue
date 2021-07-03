<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="field">
                <label class="label">Nama Lengkap</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Nama Lengkap" v-model="nama_lengkap">
                </div>
            </div>
            <div class="field">
                <label class="label">NIP</label>
                <div class="control">
                    <input class="input" type="text" placeholder="NIP Lengkap" v-model="nip">
                </div>
            </div>
            <div class="field">
                <label class="label">No Handphone</label>
                <div class="control">
                    <input class="input" type="text" placeholder="No Handphone" v-model="no_hp">
                </div>
            </div>
            <div class="field">
                <label class="label">Keahlian</label>
                <div class="control">
                    <Multiselect
                        :multiple="true"
                        v-model="selected.keahlian"
                        :options="master.badges"
                        placeholder="Pilih Keahlian"
                        track-by="id"
                        label="label"
                        style="width:100%"
                    />
                </div>
            </div>
            <div class="field">
                <label class="label">Deskripsi</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Deskripsi Diri" v-model="deskripsi">
                </div>
            </div>
            <div class="field">
                <label class="label"> Foto Profil </label>
                <div class="control">
                    <div class="file">
                        <label class="file-label">
                            <input class="file-input" type="file" name="resume" @change="onFileChange">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="file-label">
                                    Upload Foto Profil
                                </span>
                            </span>
                        </label>
                    </div>
                    <div class="image-current mr-2 mb-2 mt-2" v-if="foto_profil">
                        <figure class="image is-inline-block p-1" style="border:1px dotted #ddd;">
                            <img :src="foto_profil" style="max-width:128px;width:128px;" />
                        </figure>
                    </div>
                    <div class="image-cont mr-2 mb-2 mt-2" v-if="image.display">
                        <figure class="image is-inline-block p-1" style="border:1px dotted #ddd;">
                            <img :src="image.display" style="max-width:128px;width:128px;" />
                            <a href="javascript:void(0)" class="button is-danger is-small mt-1 is-fullwidth" @click="cancelUpload">Batal Upload</a>
                        </figure>
                    </div>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot">
            <button type="submit" class="button is-success">{{ formDep.submit }}</button>
            <button type="cancel" @click="Close" class="button">Cancel</button>
        </footer>
    </form>
</template>

<script>
import { AutoClosePopup } from '../../lib/popup'
import { HandlePostUpload, ParseError } from '../../lib/form'
import Multiselect from 'vue-multiselect'

export default {
    components: {
        Multiselect
    },
    data: function() {
        return {
            master: {
                badges: []
            },
            selected: {
                keahlian: []
            },
            nama_lengkap: '',
            nip: '',
            no_hp: '',
            keahlian: '',
            deskripsi: '',
            foto_profil: '',
            image: {
                display: '',
                object: {}
            },
            baseUrl: BASE_URL
        }
    },
    props: {
        formDep: {
            type: Object,
            default: function() {
                return {
                    submit: '',
                    isEdit: false,
                    createUrl: '',
                    fetchEditUrl: '',
                    updateUrl: '',
                    extra: {
                        pembinaMutuId: 0
                    }
                }
            }
        }
    },
    created() {
        let fd = this.formDep

        this.setMaster()

        if (fd.isEdit) {
            this.setEditForm()
        }
    },
    methods: {
        cancelUpload() {
            this.image = {
                display: '',
                object: {}
            }
        },
        onFileChange(e) {
            const file = e.target.files[0];

            if (file) {
                if (file.type !== 'image/png' && file.type !== 'image/jpeg') {
                    return this.errorPopup("Jenis file tidak didukung")
                }

                const ou = URL.createObjectURL(file)

                this.image.display = ou
                this.image.uploadType = 'image_upload'
                this.image.uploadUsage = 'foto_pembina_mutu'
                this.image.object = file
            }

        },
        async fetchUpload(payload) {
            const result = await HandlePostUpload(
                `${BASE_API_URL}v1/upload/file`,
                payload
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            if (result.origin && result.origin.data && result.origin.data.path) {
                const [uploaded] = result.origin.data.path

                return uploaded
            }
        },
        async actionUpload() {
            const payload = new FormData()

            if (this.image && this.image.object && this.image.display != '') {
                payload.append('upload_usage', this.image.uploadUsage)
                payload.append('upload_type', this.image.uploadType)
                payload.append('files[]', this.image.object)

                const uploaded = await this.fetchUpload(payload)

                return uploaded
            }

            return {}
        },
        async submitData() {
            let fd = this.formDep

            if (fd.isEdit) {
                const uploaded = await this.actionUpload()

                if (uploaded && uploaded.upload_path) {
                    this.foto_profil = `${this.baseUrl}/${uploaded.upload_path}`
                }

                return this.updateData()
            }

            return this.createData()
        },
        Close() {
            this.$emit('toggle-close')
        },
        setMaster() {
            const url = `${BASE_API_URL}/v1/badge/all?category=pm&transformLabel=true`
            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data) {
                        this.master.badges = data
                    }
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        setEditForm() {
            const url = `${BASE_API_URL}${this.formDep.fetchEditUrl}`
            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data) {
                        if (data.keahlian.length > 0) {
                            this.selected.keahlian = data.keahlian.map(el => {
                                return {
                                    id: el.badge_id,
                                    label: el.name
                                }
                            })
                        }
                        this.nama_lengkap = data.nama_lengkap
                        this.nip = data.nip
                        this.no_hp = data.no_hp
                        this.deskripsi = data.deskripsi
                        this.foto_profil = data.foto_profil
                    }
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        createData() {
            const url = `${BASE_API_URL}${this.formDep.createUrl}`
            const payload = JSON.stringify({
                username: this.iuname,
                password: this.ipass
            })
            fetch(url, {
                method: 'POST',
                body: payload,
            })
            .then(resp => {
                if (!resp.ok) {
                    return resp.json()
                        .then(errResp => {
                            if (errResp && errResp.error && errResp.error.message) {
                                console.log(errResp.error.message)
                            }
                        })
                        .catch(err => {
                            console.error(err)
                        })
                }

                return resp.json()
            })
            .catch(err => {
                console.error(err)
            })

            this.Close()

            AutoClosePopup({
                title: 'Create Data Succeeded',
                body: '',
                timeout: 900
            })

            this.$emit('update-table')
        },
        updateData() {
            const url = `${BASE_API_URL}${this.formDep.updateUrl}`

            this.keahlian = this.selected.keahlian.map(el => el.id)

            const payload = {
                nama_lengkap: this.nama_lengkap,
                nip: this.nip,
                no_hp: this.no_hp,
                keahlian: this.keahlian
            }
            if (this.foto_profil !== '') {
                payload.foto_profil = this.foto_profil
            }
            if (this.deskripsi !== '') {
                payload.deskripsi = this.deskripsi
            }
            fetch(url, {
                method: 'PATCH',
                body: JSON.stringify(payload),
            })
            .then(resp => {
                if (!resp.ok) {
                    return resp.json()
                        .then(errResp => {
                            if (errResp && errResp.error && errResp.error.message) {
                                console.log(errResp.error.message)
                            }
                        })
                        .catch(err => {
                            console.error(err)
                        })
                }

                return resp.json()
            })
            .catch(err => {
                console.error(err)
            })

            this.Close()

            AutoClosePopup({
                title: 'Update Data Succeeded',
                body: '',
                timeout: 900
            })

            this.$emit('update-table')
        },
        errorPopup(message) {
            AutoClosePopup({
                title: message,
                body: '',
                timeout: 3000
            })
        },
    }
};
</script>

<style scoped>
</style>