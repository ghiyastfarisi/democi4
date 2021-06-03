<template>
    <div class="box">
        <div class="block">
            <div class="file">
                <label class="file-label">
                    <input class="file-input" type="file" name="resume" @change="onFileChange">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="file-label">
                            Klik Untuk Memilih Gambar
                        </span>
                    </span>
                    <span>
                        <a @click="actionUpload" v-if="urls.length > 0" href="javascript:void(0)" class="ml-2 button is-success">
                            <i class="fas fa-save"></i>&nbsp;&nbsp; Simpan Gambar
                        </a>
                    </span>
                </label>
            </div>
        </div>
        <div class="block">
            <figure v-for="(url, urli) in urls" :key="urli" class="image is-128x128 is-inline-block mr-2" style="border:1px dotted #ddd;">
                <img :src="url.ou" style="max-width:128px;max-height:128px;width:128px;height:128px;" />
                <a href="javascript:void(0)" @click="cancelUpload(urli)">hapus</a>
            </figure>
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
            uploadUsage: 'gambar_laporan',
            uploadType: 'image_upload',
            fileData: [],
            urls: []
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
        onFileChange(e) {
            const file = e.target.files[0];

            if (this.urls.length >= 5) {
                this.errorPopup("Maksimal 5 file upload")
                return
            }

            const cleanName = CleanupFileName(file.name)

            if (!this.fileData[cleanName]) {
                if (file) {
                    const ou = URL.createObjectURL(file)
                    this.fileData[cleanName] = file
                    this.urls.push({ ou, cleanName })
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
        async actionUpload() {
            const payload = new FormData()

            if (this.fileData) {
                payload.append('upload_usage', this.uploadUsage)
                payload.append('upload_type', this.uploadType)

                for (const el in this.fileData) {
                    payload.append('files[]', this.fileData[el])
                }

                await this.fetchUpload(payload)
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