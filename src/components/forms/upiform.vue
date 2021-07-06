<template>
    <div class="box">
        <div class="block">
            <div v-if="!showData && !showError" class="has-text-centered">
                <span class="icon is-large">
                    <i class="fas fa-2x fa-cog fa-spin"></i>
                </span>
                <span class="title is-3">Loading Data</span>
            </div>
            <div v-cloak v-if="showError" class="notification is-danger">
                <span class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </span>
                <span>
                    UPI not found
                </span>
            </div>
            <form v-cloak v-if="showData" class="form-name-container" @submit.prevent="submitData">
                <div class="columns is-multiline">
                    <div class="column is-12" v-if="formDep.editMode=='request'">
                        <div class="field">
                            <label class="label">Kunjungan Terkait</label>
                            <Multiselect
                                v-if="formDep.isEdit"
                                v-model="list.kunjungan_id"
                                id="ajax"
                                track-by="id"
                                label="label"
                                placeholder="Cari Kunjungan Terkait"
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
                        <div>
                            <table class="table is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>Kegiatan</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Provinsi</th>
                                        <th>Pembina Mutu</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>{{master.kunjungan.kegiatan}}</td>
                                    <td>{{master.kunjungan.tanggal_kunjungan}}</td>
                                    <td>{{master.kunjungan.nama_provinsi}}</td>
                                    <td>{{master.kunjungan.nama_pembina_mutu}}</td>
                                    <td>
                                        <div class="control" v-html="master.kunjungan.catatanForm">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <nav class="level">
                                <div class="level-left">
                                    <p class="title is-4 is-spaced">Data Umum</p>
                                </div>
                            </nav>
                        </div>
                        <div class="field">
                            <label class="label"> Nama Perusahaan </label>
                            <div class="control">
                                <input :class="validation.classHandler.nama_perusahaan" class="input" required type="text" placeholder="nama perusahaan" v-model="list.data_umum.nama_perusahaan">
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.nama_perusahaan">{{validation.messageHandler.nama_perusahaan}}</p>
                        </div>
                        <div class="field">
                            <label class="label"> Alamat Pabrik </label>
                            <div class="control">
                                <textarea class="textarea" :class="validation.classHandler.alamat_pabrik" placeholder="Alamat Pabrik" v-model="list.data_umum.alamat_pabrik"></textarea>
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.alamat_pabrik">{{validation.messageHandler.alamat_pabrik}}</p>
                        </div>
                        <label class="label"> Detail Alamat</label>
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <p class="help is-info mb-3">
                                        <span class="icon is-small">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        Provinsi
                                    </p>
                                    <div class="control">
                                        <div :class="validation.classHandler.provinsi" class="select is-fullwidth">
                                            <select v-model="list.data_umum.provinsi" @change="reloadSelect('kab_kota')">
                                                <option v-for="(l, i) in master.province" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="help is-danger" v-if="validation.messageHandler.provinsi">{{validation.messageHandler.provinsi}}</p>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <p class="help is-info mb-3">
                                        <span class="icon is-small">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        Kabupatan/Kota
                                    </p>
                                    <div class="control">
                                        <div :class="validation.classHandler.kab_kota" class="select is-fullwidth">
                                            <select v-model="list.data_umum.kab_kota" @change="reloadSelect('kecamatan')">
                                                <option v-for="(l, i) in master.regency" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="help is-danger" v-if="validation.messageHandler.kab_kota">{{validation.messageHandler.kab_kota}}</p>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <p class="help is-info mb-3">
                                        <span class="icon is-small">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        Kecamatan
                                    </p>
                                    <div class="control">
                                        <div :class="validation.classHandler.kecamatan" class="select is-fullwidth">
                                            <select v-model="list.data_umum.kecamatan" @change="reloadSelect('kelurahan_desa')">
                                                <option v-for="(l, i) in master.district" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="help is-danger" v-if="validation.messageHandler.kecamatan">{{validation.messageHandler.kecamatan}}</p>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <p class="help is-info mb-3">
                                        <span class="icon is-small">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        Kelurahan/Desa
                                    </p>
                                    <div class="control">
                                        <div :class="validation.classHandler.kelurahan_desa" class="select is-fullwidth">
                                            <select v-model="list.data_umum.kelurahan_desa">
                                                <option v-for="(l, i) in master.sub_district" :value="l.id" :key="i">
                                                    {{ l.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="help is-danger" v-if="validation.messageHandler.kelurahan_desa">{{validation.messageHandler.kelurahan_desa}}</p>
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
                                <input :class="validation.classHandler.nib" class="input" type="text" required placeholder="Nomor Induk Berusaha (NIB)" v-model="list.data_umum.nib">
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.nib">{{validation.messageHandler.nib}}</p>
                        </div>
                        <div class="field">
                            <label class="label"> No Kusuka </label>
                            <div class="control">
                                <input :class="validation.classHandler.no_kusuka" class="input" type="text" required placeholder="Nomor KUSUKA" v-model="list.data_umum.no_kusuka">
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.no_kusuka">{{validation.messageHandler.no_kusuka}}</p>
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
                                <Multiselect
                                    :multiple="true"
                                    v-model="transformed.sertifikasi"
                                    :options="master.badges"
                                    placeholder="Pilih Sertifikasi"
                                    track-by="id"
                                    label="label"
                                    style="width:100%"
                                />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Sumber Permodalan </label>
                            <div class="control">
                                <div :class="validation.classHandler.sumber_permodalan" class="select is-fullwidth">
                                    <select v-model="list.data_umum.sumber_permodalan">
                                        <option value="PMDN">PMDN</option>
                                        <option value="PMA">PMA</option>
                                    </select>
                                </div>
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.sumber_permodalan">{{validation.messageHandler.sumber_permodalan}}</p>
                        </div>
                        <div class="field">
                            <label class="label"> Foto Pabrik </label>
                            <div class="control">
                                <div class="file">
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="resume" @change="onFileChange">
                                        <span class="file-cta">
                                            <span class="file-icon">
                                                <i class="fas fa-upload"></i>
                                            </span>
                                            <span class="file-label">
                                                Upload Foto Pabrik
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="image-current mr-2 mb-2 mt-2" v-if="list.data_umum.foto_pabrik">
                                    <figure class="image is-inline-block p-1" style="border:1px dotted #ddd;">
                                        <img :src="list.data_umum.foto_pabrik" style="max-width:128px;width:128px;" />
                                    </figure>
                                </div>
                                <div class="image-cont mr-2 mb-2 mt-2" v-if="image.display">
                                    <figure class="image is-inline-block p-1" style="border:1px dotted #ddd;">
                                        <img :src="image.display" style="max-width:128px;width:128px;" />
                                        <a href="javascript:void(0)" class="button is-danger is-small mt-1 is-fullwidth" @click="cancelUpload">Batal Upload</a>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Website </label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Alamat Website Perusahaan" v-model="list.data_umum.website">
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
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Kapasitas Maksimum Produk (Kg per Hari)</label>
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka
                            </p>
                            <div class="control">
                                <input :class="validation.classHandler.kapasitas_produksi_hari" class="input" type="number" pattern="[0-9]*" required placeholder="Kapasitas Maksimum Produksi (Kg per Hari)" v-model="list.data_produksi.kapasitas_produksi_hari">
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.kapasitas_produksi_hari">{{validation.messageHandler.kapasitas_produksi_hari}}</p>
                        </div>
                        <div class="field">
                            <label class="label"> Rata-rata Jumlah Hari Produksi (Hari per Bulan) </label>
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka (1-31)
                            </p>
                            <div class="control">
                                <input :class="validation.classHandler.rata_hari_produksi_bulan" class="input" type="number" pattern="[0-9]*" placeholder="Rata-rata Jumlah Hari Produksi (Hari per Bulan)" v-model="list.data_produksi.rata_hari_produksi_bulan">
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.rata_hari_produksi_bulan">{{validation.messageHandler.rata_hari_produksi_bulan}}</p>
                        </div>
                        <div class="field">
                            <label class="label"> Rata-rata Jumlah Bulan Produksi (Bulan per Tahun) </label>
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka (1-12)
                            </p>
                            <div class="control">
                                <input :class="validation.classHandler.rata_bulan_produksi_tahun" class="input" type="number" pattern="[0-9]*" placeholder="Rata-rata Jumlah Bulan Produksi (Bulan per Tahun)" v-model="list.data_produksi.rata_bulan_produksi_tahun">
                            </div>
                            <p class="help is-danger" v-if="validation.messageHandler.rata_bulan_produksi_tahun">{{validation.messageHandler.rata_bulan_produksi_tahun}}</p>
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
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka
                            </p>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Karyawan Tetap" v-model="list.data_tenaga_kerja.karyawan_tetap_p" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static" style="width:130px;">
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
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Karyawan Tetap" v-model="list.data_tenaga_kerja.karyawan_tetap_l" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static" style="width:130px;">
                                                Laki - laki
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Karyawan Harian </label>
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka
                            </p>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Karyawan Harian" v-model="list.data_tenaga_kerja.karyawan_harian_p" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static" style="width:130px;">
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
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Karyawan Harian" v-model="list.data_tenaga_kerja.karyawan_harian_l" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static" style="width:130px;">
                                                Laki-laki
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Jumlah Hari Kerja per Bulan </label>
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka (1-31)
                            </p>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Jumlah Hari Kerja per Bulan" v-model="list.data_tenaga_kerja.hari_kerja_bulan" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static" style="width:130px;">
                                                Hari / Bulan
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Jumlah Shift per Hari </label>
                            <p class="help is-info">
                                <span class="icon is-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                hanya bisa diisi dengan angka
                            </p>
                        </div>
                        <div class="field">
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control is-expanded">
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Jumlah Shift per Hari" v-model="list.data_tenaga_kerja.shift_kerja_hari" />
                                        </p>
                                        <p class="control">
                                            <a class="button is-static" style="width:130px;">
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
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Unit" v-model="l.nilai_unit">
                                        </td>
                                        <td>
                                            <input class="input" type="number" pattern="[0-9]*" placeholder="Kapasitas" v-model="l.nilai_kapasitas">
                                        </td>
                                        <td>
                                            <div class="field">
                                                <div class="control">
                                                    <div class="select is-fullwidth">
                                                        <select v-if="l.metric=='weight'" v-model="l.satuan">
                                                            <option value="kg">kg</option>
                                                            <option value="ton">ton</option>
                                                        </select>
                                                        <select v-if="l.metric=='duration'" v-model="l.satuan">
                                                            <option value="jam">jam</option>
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
                    <button type="submit" class="button is-success">{{formDep.submitTitle}}</button>
                    <button type="cancel" @click="Close" class="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import UrlParse from 'url-parse'
import DynamicModalForm from '../forms/dynamicmodalform'
import { HandlePatch, HandlePost, ParseError, HandlePostUpload } from '../../lib/form'
import { AutoClosePopup } from '../../lib/popup'
import Multiselect from 'vue-multiselect'
import FValid from 'fastest-validator'
import Dayjs from 'dayjs'

export default {
    components: {
        DynamicModalForm,
        Multiselect,
    },
    data: function() {
        return {
            AUTH: {},
            baseUrl: BASE_URL,
            showEditAkun: false,
            showError: false,
            showData: false,
            backUrl: this.formDep.upiId > 0 ? `${BASE_URL}/web/upi/get/${this.formDep.upiId}` : `${BASE_URL}/web/upi`,
            master: {
                province: [],
                regency: [],
                district: [],
                sub_district: [],
                products: [],
                ekspor: [],
                domestik: [],
                badges: [],
                kunjungan: {}
            },
            transformed: {
                sertifikasi: [],
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
                data_sarpras: [],
                kunjungan_id: 0
            },
            validation: {
                classHandler: {},
                messageHandler: {}
            },
            image: {
                display: '',
                object: {}
            }
        }
    },
    props: {
        formDep: {
            type: Object,
            default: function () {
                return {
                    editMode: 'request',
                    ajaxUri: '',
                    upiId: 0,
                    isCreate: false,
                    createUrl: '',
                    submitTitle: 'Simpan Data',
                    extra: {
                        kunjunganId: 0
                    }
                }
            }
        }
    },
    methods: {
        Close() {},
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
        cancelUpload() {
            this.image = {
                display: '',
                object: {}
            }
        },
        onFileChange(e) {
            const file = e.target.files[0];

            if (file) {
                if (file.type !== 'image/png' && file.type !== 'image/jpeg') {
                    return this.errorPopup("Jenis file tidak didukung")
                }

                const ou = URL.createObjectURL(file)

                this.image.display = ou
                this.image.uploadType = 'image_upload'
                this.image.uploadUsage = 'foto_pabrik'
                this.image.object = file
            }

        },
        addTag (newTag) {
            this.transformed.data_produksi.merk_dagang.push(newTag)
        },
        getBadge() {
            const url = `${BASE_API_URL}v1/badge/all?category=upi&transformLabel=true`
            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data) {
                        this.master.badges = data
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
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
                    this.list.data_umum = Object.assign({}, this.list.data_umum, { koordinat_lokasi: latlong })
                },
                error => {
                    console.error(error.message);
                },
            )
        },
        validateDataUmum(items) {
            this.validation.classHandler = {}
            this.validation.messageHandler = {}

            const v = new FValid({
                messages: {
                    required: "Wajib isi"
                }
            })
            const schema = {
                nama_perusahaan: "string",
                alamat_pabrik: "string",
                provinsi: {
                    type: 'number',
                    min: 1,
                    messages: {
                        number: 'harap pilih data yang sesuai',
                        min: 'wajib isi'
                    }
                },
                kab_kota: {
                    type: 'number',
                    min: 1,
                    messages: {
                        number: 'harap pilih data yang sesuai',
                        min: 'wajib isi'
                    }
                },
                kecamatan: {
                    type: 'number',
                    min: 1,
                    messages: {
                        number: 'harap pilih data yang sesuai',
                        min: 'wajib isi'
                    }
                },
                kelurahan_desa: {
                    type: 'number',
                    min: 1,
                    messages: {
                        number: 'harap pilih data yang sesuai',
                        min: 'wajib isi'
                    }
                },
                nib: {
                    type: 'number',
                    length: 13,
                    messages: {
                        number: 'isi dengan angka NIB',
                        length: 'format NIB tidak sesuai'
                    }
                },
                no_kusuka: {
                    type: 'number',
                    messages: {
                        number: 'isi dengan angka No. Kusuka'
                    }
                },
                sumber_permodalan: {
                    type: 'enum',
                    values: [ 'PMA', 'PMDN' ],
                    messages: {
                        enum: 'hanya PMA dan PMDN',
                    }
                }
            }
            const validate = v.compile(schema)
            const result = validate(items)
            const msg = {}
            const cls = {}

            if (typeof result === 'boolean') {
                return true
            } else if (typeof result === 'object' || typeof result === 'array') {
                for (const key in result) {
                    if (result[key].message && result[key].message !== '') {
                        msg[result[key].field] = result[key].message
                        cls[result[key].field] = 'is-danger'
                    }
                }

                this.validation.classHandler =  Object.assign({}, this.validation.classHandler, cls)
                this.validation.messageHandler =  Object.assign({}, this.validation.messageHandler, msg)

                setTimeout(function() {
                    const pos = document.getElementsByClassName('is-danger')[0].getBoundingClientRect()
                    const bodypos = document.body.getBoundingClientRect()
                    const cocok = (Math.abs(bodypos.top) - Math.abs(pos.top)) - 45

                    window.scrollTo({
                        top: cocok,
                        left: 0,
                        behavior: 'smooth'
                    });
                }, 500)
            }

            return false
        },
        validateDataProduksi(items) {
            this.validation.classHandler = {}
            this.validation.messageHandler = {}

            const v = new FValid({
                messages: {
                    required: "Wajib isi"
                }
            })
            const schema = {
                kapasitas_produksi_hari: {
                    type: "number",
                    min: 1
                },
                rata_hari_produksi_bulan: {
                    type: "number",
                    min: 1,
                    max: 31
                },
                rata_bulan_produksi_tahun: {
                    type: "number",
                    min: 1,
                    max: 12
                }
            }
            const validate = v.compile(schema)
            const result = validate(items)
            const msg = {}
            const cls = {}

            if (typeof result === 'boolean') {
                return true
            } else if (typeof result === 'object' || typeof result === 'array') {
                for (const key in result) {
                    if (result[key].message && result[key].message !== '') {
                        msg[result[key].field] = result[key].message
                        cls[result[key].field] = 'is-danger'
                    }
                }

                this.validation.classHandler =  Object.assign({}, this.validation.classHandler, cls)
                this.validation.messageHandler =  Object.assign({}, this.validation.messageHandler, msg)

                setTimeout(function() {
                    const pos = document.getElementsByClassName('is-danger')[0].getBoundingClientRect()
                    const bodypos = document.body.getBoundingClientRect()
                    const cocok = (Math.abs(bodypos.top) - Math.abs(pos.top)) - 45

                    window.scrollTo({
                        top: cocok,
                        left: 0,
                        behavior: 'smooth'
                    });
                }, 500)
            }

            return false
        },
        validatePayload(payload) {
            return this.validateDataUmum(payload.data_umum) && this.validateDataProduksi(payload.data_produksi)
        },
        async submitData() {
            const payload = Object.assign({}, this.list)

            payload.data_sarpras = this.transformSarprasForm(payload.data_sarpras)
            payload.data_produksi.produk_dihasilkan = this.transformArrayObject(this.transformed.data_produksi.produk_dihasilkan)
            payload.data_produksi.pemasaran_domestik = this.transformArrayObject(this.transformed.data_produksi.pemasaran_domestik)
            payload.data_produksi.pemasaran_ekspor = this.transformArrayObject(this.transformed.data_produksi.pemasaran_ekspor)
            payload.data_produksi.merk_dagang = this.transformed.data_produksi.merk_dagang.join(',')
            payload.data_umum.sertifikasi = this.transformArrayObject(this.transformed.sertifikasi);

            delete payload.data_produksi.id
            delete payload.data_produksi.upi_id
            delete payload.data_produksi.foto_produk
            delete payload.data_tenaga_kerja.id
            delete payload.data_tenaga_kerja.upi_id

            payload.data_umum = {
                ...payload.data_umum,
                provinsi: parseInt(payload.data_umum.provinsi),
                kab_kota: parseInt(payload.data_umum.kab_kota),
                kecamatan: parseInt(payload.data_umum.kecamatan),
                kelurahan_desa: parseInt(payload.data_umum.kelurahan_desa),
                nib: parseInt(payload.data_umum.nib),
                no_kusuka: parseInt(payload.data_umum.no_kusuka)
            }

            payload.data_produksi = {
                ...payload.data_produksi,
                kapasitas_produksi_hari: parseInt(payload.data_produksi.kapasitas_produksi_hari),
                rata_hari_produksi_bulan: parseInt(payload.data_produksi.rata_hari_produksi_bulan),
                rata_bulan_produksi_tahun: parseInt(payload.data_produksi.rata_bulan_produksi_tahun)
            }

            if (this.validatePayload(payload)) {
                const uploaded = await this.actionUpload()

                if (uploaded && uploaded.upload_path) {
                    payload.data_umum.foto_pabrik = `${this.baseUrl}/${uploaded.upload_path}`
                }

                if (this.formDep.isCreate) {
                    return this.createData(payload)
                } else {
                    if (this.formDep.editMode === 'request') {
                        return this.requestEditData(payload)
                    } else if (this.formDep.editMode === 'edit') {
                        return this.editData(payload)
                    }
                }
            }

            return false
        },
        async fetchUpload(payload) {
            const result = await HandlePostUpload(
                `${BASE_API_URL}v1/upload/file`,
                payload
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            if (result.origin && result.origin.data && result.origin.data.path) {
                const [uploaded] = result.origin.data.path

                return uploaded
            }
        },
        async actionUpload() {
            const payload = new FormData()

            if (this.image && this.image.object && this.image.display != '') {
                payload.append('upload_usage', this.image.uploadUsage)
                payload.append('upload_type', this.image.uploadType)
                payload.append('files[]', this.image.object)

                const uploaded = await this.fetchUpload(payload)

                return uploaded
            }

            return {}
        },
        closeAndPopup(title='', body ='', timeout=900, redirect=false) {
            AutoClosePopup({
                title,
                body,
                timeout
            })

            const getBack = this.backUrl

            if (redirect) {
                setTimeout(function() { window.location.replace(getBack) } , timeout+250)
            }
        },
        errorPopup(message) {
            AutoClosePopup({
                title: message,
                body: '',
                timeout: 3000
            })
        },
        async editData(payload) {
            const result = await HandlePatch(
                `${BASE_API_URL}v1/upi/${this.formDep.upiId}/update/complete`,
                JSON.stringify(payload)
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            return this.closeAndPopup(result.message, '', 1200, true)
        },
        async requestEditData(payload) {
            payload.kunjungan_id = this.master.kunjungan.id

            const result = await HandlePost(
                `${BASE_API_URL}v1/upi/${this.formDep.upiId}/request/perubahan-upi`,
                JSON.stringify(payload)
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            return this.closeAndPopup(result.message, '', 1200, true)
        },
        async createData(payload) {
            const result = await HandlePost(
                this.formDep.createUrl,
                JSON.stringify(payload)
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            return this.closeAndPopup(result.message, '', 1200, true)
        },
        getData() {
            const url = `${BASE_API_URL}${this.formDep.ajaxUri}`

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data && data !== null) {
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
                        this.transformed.sertifikasi = data.data_umum.sertifikasi.map(el => {
                            return {
                                id: el.badge_id,
                                label: el.name
                            }
                        })
                        this.reloadLocation(data)
                    } else {
                        this.showError = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                    this.showError = true
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
        getSarpras() {
            const url = new UrlParse(`${BASE_API_URL}v1/sarpras/upi/0`, true)

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        data.forEach(el => {
                            el.sarpras_id = el.id
                            el.nilai_unit = 0
                            el.nilai_kapasitas = 0
                            el.ukuran = el.metric === 'weight' ? 'kg' : 'jam'
                            el.satuan = el.metric === 'weight' ? 'kg' : 'jam'

                            this.list.data_sarpras.push(el)
                        })
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
        },
        parseForDisplay(txt) {
            return txt.split('\n').join('<br/>')
        },
        async getKunjungan(kunjunganId) {
            return fetch(`${BASE_API_URL}v1/kunjungan/${kunjunganId}`)
                .then(async stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        data.tanggal_kunjungan = Dayjs(data.tanggal_kunjungan).format('DD-MM-YYYY')
                        data.catatanForm = this.parseForDisplay(data.catatan)
                        this.master.kunjungan = Object.assign({}, this.master.kunjungan, data)

                        return data
                    }

                    return {}
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                    return {}
                })
        },
        async validatePage() {
            if (!this.formDep.isCreate) {
                if (this.formDep.extra && this.formDep.extra.kunjunganId && this.formDep.extra.kunjunganId > 0) {
                    const kunjungan = await this.getKunjungan(this.formDep.extra.kunjunganId)

                    if (kunjungan && kunjungan.upi_id) {
                        const fetchedUpiId = parseInt(kunjungan.upi_id)

                        if (fetchedUpiId !== this.formDep.upiId) {
                            return false
                        }

                        return true
                    } else {
                        return false
                    }
                } else {
                    return true
                }
            } else {
                return true
            }
        }
    },
    async created() {
        const valid = await this.validatePage()
        if (valid) {
            this.AUTH = window.COOKIE_OBJECT
            this.getBadge()
            this.getProduct()
            this.getRegency()
            this.getCountry()
            if (this.formDep.isCreate) {
                this.getLocation('province', 0)
                this.getSarpras()
                this.showData = true
            } else {
                this.getData()
            }
        } else {
            alert('ERROR!')
            this.showError = true
        }
    }
};
</script>
