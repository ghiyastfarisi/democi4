<template>
    <div>
        <div class="mb-2">
            <div class="file">
                <label class="file-label">
                    <input class="file-input" type="file" name="resume" @change="onFileChange">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="file-label">
                            {{blockDep.uploadText}}
                        </span>
                    </span>
                    <span>
                        <a @click="actionUpload" v-if="urls.length > 0" href="javascript:void(0)" class="ml-2 button is-success">
                            <i class="fas fa-save"></i>&nbsp;&nbsp; {{blockDep.submitText}}
                        </a>
                    </span>
                </label>
            </div>
        </div>
        <div class="image-cont" v-if="blockDep.uploadType=='image_upload'">
            <figure v-for="(url, urli) in urls" :key="urli" class="image is-128x128 is-inline-block mr-2" style="border:1px dotted #ddd;">
                <img class="mb-1" :src="url.ou" style="max-width:128px;max-height:128px;width:128px;height:128px;" />
                <a href="javascript:void(0)" class="button is-danger is-small" @click="cancelUpload(urli)">hapus</a>
            </figure>
        </div>
        <div class="image-cont" v-if="blockDep.uploadType=='document_upload'">
            <span v-for="(url, urli) in urls" :key="urli">
                <div class="field has-addons mb-1">
                    <p class="control">
                        <a class="button is-static">
                            {{url.cleanName}}
                        </a>
                    </p>
                    <p class="control">
                        <input class="input" type="text" placeholder="Masukan nama file" v-model="fileNames[urli]">
                    </p>
                    <p class="control">
                        <a href="javascript:void(0)" class="button is-danger" @click="cancelUpload(urli)">hapus</a>
                    </p>
                </div>
            </span>
        </div>
    </div>
</template>

<script>
import { HandlePost, ParseError } from '../../lib/form'
import { AutoClosePopup } from '../../lib/popup'
import { CleanupFileName } from '../../lib/strings'

export default {
    created() {},
    components: {},
    data: function() {
        return {
            typeList: [ 'gambar_laporan', 'dokumen_laporan', 'foto_pabrik', 'foto_produk', 'foto_pembina_mutu' ],
            usageList: [ 'image_upload', 'document_upload' ],
            validations: {
                imageUpload: [
                    'image/png',
                    'image/jpeg'
                ],
                documentUpload: [
                    'application/pdf',
                    'application/zip',
                    'application/vnd.rar',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            },
            fileData: [],
            urls: [],
            fileNames: []
        }
    },
    props: {
        blockDep: {
            type: Object,
            default: function () {
                return {
                    uploadText: '',
                    uploadUsage: '',
                    uploadType: '',
                    submitText: 'Simpan Gambar',
                    fileNameInput: false,
                    extra: {}
                }
            }
        }
    },
    methods: {
        Close() {},
        submitData() {
        },
        closeAndPopup(title='', body ='', timeout=900) {
            AutoClosePopup({
                title,
                body,
                timeout
            })
        },
        errorPopup(message) {
            AutoClosePopup({
                title: message,
                body: '',
                timeout: 5000
            })
        },
        matchFileType(ft) {
            let vld = []
            if (this.blockDep.uploadType === 'image_upload') {
                vld = this.validations.imageUpload
            } else if (this.blockDep.uploadType === 'document_upload') {
                vld = this.validations.documentUpload
            }

            if (vld.findIndex(el => el === ft) < 0) {
                return false
            }

            return true
        },
        onFileChange(e) {
            const file = e.target.files[0];

            if (this.urls.length >= 5) {
                this.errorPopup("Maksimal 5 file upload")
                return
            }

            if (!this.matchFileType(file.type)) {
                this.errorPopup("Jenis file tidak didukung")
                return
            }

            const cleanName = CleanupFileName(file.name)

            if (!this.fileData[cleanName]) {
                if (file) {
                    const ou = URL.createObjectURL(file)
                    const ft = this.blockDep.uploadType === 'document_upload' ? 'doc' : 'img'

                    this.fileData[cleanName] = file
                    this.urls.push({ ou, cleanName, ft })
                }
            }
        },
        cancelUpload(id) {
            if (this.urls) {
                const cn = this.urls[id].cleanName
                delete this.fileData[cn]
                this.urls.splice(id, 1)
            }
        },
        postUpload() {
            this.fileData = []
            this.urls = []
            this.$emit('upload-done')
        },
        async actionUpload() {
            const payload = new FormData()

            if (this.fileData) {
                payload.append('upload_usage', this.blockDep.uploadUsage)
                payload.append('upload_type', this.blockDep.uploadType)
                payload.append('file_names', this.fileNames)

                for (const el in this.blockDep.extra) {
                    payload.append(el, this.blockDep.extra[el])
                }

                for (const el in this.fileData) {
                    payload.append('files[]', this.fileData[el])
                }

                await this.fetchUpload(payload)

                this.postUpload()
            } else {
                this.errorPopup('no file selected')
            }
        },
        async fetchUpload(payload) {
            const result = await HandlePost(
                `${BASE_API_URL}v1/upload/file`,
                payload
            )

            if (result.isError) {
                return this.errorPopup(
                    ParseError(result.message)
                )
            }

            return this.closeAndPopup(
                result.message
            )
        }
    },
    mounted() {
        let vm = this
    }
};
</script>
<style scoped>
</style>