<template>
    <div class="box">
        <div class="block">
            <div v-cloak v-if="showError" class="notification is-danger">
                <span class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </span>
                <span>
                    UPI not found
                </span>
            </div>
            <div v-cloak v-if="showData">
                <nav class="level">
                    <div class="level-left">
                        <p class="title is-4 is-spaced">{{ list.nama_perusahaan }}</p>
                    </div>
                </nav>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <figure class="image">
                            <img v-if="list.foto_pabrik!=''" :src="list.foto_pabrik" style="max-width:400px;max-height:400px;">
                        </figure>
                    </div>
                    <div class="column is-12">
                        <span v-for="list in list.sertifikasi" :key="list.id">
                            <img :src="`${baseUrl}/asset/${list.category}/${list.code}.png`" style="width:100px;height:100px;margin-right:15px;">
                        </span>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label class="label"> Alamat Pabrik </label>
                            <div class="control">
                                {{ list.alamat_pabrik }}
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                KEL. {{ list.location.sub_district.name }},
                                KEC. {{ list.location.district.name }},
                                {{ list.location.regency.name.toUpperCase() }},
                                {{ list.location.province.name.toUpperCase() }}
                                {{ list.location.sub_district.zipcode }}
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <a :href="list.website">
                                    <span class="icon">
                                        <i class="fas fa-link"></i>
                                    </span> Link Website
                                </a>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Koordinat Lokasi </label>
                            <div class="control">
                                {{ list.koordinat_lokasi && list.koordinat_lokasi !== '' ? list.koordinat_lokasi : '-' }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Nomor Induk Berusaha (NIB) </label>
                            <div class="control">
                                {{ list.nib }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> No Kusuka </label>
                            <div class="control">
                                {{ list.no_kusuka }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> NPWP </label>
                            <div class="control">
                                {{ list.npwp }}
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label class="label"> Nama Pemilik </label>
                            <div class="control">
                                {{ list.nama_pemilik }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Nama dan Nomor Kontak UPI </label>
                            <div class="control">
                                {{ list.nama_kontak_upi }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Sumber Permodalan </label>
                            <div class="control">
                                {{ list.sumber_permodalan }}
                            </div>
                        </div>
                        <div class="field">
                            <label class="label"> Deskripsi Perusahaan </label>
                            <div class="control">
                                {{ list.deskripsi && list.deskripsi !== '' ? list.deskripsi : '-' }}
                            </div>
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
            baseUrl: BASE_URL,
            showEditAkun: false,
            showError: false,
            showData: false,
            list: {
                koordinat_lokasi: '-',
                location: {
                    province: {
                        name: '-'
                    },
                    regency: {
                        name: '-'
                    },
                    district: {
                        name: '-'
                    },
                    sub_district: {
                        name: '-',
                        zipcode: '-'
                    }
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
        getData() {
            const url = BASE_API_URL + this.blockDep.ajaxUri

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        this.showData = true
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