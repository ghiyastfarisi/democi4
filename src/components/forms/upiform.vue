<template>
    <div class="box">
        <div class="block">
            <form class="form-name-container" @submit.prevent="submitData">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label"> Nama Perusahaan </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="nama perusahaan" v-model="list.data_umum.nama_perusahaan">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Alamat Pabrik </label>
                            <div class="control">
                                <textarea class="textarea" placeholder="Alamat Pabrik" v-model="list.data_umum.alamat_pabrik"></textarea>
                            </div>
                        </div>
                        <label class="label"> Detail Alamat</label>
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select v-model="list.data_umum.provinsi" @change="reloadSelect('kab_kota')">
                                                <option v-for="(l, i) in master.province" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select v-model="list.data_umum.kab_kota" @change="reloadSelect('kecamatan')">
                                                <option v-for="(l, i) in master.regency" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select v-model="list.data_umum.kecamatan" @change="reloadSelect('kelurahan_desa')">
                                                <option v-for="(l, i) in master.district" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select v-model="list.data_umum.kelurahan_desa">
                                                <option v-for="(l, i) in master.sub_district" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Koordinat Lokasi </label>
                        </div>
                        <div class="field has-addons">
                            <p class="control is-expanded">
                                <input class="input" type="text" placeholder="Koordinat Lokasi" v-model="list.data_umum.koordinat_lokasi">
                            </p>
                            <p class="control">
                                <a class="button is-info" href="javascript:void(0)" @click="locatorButtonPressed">
                                    <span class="icon is-small">
                                        <i class="fas fa-map-marked"></i>
                                    </span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;Gunakan GPS
                                </a>
                            </p>
                        </div>
                        <div class="field">
                            <label class="label"> Nomor Induk Berusaha (NIB) </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Nomor Induk Berusaha (NIB)" v-model="list.data_umum.nib">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> No Kusuka </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Nomor KUSUKA" v-model="list.data_umum.no_kusuka">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> NPWP </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="NPWP" v-model="list.data_umum.npwp">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Nama Pemilik </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Nama Pemilik" v-model="list.data_umum.nama_pemilik">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Nama dan Nomor Kontak UPI </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Nama & Kontak UPI" v-model="list.data_umum.nama_kontak_upi">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Sertifikasi Perusahaan </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Sertifikasi Perusahaan" v-model="list.data_umum.sertifikasi_perusahaan">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Sumber Permodalan </label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="list.data_umum.sumber_permodalan">
                                        <option value="PMDN">PMDN</option>
                                        <option value="PMA">PMA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Foto Pabrik </label>
                            <div class="control">
                                <input class="input" type="text" v-model="list.data_umum.foto_pabrik">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Website </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Nama & Kontak UPI" v-model="list.data_umum.website">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Deskripsi Perusahaan </label>
                            <div class="control">
                                <textarea class="textarea" placeholder="Deskripsi Perusahaan" v-model="list.data_umum.deskripsi"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <nav class="level">
                                <div class="level-left">
                                    <p class="title is-4 is-spaced">Data Produksi</p>
                                </div>
                            </nav>
                        </div>
                        <div class="field">
                            <label class="label"> Produk yang Dihasilkan </label>
                            <div class="control">
                                <Multiselect
                                    :multiple="true"
                                    v-model="transformed.data_produksi.produk_dihasilkan"
                                    :options="master.products"
                                    track-by="id"
                                    label="label"
                                    style="width:100%"
                                />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Produk Utama </label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="list.data_produksi.produk_utama">
                                        <option v-for="(l, i) in transformed.data_produksi.produk_dihasilkan" :value="l.id" :key="i">
                                            {{ l.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Merk Dagang </label>
                            <div class="control">
                                <Multiselect
                                    v-model="transformed.data_produksi.merk_dagang"
                                    tag-placeholder="Enter untuk tambah merk baru"
                                    placeholder="Tambah Merk Dagang"
                                    :multiple="true"
                                    :taggable="true"
                                    @tag="addTag"
                                    :options="transformed.data_produksi.merk_dagang"
                                    style="width:100%"
                                />
                                <!-- <input class="input" type="text" placeholder="Merk Dagang" v-model="list.data_produksi.merk_dagang"> -->
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Kapasitas Maksimum Produk (Kg per Hari)</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Kapasitas Maksimum Produksi (Kg per Hari)" v-model="list.data_produksi.kapasitas_produksi_hari">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Rata-rata Jumlah Hari Produksi (Hari per Bulan) </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Rata-rata Jumlah Hari Produksi (Hari per Bulan)" v-model="list.data_produksi.rata_hari_produksi_bulan">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Rata-rata Jumlah Bulan Produksi (Bulan per Tahun) </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Rata-rata Jumlah Bulan Produksi (Bulan per Tahun)" v-model="list.data_produksi.rata_bulan_produksi_tahun">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Wilayah Pemasaran Domestik </label>
                            <div class="control">
                                <Multiselect
                                    :multiple="true"
                                    v-model="transformed.data_produksi.pemasaran_domestik"
                                    :options="master.domestik"
                                    track-by="id"
                                    label="label"
                                    style="width:100%"
                                />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Negara Pemasaran </label>
                            <div class="control">
                                <Multiselect
                                    :multiple="true"
                                    v-model="transformed.data_produksi.pemasaran_ekspor"
                                    :options="master.ekspor"
                                    track-by="id"
                                    label="label"
                                    style="width:100%"
                                />
                            </div>
                        </div>
                        <div class="field">
                            <nav class="level">
                                <div class="level-left">
                                    <p class="title is-4 is-spaced">Data Tenaga Kerja</p>
                                </div>
                            </nav>
                        </div>
                        <div class="field">
                            <label class="label"> Karyawan Tetap </label>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="text" placeholder="Karyawan Tetap" v-model="list.data_tenaga_kerja.karyawan_tetap_p" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                Perempuan
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="text" placeholder="Karyawan Tetap" v-model="list.data_tenaga_kerja.karyawan_tetap_l" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                Laki-laki
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Karyawan Harian </label>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="text" placeholder="Karyawan Harian" v-model="list.data_tenaga_kerja.karyawan_harian_p" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                Perempuan
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="text" placeholder="Karyawan Harian" v-model="list.data_tenaga_kerja.karyawan_harian_l" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                Laki-laki
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Jumlah Hari Kerja per Bulan </label>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="text" placeholder="Jumlah Hari Kerja per Bulan" v-model="list.data_tenaga_kerja.hari_kerja_bulan" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                Hari / Bulan
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Jumlah Shift per Hari </label>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="text" placeholder="Jumlah Shift per Hari" v-model="list.data_tenaga_kerja.shift_kerja_hari" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                Shift / Hari
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <nav class="level">
                                <div class="level-left">
                                    <p class="title is-4 is-spaced">Data Kapasitas Sarana dan Prasarana</p>
                                </div>
                            </nav>
                        </div>
                        <div class="field">
                            <table class="table is-fullwidth is-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Unit</th>
                                        <th>Kapasitas</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(l, i) in list.data_sarpras" :key="i">
                                        <td>{{ l.name }}</td>
                                        <td>
                                            <input class="input" type="text" placeholder="Unit" v-model="l.nilai_unit">
                                        </td>
                                        <td>
                                            <input class="input" type="text" placeholder="Kapasitas" v-model="l.nilai_kapasitas">
                                        </td>
                                        <td>
                                            <div class="field">
                                                <div class="control">
                                                    <div class="select is-fullwidth">
                                                        <select v-model="l.satuan">
                                                            <option value="kg">kg</option>
                                                            <option value="ton">ton</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="button is-success">Update</button>
                    <button type="cancel" @click="Close" class="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import UrlParse from 'url-parse'
import DynamicModalForm from '../forms/dynamicmodalform'
import { HandlePatch, ParseError } from '../../lib/form'
import { AutoClosePopup } from '../../lib/popup'
import Multiselect from 'vue-multiselect'

export default {
    created() {},
    components: {
        DynamicModalForm,
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
                    pemasaran_ekspor: [],
                    merk_dagang: []
                }
            },
            list: {
                data_umum: {},
                data_produksi: {
                    produk_dihasilkan: [],
                    pemasaran_domestik: [],
                    pemasaran_ekspor:[],
                    merk_dagang: []
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
        addTag (newTag) {
            this.transformed.data_produksi.merk_dagang.push(newTag)
        },
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
            payload.data_produksi.merk_dagang = this.transformed.data_produksi.merk_dagang.join(',')

            delete payload.data_produksi.id
            delete payload.data_produksi.upi_id
            delete payload.data_produksi.foto_produk
            delete payload.data_tenaga_kerja.id
            delete payload.data_tenaga_kerja.upi_id

            this.editData(payload)
        },
        closeAndPopup(title='', body ='', timeout=900) {
            // this.Close()

            AutoClosePopup({
                title,
                body,
                timeout
            })

            // this.$emit('update-table')
            this.getData()
        },
        errorPopup(message) {
            AutoClosePopup({
                title: message,
                body: '',
                timeout: 60000
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

            return this.closeAndPopup(result.message)
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
                        this.transformed.data_produksi.merk_dagang = data.data_produksi.merk_dagang.split(",")
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
                    satuan: el.satuan ? el.satuan : 'kg'
                }
            })
        }
    },
    mounted() {
        let vm = this
        vm.getProduct()
        vm.getRegency()
        vm.getCountry()
        vm.getData()
    }
};
</script>
