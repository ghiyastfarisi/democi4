<template>
    <div class="modalform">
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ modalDep.title }}</p>
                    <button @click="emitClose" class="delete" aria-label="close"></button>
                </header>
                <UserForm
                    v-if="modalDep.modalType==='adduser'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
                <RiwayatPendidikanForm
                    v-if="modalDep.modalType==='addriwayatpendidikan'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
                <RiwayatJabatanForm
                    v-if="modalDep.modalType==='addriwayatjabatan'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
                <RiwayatPelatihanForm
                    v-if="modalDep.modalType==='addriwayatpelatihan'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
            </div>
        </div>
    </div>
</template>

<script>
const UserForm = require('./userform').default
const RiwayatPendidikanForm = require('./riwayatpendidikanform').default
const RiwayatJabatanForm = require('./riwayatjabatanform').default
const RiwayatPelatihanForm = require('./riwayatpelatihanform').default

module.exports = {
    created() {
        console.log("modalDep:", this.modalDep)
    },
    data: function() {
        return {
            formDep: {
                submit: this.modalDep.submit,
                isEdit: this.modalDep.isEdit,
                fetchEditUrl: this.modalDep.fetchEditUrl,
                updateUrl: this.modalDep.updateUrl,
                extra: this.modalDep.extra
            }
        }
    },
    components: {
        UserForm,
        RiwayatPendidikanForm,
        RiwayatJabatanForm,
        RiwayatPelatihanForm
    },
    props: {
        modalDep: {
            title: String,
            submit: String,
            modalType: String,
            isEdit: Boolean,
            fetchEditUrl: String,
            updateUrl: String,
            extra: Object
        }
    },
    methods: {
        emitClose() {
            this.$emit('toggle-close')
        },
        emitUpdateTable() {
            this.$emit('update-table')
        },
    }
};
</script>

<style scoped>
</style>