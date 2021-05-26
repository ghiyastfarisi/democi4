<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Tahun Pelaksanaan</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="tahun_pelaksanaan" id="tahun_pelaksanaan" v-model="formValue.tahun_pelaksanaan">
                                    <option v-for="(list, index) in 20" :value="currentYear - list" :key="index">
                                        {{ currentYear - list }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="label">Penyelenggara</label>
                <div class="control">
                    <input class="input" name="penyelenggara" type="text" placeholder="minimum 5 characters" v-model="formValue.penyelenggara">
                </div>
            </div>
            <div class="field">
                <label class="label">Nama Pelatihan</label>
                <div class="control">
                    <input class="input" name="nama_pelatihan" type="text" placeholder="minimum 5 characters" v-model="formValue.nama_pelatihan">
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
            currentYear: new Date().getFullYear(),
            formValue: {
                tahun_pelaksanaan: 2015,
                penyelenggara: '',
                nama_pelatihan: '',
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
                JSON.stringify(this.formValue)
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