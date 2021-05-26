<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enable-add="{
                    valid: false,
                    addDep: {}
                }"
                :table-dep="{
                    fieldType: 'pembina_mutu',
                    ajaxUri: `v1/pembina-mutu/all?getInstansi=true`,
                    showLimit: 20,
                    deleteUrl: 'v1/pembina-mutu/',
                    detailUrl: 'pembina-mutu/'
                }"
                :enable-edit="{
                    valid: false,
                    editDep: {}
                }"
            />
        </div>
    </div>
</template>

<script>
import DynamicTable from '../dynamictable'
import DynamicModalForm from '../forms/dynamicmodalform'

export default {
    components: {
        DynamicTable,
        DynamicModalForm,
    },
    data: function() {
        return {
            showError: false,
            showData: false,
            userData: {
                role: 'pembina_mutu'
            },
            pembinaMutuData: {},
        }
    },
    props: {},
    methods: {},
    mounted() {}
};
</script>
