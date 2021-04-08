<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Jenjang</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="jenjang" id="jenjang" v-model="jenjang">
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
                                <select name="tahun_lulus" id="tahun_lulus" v-model="tahun_lulus">
                                    <option v-for="(list, index) in 40" :value="currentYear - list" :key="index">
                                        {{ currentYear - list }}
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
                    <input class="input" name="institusi_pendidikan" type="text" placeholder="minimum 5 characters" v-model="institusi_pendidikan">
                </div>
            </div>
            <div class="field">
                <label class="label">Program Studi</label>
                <div class="control">
                    <input class="input" name="program_studi" type="text" placeholder="minimum 5 characters" v-model="program_studi">
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

module.exports = {
    data: function() {
        return {
            jenjangArray: ['D3', 'D4', 'S1', 'S2', 'S3'],
            currentYear: new Date().getFullYear(),
            jenjang: '',
            tahun_lulus: '',
            institusi_pendidikan: '',
            program_studi: '',
        }
    },
    props: {
        formDep: {
            submit: String,
            extra: {
                pembinaMutuId: Number
            }
        }
    },
    methods: {
        submitData() {
            const url = BASE_API_URL + 'v1/pendidikan/create/pembina-mutu/' + this.formDep.extra.pembinaMutuId

            const payload = JSON.stringify({
                jenjang: this.jenjang,
                tahun_lulus: this.tahun_lulus,
                institusi_pendidikan: this.institusi_pendidikan,
                program_studi: this.program_studi
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
                timeout:1500
            })

            this.$emit('update-table')
        },
        Close() {
            this.$emit('toggle-close')
        },
    }
};
</script>

<style scoped>
</style>