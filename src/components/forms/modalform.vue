<template>
    <div class="modalform">
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ title }}</p>
                    <button @click="emitClose" class="delete" aria-label="close"></button>
                </header>
                <UserForm v-if="mtype==='userform'" submit="submit" @toggle-close="emitClose" @update-table="emitUpdateTable"/>
            </div>
        </div>
    </div>
</template>

<script>
import UserForm from './userform'

export default {
    data: function() {
        return {}
    },
    components: {
        UserForm
    },
    props: {
        title: String,
        mtype: String,
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