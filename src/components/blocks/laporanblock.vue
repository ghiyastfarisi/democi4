<template>
    <div class="box">
        <div class="block">
            <div v-cloak v-if="showError" class="notification is-danger">
                <span class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </span>
                <span>
                    Laporan Kunjungan not found
                </span>
            </div>
            <div v-cloak v-if="showData">
                <nav class="level">
                    <div class="level-left">
                        <p class="title is-4 is-spaced">Data Laporan Kunjungan</p>
                    </div>
                </nav>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label class="label"> Kegiatan </label>
                            <div class="control">
                                {{ list.kegiatan }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Tanggal Kunjungan </label>
                            <div class="control">
                                {{ list.tanggal_kunjungan }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> UPI </label>
                            <div class="control">
                                {{ list.nama_upi }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Provinsi </label>
                            <div class="control">
                                {{ list.nama_provinsi }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Pembina Mutu </label>
                            <div class="control">
                                {{ list.nama_pembina_mutu }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Catatan </label>
                            <div class="control" v-html="list.catatanForm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DynamicModalForm from '../forms/dynamicmodalform'
import Dayjs from 'dayjs'

export default {
    created() {},
    components: {
        DynamicModalForm
    },
    data: function() {
        return {
            showEditAkun: false,
            showError: false,
            showData: false,
            list: {}
        }
    },
    props: {
        blockDep: {
            type: Object,
            default: function () {
                return {
                    enableEdit: false,
                    ajaxUri: '',
                    blockType: ''
                }
            }
        }
    },
    methods: {
        parseForDisplay(txt) {
            return txt.split('\n').join('<br/>')
        },
        getData() {
            const url = BASE_API_URL + this.blockDep.ajaxUri

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.showData = true
                        this.list = data
                        this.list.tanggal_kunjungan = Dayjs(this.list.tanggal_kunjungan).format('DD-MM-YYYY')
                        this.list.catatanForm = this.parseForDisplay(this.list.catatan)
                    } else {
                        this.showError = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        }
    },
    mounted() {
        let vm = this
        vm.getData()
    }
};
</script>