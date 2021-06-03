<template>
    <div class="box">
        <div class="block">
            <UploadImages />
            <!-- <form class="form-name-container" @submit.prevent="submitData">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label"> Nama Perusahaan </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="nama perusahaan" v-model="list.data_umum.nama_perusahaan">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Notes </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="nama perusahaan" v-model="list.data_umum.nama_perusahaan">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Kegiatan </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="nama perusahaan" v-model="list.data_umum.nama_perusahaan">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Tanggal Kunjungan </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="nama perusahaan" v-model="list.data_umum.nama_perusahaan">
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="button is-success">Update</button>
                    <button type="cancel" @click="Close" class="button">Cancel</button>
                </div>
            </form> -->
        </div>
    </div>
</template>

<script>
import UrlParse from 'url-parse'
import UploadImages from '../forms/uploadimages'
import { HandlePatch, ParseError } from '../../lib/form'
import { AutoClosePopup } from '../../lib/popup'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'

export default {
    created() {},
    components: {
        UploadImages,
        Multiselect
    },
    data: function() {
        return {
            showEditAkun: false,
            showError: false,
            showData: false,
            master: {
                province: [],
                regency: [],
                district: [],
                sub_district: [],
                products: [],
                ekspor: [],
                domestik: []
            },
            transformed: {
                data_produksi: {
                    produk_dihasilkan: [],
                    pemasaran_domestik: [],
                    pemasaran_ekspor: []
                }
            },
            list: {
                data_umum: {},
                data_produksi: {
                    produk_dihasilkan: [],
                    pemasaran_domestik: [],
                    pemasaran_ekspor:[]
                },
                data_tenaga_kerja: {},
                data_sarpras: {}
            }
        }
    },
    props: {
        blockDep: {
            type: Object,
            default: function () {
                return {
                    editMode: 'request',
                    ajaxUri: '',
                    upiId: 0
                }
            }
        }
    },
    methods: {
        Close() {},
        getCountry() {
            const url = new UrlParse(`${BASE_API_URL}v1/country`, true)

            url.query.transformLabel = true

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.master.ekspor = data
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        getRegency() {
            const url = new UrlParse(`${BASE_API_URL}v1/location`, true)

            url.query.getType = 'regency'
            url.query.transformLabel = true

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.master.domestik = data
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        getProduct() {
            const url = new UrlParse(`${BASE_API_URL}v1/produk`, true)

            url.query.transformLabel = true

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.master.products = data
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        locatorButtonPressed() {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const latlong = `${position.coords.latitude}, ${position.coords.longitude}`
                    this.list.data_umum.koordinat_lokasi = latlong
                },
                error => {
                    console.log(error.message);
                },
            )
        },
        submitData() {
            const payload = Object.assign({}, this.list)

            payload.data_sarpras = this.transformSarprasForm(payload.data_sarpras)
            payload.data_produksi.produk_dihasilkan = this.transformArrayObject(this.transformed.data_produksi.produk_dihasilkan)
            payload.data_produksi.pemasaran_domestik = this.transformArrayObject(this.transformed.data_produksi.pemasaran_domestik)
            payload.data_produksi.pemasaran_ekspor = this.transformArrayObject(this.transformed.data_produksi.pemasaran_ekspor)

            delete payload.data_produksi.id
            delete payload.data_produksi.upi_id
            delete payload.data_produksi.foto_produk
            delete payload.data_tenaga_kerja.id
            delete payload.data_tenaga_kerja.upi_id

            this.editData(payload)
        },
        closeAndPopup(title='', body ='', timeout=900) {
            AutoClosePopup({
                title,
                body,
                timeout
            })

            this.getData()
        },
        errorPopup(message) {
            AutoClosePopup({
                title: message,
                body: '',
                timeout: 900
            })
        },
        async editData(payload) {
            const result = await HandlePatch(
                `${BASE_API_URL}v1/upi/${this.blockDep.upiId}/update/complete`,
                JSON.stringify(payload)
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
        getData() {
            const url = `${BASE_API_URL}${this.blockDep.ajaxUri}`

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.showData = true
                        this.transformed.data_produksi.produk_dihasilkan = data.data_produksi.produk_dihasilkan.map(el => {
                            return {
                                id: el.id,
                                label: el.nama_produk
                            }
                        })
                        this.transformed.data_produksi.pemasaran_domestik = data.data_produksi.pemasaran_domestik.map(el => {
                            return {
                                id: el.id,
                                label: el.name
                            }
                        })
                        this.transformed.data_produksi.pemasaran_ekspor = data.data_produksi.pemasaran_ekspor.map(el => {
                            return {
                                id: el.id,
                                label: el.name
                            }
                        })
                        this.list = data
                        this.reloadLocation(data)
                    } else {
                        this.showError = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        reloadLocation() {
            this.getLocation('province', 0)
            this.getLocation('regency', this.list.data_umum.provinsi)
            this.getLocation('district', this.list.data_umum.kab_kota)
            this.getLocation('sub_district', this.list.data_umum.kecamatan)
        },
        reloadSelect(target) {
            if (target === 'kab_kota') {
                this.master.regency = []
                this.master.district = []
                this.master.sub_district = []
                this.getLocation('regency', this.list.data_umum.provinsi)
            } else if (target === 'kecamatan') {
                this.master.district = []
                this.master.sub_district = []
                this.getLocation('district', this.list.data_umum.kab_kota)
            } else if (target === 'kelurahan_desa') {
                this.master.sub_district = []
                this.getLocation('sub_district', this.list.data_umum.kecamatan)
            }
        },
        getLocation(type, parent) {
            const url = new UrlParse(`${BASE_API_URL}v1/location`, true)

            url.query.getParent = parent
            url.query.getType = type

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.master[type] = data
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        transformArrayObject(data) {
            return data.map(el => {
                return parseInt(el.id)
            })
        },
        transformArrayObjectStr(data) {
            return data.map(el => {
                return `${el.id}`
            })
        },
        transformSarprasForm(sarpras) {
            return sarpras.map(el => {
                return {
                    sarpras_id: el.sarpras_id ? el.sarpras_id : el.upi_sarpras_id,
                    nilai_unit: el.nilai_unit ? el.nilai_unit : 0,
                    nilai_kapasitas: el.nilai_kapasitas ? el.nilai_kapasitas : 0,
                    satuan: "kg"
                }
            })
        }
    },
    mounted() {
        let vm = this
        // vm.getProduct()
        // vm.getRegency()
        // vm.getCountry()
        // vm.getData()
    }
};
</script>
