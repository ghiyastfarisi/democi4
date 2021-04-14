<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Tahun Mulai</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="tahun_mulai" id="tahun_mulai" v-model="formValue.tahun_mulai">
                                    <option v-for="(list, index) in 25" :value="currentYear - list" :key="index">
                                        {{ currentYear - list }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Tahun Selesai</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="tahun_selesai" id="tahun_selesai" v-model="formValue.tahun_selesai">
                                    <option v-for="(list, index) in 25" :value="currentYear - list" :key="index">
                                        {{ currentYear - list }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" v-model="formValue.masih_menjabat" true-value="YA" false-value="TIDAK">
                    Masih Menjabat
                </label>
            </div>
            <div class="field">
                <label class="label">Jabatan</label>
                <div class="control">
                    <input class="input" name="jabatan" type="text" placeholder="minimum 5 characters" v-model="formValue.jabatan">
                </div>
            </div>
            <div class="field">
                <label class="label">Unit Kerja</label>
                <div class="control">
                    <input class="input" name="unit_kerja" type="text" placeholder="minimum 5 characters" v-model="formValue.unit_kerja">
                </div>
            </div>
            <div class="field">
                <label class="label">Instansi</label>
                <div class="control is-expanded">
                    <div class="select is-fullwidth">
                        <select name="instansi" id="instansi" v-model="formValue.instansi">
                            <option v-for="(list, index) in instansiArray" :value="list" :key="index">
                                {{ list }}
                            </option>
                        </select>
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
const { AutoClosePopup } = require('../../lib/popup')
const { HandlePost, HandlePatch, ParseError } = require('../../lib/form')
const { Sanitize } = require('../../lib/object')

module.exports = {
    data: function() {
        return {
            instansiArray: ['KKP', 'DINAS'],
            currentYear: new Date().getFullYear(),
            formValue: {
                jabatan: '',
                tahun_selesai: 2020,
                tahun_mulai: 2010,
                unit_kerja: '',
                instansi: '',
                masih_menjabat: 'TIDAK'
            }
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
            this.setEditForm()
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
        setEditForm() {
            const url = `${BASE_API_URL}${this.formDep.fetchEditUrl}`
            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data) {
                        this.formValue = Sanitize(this.formValue, data)
                    }
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        async createData() {
            const result = await HandlePost(
                `${BASE_API_URL}${this.formDep.createUrl}`,
                JSON.stringify(this.formValue),
                'PATCH'
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            return this.closeAndPopup(
                result.message
            )
        },
        async updateData() {
            const result = await HandlePatch(
                `${BASE_API_URL}${this.formDep.updateUrl}`,
                JSON.stringify(this.formValue),
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            return this.closeAndPopup(
                result.message
            )
        },
        closeAndPopup(title='', body ='', timeout=900) {
            this.Close()

            AutoClosePopup({
                title,
                body,
                timeout
            })

            this.$emit('update-table')
        },
        errorPopup(message) {
            AutoClosePopup({
                title: message,
                body: '',
                timeout: 900
            })
        },
        Close() {
            this.$emit('toggle-close')
        },
    }
};
</script>

<style scoped>
</style>