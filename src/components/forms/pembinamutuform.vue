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
                    <input class="input" type="text" placeholder="Keahlian" v-model="keahlian">
                </div>
            </div>
            <div class="field">
                <label class="label">Deskripsi</label>
                <div class="control">
                    <input class="input" type="text" placeholder="Deskripsi Diri" v-model="deskripsi">
                </div>
            </div>
            <!-- <div class="field">
                <label class="label">Upload Foto Profil</label>
                <div class="control">
                    <input class="input" type="text" placeholder="minimum 8 characters" v-model="foto_profil">
                </div>
            </div> -->
        </section>
        <footer class="modal-card-foot">
            <button type="submit" class="button is-success">{{ formDep.submit }}</button>
            <button type="cancel" @click="Close" class="button">Cancel</button>
        </footer>
    </form>
</template>

<script>
const { AutoClosePopup } = require('../../lib/popup')

module.exports = {
    data: function() {
        return {
            nama_lengkap: '',
            nip: '',
            no_hp: '',
            keahlian: '',
            deskripsi: '',
            foto_profil: '',
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
                        userId: 0
                    }
                }
            }
        }
    },
    created() {
        let fd = this.formDep

        if (fd.isEdit) {
            this.setEditForm(fd.extra.userId)
        }
    },
    methods: {
        submitData() {
            let fd = this.formDep

            if (fd.isEdit) {
                return this.updateData()
            }

            return this.createData()
        },
        Close() {
            this.$emit('toggle-close')
        },
        setEditForm() {
            const url = `${BASE_API_URL}${this.formDep.fetchEditUrl}`
            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data) {
                        this.nama_lengkap = data.nama_lengkap
                        this.nip = data.nip
                        this.no_hp = data.no_hp
                        this.keahlian = data.keahlian
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
            const payload = {
                nama_lengkap: this.nama_lengkap,
                nip: this.nip,
                no_hp: this.no_hp,
                keahlian: this.keahlian,
                user_id: this.formDep.extra.userId
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
    }
};
</script>

<style scoped>
</style>