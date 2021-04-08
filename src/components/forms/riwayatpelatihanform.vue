<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Tahun Pelaksanaan</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="tahun_pelaksanaan" id="tahun_pelaksanaan" v-model="tahun_pelaksanaan">
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
                    <input class="input" name="penyelenggara" type="text" placeholder="minimum 5 characters" v-model="penyelenggara">
                </div>
            </div>
            <div class="field">
                <label class="label">Nama Pelatihan</label>
                <div class="control">
                    <input class="input" name="nama_pelatihan" type="text" placeholder="minimum 5 characters" v-model="nama_pelatihan">
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
            currentYear: new Date().getFullYear(),
            tahun_pelaksanaan: 2015,
            penyelenggara: '',
            nama_pelatihan: '',
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
            const url = BASE_API_URL + 'v1/pelatihan/create/pembina-mutu/' + this.formDep.extra.pembinaMutuId

            const payload = JSON.stringify({
                tahun_pelaksanaan: this.tahun_pelaksanaan,
                penyelenggara: this.penyelenggara,
                nama_pelatihan: this.nama_pelatihan
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
        Close() {
            this.$emit('toggle-close')
        },
    }
};
</script>

<style scoped>
</style>