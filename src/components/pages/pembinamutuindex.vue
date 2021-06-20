<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enableAdd="{
                    valid: false
                }"
                :tableDep="{
                    ajaxUri: `v1/pembina-mutu/all?getInstansi=true`,
                    deleteUrl: 'v1/pembina-mutu/',
                    showLimit: 25,
                    renderObject: [
                        {
                            fieldName: 'Nama',
                            objectField: 'nama_lengkap',
                            link: {
                                path: '/web/pembina-mutu/get/{id}',
                                parser: [ { replace: 'id', with: 'id' } ]
                            }
                        },
                        {
                            fieldName: 'Email',
                            objectField: 'email'
                        },
                        {
                            fieldName: 'Instansi',
                            objectField: 'instansi'
                        }
                    ]
                }"
            />
        </div>
    </div>
</template>

<script>
import DynamicTable from '../dynamic/table'
import DynamicModalForm from '../forms/dynamicmodalform'

export default {
    components: {
        DynamicTable,
        DynamicModalForm,
    },
    data: function() {
        return {}
    },
    props: {},
    methods: {},
    mounted() {}
};
</script>
