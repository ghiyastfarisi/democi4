<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <UpiForm
                :formDep="{
                    formMode: '',
                    ajaxUri: ``,
                    upiId: 0,
                    isCreate: true,
                    createUrl,
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
            createUrl: `${BASE_API_URL}v1/upi/create/complete`,
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
