<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input class="input" name="username" type="email" placeholder="use email format" v-model="iuname" :disabled="formDep.isEdit">
                </div>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <div v-if="formDep.isEdit" class="block">
                    <span class="tag is-danger is-light">Hanya isi jika ingin mengganti password</span>
                </div>
                <div class="control">
                    <input class="input" name="password" type="password" placeholder="minimum 8 characters" v-model="ipass">
                </div>
            </div>
            <div v-if="formDep.isEdit && !formDep.extra.excludeRoleUpdate" class="field">
                <label class="label">Login Status</label>
                <div class="control is-expanded">
                    <div class="select is-fullwidth">
                        <select name="login_status" id="login_status" v-model="iloginStatus">
                            <option v-for="(list, index) in loginStatusArray" :value="list" :key="index">
                                {{ list }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div v-if="formDep.isEdit && !formDep.extra.excludeRoleUpdate" class="field">
                <label class="label">Role</label>
                <div class="control is-expanded">
                    <div class="select is-fullwidth">
                        <select name="role" id="login_status" v-model="iRole">
                            <option v-for="(list, index) in roleArray" :value="list" :key="index">
                                {{ list }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div v-if="!formDep.isEdit" class="field">
                <label for="" class="label">Nama Lengkap</label>
                <div class="control has-icons-left">
                    <input v-model="nama_lengkap" type="text" placeholder="Nama Lengkap Pembina Mutu" class="input" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-user"></i>
                    </span>
                </div>
            </div>
            <div v-if="!formDep.isEdit" class="field">
                <label for="" class="label">NIP</label>
                <div class="control has-icons-left">
                    <input v-model="nip" type="text" placeholder="NIP Valid" class="input" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-id-card"></i>
                    </span>
                </div>
            </div>
            <div v-if="!formDep.isEdit" class="field">
                <label for="" class="label">No. Handphone</label>
                <div class="control has-icons-left">
                    <input v-model="no_hp" type="text" placeholder="Nomor Handphone Terdaftar" class="input" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-mobile-alt"></i>
                    </span>
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

export default {
    data: function() {
        return {
            iuname: '',
            ipass: '',
            iloginStatus: '',
            iRole: '',
            loginStatusArray: [ 'active', 'inactive' ],
            roleArray: [ 'admin', 'pembina_mutu' ],
            nama_lengkap: '',
            nip: '',
            no_hp: ''
        }
    },
    props: {
        formDep: {
            type: Object,
            default: function() {
                return {
                    submit: '',
                    isEdit: false,
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
                        this.iuname = data.username
                        this.iloginStatus = data.login_status
                        this.iRole = data.role
                    }
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        createData() {
            const url = `${BASE_URL}/register`
            const payload = JSON.stringify({
                username: this.iuname,
                password: this.ipass,
                nama_lengkap: this.nama_lengkap,
                nip: this.nip,
                no_hp: this.no_hp
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
                                console.error(errResp.error.message)
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
                login_status: this.iloginStatus,
                role: this.iRole
            }
            if (this.ipass !== '' && this.ipass.length > 1) {
                payload.password = this.ipass
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
                                console.error(errResp.error.message)
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