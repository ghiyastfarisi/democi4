<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <DynamicTable
                :enableAdd="{
                    valid: true,
                    displayLink: 'Buat User Baru',
                    useModal: {
                        title: 'Form User Pembina Mutu',
                        submit: 'Submit Data',
                        modalType: 'userform',
                        createUrl: `register`,
                        extra: {}
                    }
                }"
                :tableDep="{
                    searchable: true,
                    ajaxUri: `v1/user/all`,
                    deleteUrl: `v1/user/`,
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

export default {
    created() {
        console.log(UserSession)
    },
    components: {
        DynamicTable
    },
    data: function() {
        return {
            renderObject: (UserSession && UserSession.role === 'pembina_mutu') ? [
                {
                    fieldName: 'Username (Email)',
                    objectField: 'username',
                    link: {
                        path: '/web/user/get/{id}',
                        parser: [ { replace: 'id', with: 'id' } ]
                    }
                },
                {
                    fieldName: 'Status',
                    objectField: 'login_status'
                }
            ] : [
                {
                    fieldName: 'Username (Email)',
                    objectField: 'username',
                    link: {
                        path: '/web/user/get/{id}',
                        parser: [ { replace: 'id', with: 'id' } ]
                    }
                },
                {
                    fieldName: 'Status',
                    objectField: 'login_status'
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
            ]
        }
    }
};
</script>
