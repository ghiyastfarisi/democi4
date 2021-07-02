<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <UpiForm
                :formDep="{
                    editMode: 'request',
                    ajaxUri: `v1/upi/${uid}/complete`,
                    upiId: uid,
                    submitTitle: 'Request Perubahan Data UPI',
                    extra: {
                        kunjunganId
                    }
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
        kunjunganId: Number
    },
    methods: {},
    mounted() {}
};
</script>
