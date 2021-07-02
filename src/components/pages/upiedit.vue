<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <UpiForm
                :formDep="{
                    editMode: 'edit',
                    ajaxUri: `v1/upi/${this.uid}/complete`,
                    upiId: this.uid,
                    submitTitle: 'Simpan Data'
                }"
            />
        </div>
    </div>
</template>

<script>
import UpiForm from '../forms/upiform'

export default {
    components: {
        UpiForm
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
