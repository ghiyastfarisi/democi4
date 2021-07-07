<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enableAdd="{
                    valid: true,
                    displayLink: 'Buat UPI Baru',
                    useLink: {
                        url: addUrl
                    }
                }"
                :tableDep="{
                    searchable: true,
                    ajaxUri: `v1/upi/all?getLocationName=true`,
                    showLimit: 25,
                    renderObject
                }"
            />
        </div>
    </div>
</template>

<script>
import DynamicTable from '../dynamic/table'
import { UserSession } from '../../lib/auth'

console.log(UserSession)

export default {
    components: {
        DynamicTable,
    },
    data: function() {
        return {
            addUrl: `${BASE_URL}/web/upi/create`,
            renderObject: (UserSession.role === 'admin') ? [
                {
                    fieldName: 'Nama',
                    objectField: 'nama_perusahaan',
                    link: {
                        path: '/web/upi/get/{id}',
                        parser: [ { replace: 'id', with: 'id' } ]
                    }
                },
                {
                    fieldName: 'NIB',
                    objectField: 'nib'
                },
                {
                    fieldName: 'Kecamatan',
                    objectField: 'location_district_name'
                },
                {
                    fieldName: 'Kota Kabupaten',
                    objectField: 'location_regency_name'
                },
                {
                    fieldName: 'Permintaan Update',
                    objectField: 'total_request_update'
                },
                {
                    fieldName: 'Setting',
                    type: 'setting',
                    content: [
                        {
                            icon: 'fas fa-times',
                            class: 'is-danger',
                            type: 'delete',
                            useModal: {
                                sourceId: 'id'
                            },
                        }
                    ]
                }
            ] : [
                {
                    fieldName: 'Nama',
                    objectField: 'nama_perusahaan',
                    link: {
                        path: '/web/upi/get/{id}',
                        parser: [ { replace: 'id', with: 'id' } ]
                    }
                },
                {
                    fieldName: 'NIB',
                    objectField: 'nib'
                },
                {
                    fieldName: 'Kecamatan',
                    objectField: 'location_district_name'
                },
                {
                    fieldName: 'Kota Kabupaten',
                    objectField: 'location_regency_name'
                },
                {
                    fieldName: 'Permintaan Update',
                    objectField: 'total_request_update'
                }
            ]
        }
    }
};
</script>
