<template>
    <div>
        <div v-if="blockDep.enableEdit">
            <nav class="level">
                <div class="level-left">
                    <p class="title is-4 is-spaced">Akun</p>
                </div>
                <div class="level-right">
                    <a href="javascript:void(0)" @click="showEditAkun=!showEditAkun" class="button is-warning is-small">
                        <span class="icon">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span>
                            Update
                        </span>
                    </a>
                </div>
            </nav>
            <DynamicModalForm
                v-if="showEditAkun"
                v-bind:modal-dep="{
                    title: 'Update Akun',
                    submit: 'Update',
                    modalType: 'adduser',
                    isEdit: true,
                    fetchEditUrl: `v1/user/${this.uid}`,
                    updateUrl: `v1/user/update/${this.uid}`,
                    extra: { userId: this.uid }
                }"
                @toggle-close="showEditAkun=!showEditAkun"
                @update-table="getData"
            />
        </div>
        <UpiBlock
            v-if="blockDep.blockType==='upi'"
            :block-dep="blockDep"
        />
        <UpiProduksiBlock
            v-if="blockDep.blockType==='upi_produksi'"
            :block-dep="blockDep"
        />
        <UpiSarprasBlock
            v-if="blockDep.blockType==='upi_sarpras'"
            :block-dep="blockDep"
        />
        <UpiTenagaKerjaBlock
            v-if="blockDep.blockType==='upi_tenaga_kerja'"
            :block-dep="blockDep"
        />
    </div>
</template>

<script>
import UpiBlock from './blocks/upiblock'
import UpiProduksiBlock from './blocks/upiproduksiblock'
import UpiSarprasBlock from './blocks/upisarprasblock'
import UpiTenagaKerjaBlock from './blocks/upitenagakerjablock'

export default {
    created() {},
    components: {
        UpiBlock,
        UpiProduksiBlock,
        UpiSarprasBlock,
        UpiTenagaKerjaBlock
    },
    data: function() {
        return {
            showError: false,
            showData: false,
            list: {},
            validFields: []
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
    methods: {},
    mounted() {}
};
</script>