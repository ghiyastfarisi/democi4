<template>
    <div class="modalform">
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card" style="width:1000px;">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ modalDep.title }}</p>
                    <button @click="emitClose" class="delete" aria-label="close"></button>
                </header>
                <UserForm
                    v-if="modalDep.modalType==='userform'"
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
                <UserPembinaMutuForm
                    v-if="modalDep.modalType==='adduserpembinamutu'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
                <PembinaMutuForm
                    v-if="modalDep.modalType==='addpembinamutu'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
                <LaporanForm
                    v-if="modalDep.modalType==='addlaporan'"
                    :form-dep="formDep" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
            </div>
        </div>
    </div>
</template>

<script>
import UserForm from './userform'
import RiwayatPendidikanForm from './riwayatpendidikanform'
import RiwayatJabatanForm from './riwayatjabatanform'
import RiwayatPelatihanForm from './riwayatpelatihanform'
import UserPembinaMutuForm from './userpembinamutuform'
import PembinaMutuForm from './pembinamutuform'
import LaporanForm from './laporanform'

export default {
    data: function() {
        return {
            formDep: this.modalDep
        }
    },
    components: {
        UserForm,
        RiwayatPendidikanForm,
        RiwayatJabatanForm,
        RiwayatPelatihanForm,
        UserPembinaMutuForm,
        PembinaMutuForm,
        LaporanForm
    },
    props: {
        modalDep: {
            type: Object,
            default: function() {
                return {
                    title: '',
                    submit: '',
                    modalType: '',
                    isEdit: false,
                    createUrl: '',
                    fetchEditUrl: '',
                    updateUrl: '',
                    extra: {}
                }
            }
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