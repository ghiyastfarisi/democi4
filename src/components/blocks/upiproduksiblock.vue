<template>
    <div>
        <div class="box" v-cloak v-if="showData">
            <div class="block">
                <nav class="level">
                    <div class="level-left">
                        <p class="title is-4 is-spaced">Data Produksi</p>
                    </div>
                </nav>
                <div class="field">
                    <label class="label"> Merk Dagang </label>
                    <div class="control">
                        <div class="tags are-medium">
                            <span class="tag is-info is-light" v-for="(l, i) in list.merk_dagang_arr" :key="i">
                                {{ l }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Produk Utama </label>
                    <div class="control">
                        {{ list.produk_utama_detail.nama_produk }}
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Kapasitas Maksimum Produk </label>
                    <div class="control">
                        {{ numberWithCommas(list.kapasitas_produksi_hari) }} Kg per Hari
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Rata-rata Jumlah Hari Produksi </label>
                    <div class="control">
                        {{ numberWithCommas(list.rata_hari_produksi_bulan) }} Hari per Bulan
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Rata-rata Jumlah Bulan Produksi </label>
                    <div class="control">
                        {{ list.rata_bulan_produksi_tahun }} Bulan per Tahun
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Produk yang Dihasilkan </label>
                    <div class="control">
                        <div class="tags are-medium">
                            <span class="tag is-info is-light" v-for="(l, i) in list.produk_dihasilkan" :key="i">
                                {{ l.nama_produk }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Wilayah Pemasaran Domestik </label>
                    <div class="control">
                        <div class="tags are-medium">
                            <span class="tag is-info is-light" v-for="(l, i) in list.pemasaran_domestik" :key="i">
                                {{ l.name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Negara Pemasaran </label>
                    <div class="control">
                        <div class="tags are-medium">
                            <span class="tag is-info is-light" v-for="(l, i) in list.pemasaran_mancanegara" :key="i">
                                {{ l.name_id !== '' ? l.name_id : l.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DynamicModalForm from '../forms/dynamicmodalform'

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
            list: {
                produk_utama_detail: {
                    nama_produk: '-'
                }
            }
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
        numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        getData() {
            const url = BASE_API_URL + this.blockDep.ajaxUri

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data && data !== null) {
                        this.showData = true

                        data.merk_dagang_arr = data.merk_dagang.split(',')

                        this.list = data
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