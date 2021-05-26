<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <LaporanForm
                v-bind:block-dep="{
                    editMode: 'edit',
                    ajaxUri: `v1/upi/${this.uid}/complete`,
                    upiId: this.uid
                }"
            />
        </div>
    </div>
</template>

<script>
import LaporanForm from '../forms/laporanform'

export default {
    components: {
        LaporanForm
    },
    data: function() {
        return {
            showError: false,
            showData: false
        }
    },
    props: {
        ajaxUri: String,
        uid: Number,
    },
    methods: {},
    mounted() {}
};
</script>
