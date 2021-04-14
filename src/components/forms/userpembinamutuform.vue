<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="field">
                <label class="label">Username</label>
                <div class="control">
                    <input class="input" type="email" placeholder="Gunakan Email KKP" v-model="username">
                </div>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div class="control">
                    <input class="input" type="password" placeholder="Minimum 8 karakter" v-model="password">
                </div>
            </div>
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
                    <input class="input" type="text" placeholder="minimum 8 characters" v-model="ipass">
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
            username: '',
            password: '',
            nama_lengkap: '',
            nip: '',
            no_hp: '',
            keahlian: '',
            foto_profil: '',
            deskripsi: ''
        }
    },
    props: {
        formDep: {
            type: Object,
            default: function() {
                return {
                    submit: 'Daftar Pengguna'
                }
            }
        }
    },
    methods: {
        submitData() {
            return this.createData()
        },
        Close() {
            this.$emit('toggle-close')
        },
        createData() {
            const url = `${BASE_API_URL}v1/user/create/pembina-mutu`
            const payload = {
                username: this.username,
                password: this.password,
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
                method: 'POST',
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
                title: 'Create Data Succeeded',
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