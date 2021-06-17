<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Jenjang</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="jenjang" id="jenjang" v-model="formValue.jenjang">
                                    <option v-for="(list, index) in jenjangArray" :value="list" :key="index">
                                        {{ list }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Tahun Lulus</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="tahun_lulus" id="tahun_lulus" v-model="formValue.tahun_lulus">
                                    <option v-for="(list, index) in 40" :value="currentYear - (list - 1)" :key="index">
                                        {{ currentYear - (list - 1) }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="label">Institusi Pendidikan</label>
                <div class="control">
                    <input class="input" name="institusi_pendidikan" type="text" placeholder="minimum 5 characters" v-model="formValue.institusi_pendidikan">
                </div>
            </div>
            <div class="field">
                <label class="label">Program Studi</label>
                <div class="control">
                    <input class="input" name="program_studi" type="text" placeholder="minimum 5 characters" v-model="formValue.program_studi">
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
import { HandlePost, HandlePatch, ParseError } from '../../lib/form'
import { Sanitize } from '../../lib/object'

export default {
    data: function() {
        return {
            jenjangArray: ['D3', 'D4', 'S1', 'S2', 'S3'],
            currentYear: new Date().getFullYear(),
            formValue: {
                jenjang: '',
                tahun_lulus: '',
                institusi_pendidikan: '',
                program_studi: '',
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