<template>
    <div class="ajaxtable">
        <div class="box">
            <div class="mb-4">
                <a href="javascript:void(0)" :class="{ 'is-loading': isLoading }" class="button is-success is-outlined" @click="UpdateTable">
                    <span class="icon">
                        <i class="fas fa-sync"></i>
                    </span>
                </a>
                <a href="javascript:void(0)" class="button is-primary" @click="ToggleModal">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span>
                        Create New User
                    </span>
                </a>
                <DynamicModalForm
                    v-if="showModal"
                    v-bind:modal-dep="{
                        title: 'Create Akun',
                        submit: 'Create',
                        modalType: 'adduserpembinamutu',
                        isEdit: false
                    }"
                    @toggle-close="ToggleModal"
                    @update-table="UpdateTable"
                />
            </div>
            <h2 class="title is-6">Showing {{ show }} of {{ total }} data</h2>
            <div class="table-container">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th><abbr title="number">#</abbr></th>
                            <th v-for="(field, index) in fields" :key="index">{{ field.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, index) in lists" :key="list.id">
                            <th>{{ startOrder + index }}</th>
                            <td><a :href="list.url">{{ list.username }}</a></td>
                            <td>{{ list.login_status }}</td>
                            <td>
                                <a class="button is-small is-primary" href="javascript:void(0)">
                                    <span class="icon">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </a>
                                <a class="button is-small is-danger" @click="openDialog(list.id)" href="javascript:void(0)">
                                    <span class="icon">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav class="pagination" role="navigation" aria-label="pagination">
                <a class="pagination-previous" title="This is the first page" :disabled="noPrev" @click="noPrev ? null : getData(limit, pagination.prev)">Previous</a>
                <a class="pagination-next" :disabled="noNext" @click="noNext ? null : getData(limit, pagination.next)">Next</a>
                <ul class="pagination-list">
                    <li v-for="(list, idx) in pagination.list" :key="idx">
                        <a @click="pagination.current != list ? getData(limit, list) : null" :class="pagination.current == list ? 'is-current' : ''" class="pagination-link">{{ list }}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>

import DynamicModalForm from './forms/dynamicmodalform'
import TableDict from '../lib/tabledictionary'
import Swal from 'sweetalert2'

export default {
    components: {
        DynamicModalForm
    },
    data: function() {
        return {
            fields: [],
            lists: [],
            total: 0,
            show: 0,
            showModal: false,
            showModalEdit: false,
            noNext: false,
            noPrev: false,
            isLoading: false,
            pagination: {},
            limit: 0,
            startOrder: 1
        }
    },
    props: {
        fieldType: String,
        ajaxUri: String,
        showLimit: Number,
    },
    methods: {
        getData(l = 0, p = 1) {
            const q = {
                limit: (l > 0) ? l : this.showLimit,
                page: p
            }
            let qs = ''

            for (const [key, value] of Object.entries(q)) {
                qs += `${key}=${value}&`
            }

            const url = BASE_API_URL + this.ajaxUri + '?' + qs

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data, pagination } = resp
                    this.pagination = pagination
                    this.noNext = (pagination && pagination.next) > 0 ? false : true
                    this.noPrev = (pagination && pagination.prev) > 0 ? false : true
                    this.lists = data.map(d => {
                        d.url = `${BASE_URL}/web/user/get/${d.id}`

                        return d
                    })
                    this.total = pagination.total
                    this.show = data.length
                    this.limit = q.limit
                    this.startOrder = ((pagination.current - 1) * q.limit) + 1
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        ToggleModal() {
            this.showModal = !this.showModal
        },
        resetData() {
            this.noNext = false
            this.noPrev = false
            this.lists = []
            this.total = 0
            this.show = 0
        },
        ToggleLoading() {
            this.isLoading = !this.isLoading
        },
        reFetch() {
            this.getData()
            this.ToggleLoading()
        },
        UpdateTable() {
            this.ToggleLoading()
            setTimeout(this.reFetch, 1000)
        },
        openDialog(id) {
            Swal.fire({
                title: 'Delete data?',
                showCancelButton: true,
                confirmButtonText: `Confirm Delete`,
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.actionDelete(id)
                }
            })
        },
        actionDelete(id) {
            const url = `${BASE_API_URL}v1/user/${id}`

            fetch(url, {
                method: 'DELETE'
            })
            .then(resp => {
                if (!resp.ok) {
                    return resp.json()
                        .then(errResp => {
                            if (errResp && errResp.error && errResp.error.message) {
                                console.log(errResp.error.message)
                            }
                        })
                        .catch(err => {
                            console.error(err)
                        })
                }

                return resp.json()
            })
            .catch(err => {
                console.error(err)
            })

            Swal.fire('User Deleted', '', 'success')

            this.UpdateTable()
        }
    },
    created() {
        let vm = this
        vm.fields = TableDict.TableFieldData[vm.fieldType]
    },
    mounted() {
        let vm = this
        vm.getData()
    }
};
</script>

<style scoped>
.ajaxtable tr {
    padding: 30px;
}
</style>