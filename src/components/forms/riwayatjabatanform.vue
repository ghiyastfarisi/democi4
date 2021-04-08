<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Tahun Mulai</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <select name="tahun_mulai" id="tahun_mulai" v-model="tahun_mulai">
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
                                <select name="tahun_selesai" id="tahun_selesai" v-model="tahun_selesai">
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
                    <input type="checkbox" v-model="masih_menjabat" true-value="YA" false-value="TIDAK">
                    Masih Menjabat
                </label>
            </div>
            <div class="field">
                <label class="label">Jabatan</label>
                <div class="control">
                    <input class="input" name="jabatan" type="text" placeholder="minimum 5 characters" v-model="jabatan">
                </div>
            </div>
            <div class="field">
                <label class="label">Unit Kerja</label>
                <div class="control">
                    <input class="input" name="unit_kerja" type="text" placeholder="minimum 5 characters" v-model="unit_kerja">
                </div>
            </div>
            <div class="field">
                <label class="label">Instansi</label>
                <div class="control is-expanded">
                    <div class="select is-fullwidth">
                        <select name="instansi" id="instansi" v-model="instansi">
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

module.exports = {
    data: function() {
        return {
            instansiArray: ['KKP', 'DINAS'],
            currentYear: new Date().getFullYear(),
            jabatan: '',
            tahun_selesai: 2020,
            tahun_mulai: 2010,
            unit_kerja: '',
            instansi: '',
            masih_menjabat: 'TIDAK'
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
            const url = BASE_API_URL + 'v1/jabatan/create/pembina-mutu/' + this.formDep.extra.pembinaMutuId

            const payload = JSON.stringify({
                jabatan: this.jabatan,
                tahun_selesai: this.tahun_selesai,
                tahun_mulai: this.tahun_mulai,
                unit_kerja: this.unit_kerja,
                instansi: this.instansi,
                masih_menjabat: this.masih_menjabat,
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
                timeout:900
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