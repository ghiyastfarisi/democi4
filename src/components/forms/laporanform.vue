<template>
    <form class="form-name-container" @submit.prevent="submitData">
        <section class="modal-card-body">
            <div class="field">
                <label class="label">Kegiatan</label>
                <div class="control">
                    <input class="input" name="institusi_pendidikan" type="text" placeholder="5 - 70 characters" v-model="formValue.kegiatan">
                </div>
            </div>
            <div class="field">
                <label class="label">UPI</label>
                <Multiselect
                    v-model="formValue.selectedUpi"
                    id="ajax"
                    track-by="id"
                    label="label"
                    placeholder="Search UPI"
                    selectLabel=""
                    deselectLabel=""
                    selectedLabel=""
                    open-direction="bottom"
                    :options="searchedUpi"
                    :searchable="true"
                    :loading="isLoading"
                    :internal-search="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit-text="limitText"
                    :max-height="600"
                    :show-no-results="false"
                    :hide-selected="false"
                    :allowEmpty="false"
                    :preserveSearch="true"
                    @search-change="asyncFind"
                    style="width:100%"
                >
                    <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
                    <span slot="noOptions">Ketik keyword untuk mencari nama upi</span>
                </Multiselect>
            </div>
            <div class="field">
                <label class="label">Tanggal Kunjungan</label>
                <div class="control">
                    <datepicker
                        placeholder="Pilih Tanggal Kunjungan"
                        v-model="formValue.tgl_kunjungan"
                        name="tgl_kunjungan"
                        input-class="input"
                        :highlighted="selectedDate"
                    ></datepicker>
                </div>
            </div>
            <div class="field">
                <label class="label"> Catatan </label>
                <div class="control">
                    <textarea v-if="formDep.isEdit" v-html="formValue.catatan" v-model="formValue.catatan" class="textarea" placeholder="Catatan"></textarea>
                    <textarea v-if="!formDep.isEdit" v-model="formValue.catatan" class="textarea" placeholder="Catatan"></textarea>
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
import Multiselect from 'vue-multiselect'
import UrlParse from 'url-parse'
import Datepicker from 'vuejs-datepicker'
import dayjs from 'dayjs'

export default {
    components: {
        Multiselect,
        Datepicker
    },
    data: function() {
        return {
            isLoading: false,
            searchedUpi: [],
            currentYear: new Date().getFullYear(),
            selectedDate: {},
            formValue: {
                selectedUpi: { id: 0 },
                kegiatan: '',
                upi: 0,
                tgl_kunjungan: '',
                catatan: ''
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
    mounted() {
        let fd = this.formDep

        if (fd.isEdit) {
            this.setEditForm()
        }
    },
    methods: {
        async asyncFind (query) {
            const q = {
                mapSelect: true,
                keyword: query
            }
            const url = new UrlParse(`${BASE_API_URL}/v1/upi/search`, true)

            url.query.mapSelect = q.mapSelect
            url.query.keyword = q.keyword

            if (query.length > 0) {
                this.isLoading = true
                fetch(url.toString())
                    .then(stream => stream.json())
                    .then(resp => {
                        const { data } = resp
                        this.searchedUpi = data
                        this.isLoading = false
                    })
                    .catch(err => {
                        console.error(err)
                        this.errorPopup(
                            ParseError("Terjadi kesalahan ketika mencari")
                        )
                    })
            }
        },
        limitText (count) {
            return `and ${count} other upi`
        },
        clearAll () {
            this.formValue.selectedUpi = []
        },
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
                        this.formValue.tgl_kunjungan = data.tanggal_kunjungan
                        this.formValue.upi = data.upi_id
                        this.formValue.selectedUpi = data.selected_upi
                    }
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        async createData() {
            const payload = {
                kegiatan: this.formValue.kegiatan,
                upi_id: Number(this.formValue.selectedUpi.id),
                tanggal_kunjungan: dayjs(this.formValue.tgl_kunjungan).format('YYYY-MM-DD'),
                catatan: this.formValue.catatan
            }

            const result = await HandlePost(
                `${BASE_API_URL}${this.formDep.createUrl}`,
                JSON.stringify(payload),
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
            const upiId = (this.formValue.selectedUpi && this.formValue.selectedUpi.id && Number(this.formValue.selectedUpi.id)) > 0
                ? Number(this.formValue.selectedUpi.id)
                : Number(this.formValue.upi)

            const payload = {
                kegiatan: this.formValue.kegiatan,
                upi_id: upiId,
                tanggal_kunjungan: dayjs(this.formValue.tgl_kunjungan).format('YYYY-MM-DD'),
                catatan: this.formValue.catatan
            }

            const result = await HandlePatch(
                `${BASE_API_URL}${this.formDep.updateUrl}`,
                JSON.stringify(payload),
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
        closeAndPopup(title='', body ='', timeout=2000) {
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