<template>
    <div class="login-main-container">
        <div class="tabs is-toggle is-centered">
            <ul>
                <li :class="[ { 'is-active': isLoginPage  } ]">
                    <a href="javascript:void(0)" @click="loginToggle">
                        <span class="icon is-small"><i class="fas fa-sign-in-alt" aria-hidden="true"></i></span>
                        <span>Login</span>
                    </a>
                </li>
                <li :class="[ { 'is-active': isRegisPage } ]">
                    <a href="javascript:void(0)" @click="regisToggle">
                        <span class="icon is-small"><i class="fas fa-people-carry" aria-hidden="true"></i></span>
                        <span>Register</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="box">
            <div v-if="isLoginPage" class="login-container">
                <form @submit.prevent="submitLogin">
                    <div class="field">
                        <label for="" class="label">Email</label>
                        <div class="control has-icons-left">
                            <input v-model="form.username" type="email" placeholder="Email KKP" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="" class="label">Password</label>
                        <div class="control has-icons-left">
                            <input v-model="form.password" type="password" placeholder="Password" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <button type="submit" name="submit" class="button is-success">
                            Login
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="isRegisPage" class="regis-container">
                <form @submit.prevent="submitRegister">
                    <div class="field">
                        <label for="" class="label">Email</label>
                        <div class="control has-icons-left">
                            <input v-model="form.username" type="email" placeholder="Email KKP" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="" class="label">Password</label>
                        <div class="control has-icons-left">
                            <input v-model="form.password" type="password" placeholder="Password" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="" class="label">Nama Lengkap</label>
                        <div class="control has-icons-left">
                            <input v-model="formregis.nama_lengkap" type="text" placeholder="Nama Lengkap Pembina Mutu" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="" class="label">NIP</label>
                        <div class="control has-icons-left">
                            <input v-model="formregis.nip" type="text" placeholder="NIP Valid" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-id-card"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label for="" class="label">No. Handphone</label>
                        <div class="control has-icons-left">
                            <input v-model="formregis.no_hp" type="text" placeholder="Nomor Handphone Terdaftar" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-mobile-alt"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <button type="submit" name="submit" class="button is-success">
                            {{ submitText }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { HandlePost, ParseError } from '../../lib/form'
import { AutoClosePopup, Loading } from '../../lib/popup'

export default {
    components: {},
    data: function() {
        return {
            submitText: 'Submit Login',
            isLoginPage: true,
            isRegisPage: false,
            form: {
                username: '',
                password: ''
            },
            formregis: {
                nama_lengkap: '',
                nip: '',
                no_hp: ''
            },
        }
    },
    props: {},
    methods: {
        regisToggle() {
            this.isRegisPage = true
            this.isLoginPage = false
            this.submitText = 'Submit Pendaftaran'
        },
        loginToggle() {
            this.isRegisPage = false
            this.isLoginPage = true
            this.submitText = 'Submit Login'
        },
        async submitLogin() {
            const payload = this.form
            const resp = await HandlePost(`${BASE_URL}/login`, JSON.stringify(payload))

            if (resp.isError) {
                AutoClosePopup({
                    icon: 'error',
                    title: 'Login Gagal',
                    body: ParseError(resp.message),
                    timeout: 5000
                })
            } else {
                AutoClosePopup({
                    icon: 'success',
                    title: 'Login Berhasil',
                    timeout: 1000
                })

                setTimeout(function() {
                    location.reload()
                }, 1100)
            }
        },
        async submitRegister() {
            const loaders = Loading({
                title: 'Sedang Memproses Registrasi'
            })

            const payload = {
                ...this.form,
                ...this.formregis
            }
            const resp = await HandlePost(`${BASE_URL}/register`, JSON.stringify(payload))

            loaders.close()

            if (resp.isError) {
                AutoClosePopup({
                    icon: 'error',
                    title: 'Registrasi Gagal',
                    body: ParseError(resp.message),
                    timeout: 5000
                })
            } else {
                this.form = {
                    username: '',
                    password: ''
                }
                this.formregis = {
                    nama_lengkap: '',
                    nip: '',
                    no_hp: ''
                }
                AutoClosePopup({
                    icon: 'success',
                    title: 'Registrasi Berhasil',
                    body: 'Mohon cek email anda untuk verifikasi pendaftaran',
                    timeout: 3500
                })
            }
        }
    },
    mounted() {}
};
</script>
