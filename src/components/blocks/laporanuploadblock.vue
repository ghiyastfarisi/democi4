<template>
    <div class="box">
        <div class="block">
            <div v-cloak>
                <nav class="level">
                    <div class="level-left">
                        <p class="title is-4 is-spaced">Dokumen dan Gambar Laporan</p>
                    </div>
                </nav>
                <div>
                    <div class="uploaded-image mb-5">
                        <DynamicFileUpload
                            :blockDep="{
                                uploadText: 'Klik Untuk Unggah Gambar Baru',
                                uploadUsage: 'gambar_laporan',
                                uploadType: 'image_upload',
                                submitText: 'Unggah dan Simpan Gambar',
                                extra: {
                                    kunjunganId: Number(blockDep.extra.kunjunganId)
                                }
                            }"
                            @upload-done="reloadPage"
                        />
                        <div v-cloak v-if="!hasImageData" class="notification is-danger mt-3">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                Belum Ada Gambar Tersimpan
                            </span>
                        </div>
                        <div class="images mt-5" v-if="hasImageData">
                            <DynamicModalImage
                                v-if="showModalImage"
                                :dep="{
                                    imageUrl: modalImageUrl,
                                    imageAlt: modalImageAlt
                                }"
                                @toggle-close="closeModalImage"
                            />
                            <div class="columns is-multiline">
                                <div class="column is-one-quarter" v-for="(image) in list.images" :key="image.id">
                                    <div class="card">
                                        <div class="card-image">
                                            <figure
                                                class="image"
                                                :style="{
                                                    height: '256px',
                                                    maxHeight: '256px',
                                                    backgroundImage: 'url('+image.upload_path+')',
                                                    backgroundSize: '80%',
                                                    backgroundPositionX: 'center',
                                                    backgroundPositionY: 'center',
                                                    backgroundRepeat: 'no-repeat'
                                                }">
                                            </figure>
                                        </div>
                                        <footer class="card-footer">
                                            <a href="javascript:void(0)" class="card-footer-item" @click="openModalImage(image.upload_path)">
                                                <span class="icon">
                                                    <i class="fas fa-search-plus"></i>
                                                </span> View
                                            </a>
                                            <a href="javascript:void(0)" class="card-footer-item" @click="deleteFile(image.id)">
                                                <span class="icon">
                                                    <i class="fas fa-times"></i>
                                                </span> Delete
                                            </a>
                                        </footer>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div>
                    <div class="uploaded-document mb-2">
                        <DynamicFileUpload
                            :blockDep="{
                                uploadText: 'Klik Untuk Unggah Dokumen Baru',
                                uploadUsage: 'dokumen_laporan',
                                uploadType: 'document_upload',
                                submitText: 'Unggah dan Simpan Dokumen',
                                fileNameInput: true,
                                extra: {
                                    kunjunganId: Number(blockDep.extra.kunjunganId)
                                }

                            }"
                            @upload-done="reloadPage"
                        />
                        <div v-cloak v-if="!hasDocData" class="notification is-danger mt-3">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                Belum Ada Dokumen Tersimpan
                            </span>
                        </div>
                        <div class="documents mt-5" v-if="hasDocData">
                            <table class="table is-fullwidth is-striped">
                                <thead>
                                    <tr>
                                        <th><abbr title="number">#</abbr></th>
                                        <th>Dokumen</th>
                                        <th>Setting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(doc, docIndex) in list.documents" :key="doc.id">
                                        <td>{{ docIndex+1 }}</td>
                                        <td>
                                            <a :href="doc.upload_path" class="button is-default is-small" target="_blank">
                                                <span class="icon">
                                                    <i class="fas fa-download"></i>
                                                </span> &nbsp;&nbsp;
                                                {{doc.file_name}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="button is-danger is-small" @click="deleteFile(doc.id)">
                                                <span class="icon">
                                                    <i class="fas fa-times"></i>
                                                </span> &nbsp;&nbsp; Delete
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DynamicModalForm from '../forms/dynamicmodalform'
import DynamicFileUpload from '../forms/dynamicfileupload'
import FileUpload from '../forms/fileupload'
import DynamicModalImage from '../dynamicmodalimage'
import Swal from 'sweetalert2'
import { HandleDelete } from '../../lib/form'

export default {
    created() {},
    components: {
        DynamicModalForm,
        DynamicFileUpload,
        FileUpload,
        DynamicModalImage
    },
    data: function() {
        return {
            showModalImage: false,
            showData: false,
            hasImageData: false,
            hasDocData: false,
            modalImageUrl: '',
            modalImageAlt: '',
            list: {},
        }
    },
    props: {
        blockDep: {
            type: Object,
            default: function () {
                return {
                    enableEdit: false,
                    ajaxUri: '',
                    blockType: '',
                    extra: {
                        kunjunganId: 0
                    }
                }
            }
        }
    },
    methods: {
        deleteFile(id) {
            Swal.fire({
                title: 'Delete data?',
                showCancelButton: true,
                confirmButtonText: `Confirm Delete`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.actionDelete(id)
                }
            })
        },
        async actionDelete(id) {
            const result = await HandleDelete(`${BASE_API_URL}v1/kunjungan/file/${id}`)

            if (result.isError) {
                Swal.fire(ParseError(result.message), '', 'error')
            } else {
                Swal.fire(result.message, '', 'success')
            }

            this.reloadPage()
        },
        openModalImage(url){
            this.modalImageUrl = url
            this.modalImageAlt = `alt ${url}`
            this.showModalImage = !this.showModalImage
        },
        closeModalImage(){
            this.showModalImage = !this.showModalImage
        },
        reloadPage(){
            this.getData()
        },
        getData() {
            const url = BASE_API_URL + this.blockDep.ajaxUri

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data !== null) {
                        const { images, documents } = data

                        if (images.length) {
                            this.hasImageData = true
                            data.images = data.images.map(el => {
                                el.upload_path = `${BASE_URL}/${el.upload_path}`

                                return el
                            })
                        } else {
                            this.hasImageData = false
                        }

                        if (documents.length) {
                            this.hasDocData = true
                            data.documents = data.documents.map(el => {
                                el.upload_path = `${BASE_URL}/${el.upload_path}`

                                return el
                            })
                        } else {
                            this.hasDocData = false
                        }

                        this.list = data
                    } else {
                        this.hasImageData = false
                        this.hasDocData = false
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