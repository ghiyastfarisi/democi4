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
                            <input :class="validation.classHandler.username" v-model="form.username" type="email" placeholder="Email KKP" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-if="validation.messageHandler.username">{{validation.messageHandler.username}}</p>
                    </div>
                    <div class="field">
                        <label for="" class="label">Password</label>
                        <div class="control has-icons-left">
                            <input :class="validation.classHandler.password" v-model="form.password" type="password" placeholder="Password" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-if="validation.messageHandler.password">{{validation.messageHandler.password}}</p>
                    </div>
                    <div class="field">
                        <label for="" class="label">Nama Lengkap</label>
                        <div class="control has-icons-left">
                            <input :class="validation.classHandler.nama_lengkap" v-model="formregis.nama_lengkap" type="text" placeholder="Pembina Mutu" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-if="validation.messageHandler.nama_lengkap">{{validation.messageHandler.nama_lengkap}}</p>
                    </div>
                    <div class="field">
                        <label for="" class="label">NIP</label>
                        <div class="control has-icons-left">
                            <input :class="validation.classHandler.nip" v-model="formregis.nip" pattern="[0-9]*" type="number" placeholder="NIP Valid" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-id-card"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-if="validation.messageHandler.nip">{{validation.messageHandler.nip}}</p>
                    </div>
                    <div class="field">
                        <label for="" class="label">No. Handphone</label>
                        <div class="control has-icons-left">
                            <input :class="validation.classHandler.no_hp" v-model="formregis.no_hp" pattern="[0-9]*" type="number" placeholder="Nomor Handphone Terdaftar" class="input" required>
                            <span class="icon is-small is-left">
                                <i class="fa fa-mobile-alt"></i>
                            </span>
                        </div>
                        <p class="help is-danger" v-if="validation.messageHandler.no_hp">{{validation.messageHandler.no_hp}}</p>
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
import FValid from 'fastest-validator'

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
            validation: {
                classHandler: {},
                messageHandler: {}
            }
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
        validateRegister(payload) {
            this.validation.classHandler = {}
            this.validation.messageHandler = {}

            const v = new FValid({
                useNewCustomCheckerFunction: true,
                messages: {
                    required: 'Wajib isi',
                    validEmail: 'Wajib menggunakan email KKP (@kkp.go.id)'
                }
            })
            const schema = {
                username: {
                    type: 'email',
                    custom: function(v, errors) {
                        const vs = v.split('@')
                        if (vs[vs.length-1] !== 'kkp.go.id') {
                            errors.push({ type: 'validEmail' })
                        }

                        return v
                    }
                },
                password: {
                    type: 'string',
                    min: 8,
                    max: 16
                },
                nama_lengkap: {
                    type: 'string'
                },
                nip: {
                    type: 'number'
                },
                no_hp: {
                    type: 'number'
                },
                $$strict: "remove"
            }
            const validate = v.compile(schema)
            const result = validate(payload)
            const msg = {}
            const cls = {}

            if (typeof result === 'boolean') {
                return true
            } else if (typeof result === 'object' || typeof result === 'array') {
                for (const key in result) {
                    if (result[key].message && result[key].message !== '') {
                        msg[result[key].field] = result[key].message
                        cls[result[key].field] = 'is-danger'
                    }
                }

                this.validation.classHandler =  Object.assign({}, this.validation.classHandler, cls)
                this.validation.messageHandler =  Object.assign({}, this.validation.messageHandler, msg)
            }

            return false
        },
        async submitRegister() {
            const payload = {
                ...this.form,
                ...this.formregis
            }
            payload.nip = parseInt(payload.nip)
            payload.no_hp = parseInt(payload.no_hp)
            if (this.validateRegister(payload)) {
                const loaders = Loading({
                    title: 'Sedang Memproses Registrasi'
                })
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
            }cons
        }
    },
    mounted() {}
};
</script>
