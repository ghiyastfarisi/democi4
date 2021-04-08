<template>
    <div class="ajaxtable">
        <div class="box">
            <div v-if="enableAdd.valid" class="mb-4">
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
                        {{ enableAdd.title }}
                    </span>
                </a>
                <DynamicModalForm v-bind:modal-dep="modalDep" v-if="showModal" @toggle-close="ToggleModal" @update-table="UpdateTable"/>
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
                        <tr v-for="(list, listIndex) in lists" :key="list.id">
                            <th>{{ startOrder + listIndex }}</th>
                            <td v-for="(field, index) in validFields" :key="index">
                                {{ list[field.origin] }}
                            </td>
                            <td>
                                <a v-if="enableEdit.valid" class="button is-small is-primary" href="javascript:void(0)">
                                    <span class="icon">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                </a>
                                <a v-if="tableDep.deleteUrl" class="button is-small is-danger" @click="openDialog(list.id)" href="javascript:void(0)">
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
const UrlParse = require('url-parse')
const DynamicModalForm = require('./forms/dynamicmodalform').default
const TableDict = require('../lib/tabledictionary')
const Swal = require('sweetalert2')

module.exports = {
    components: {
        DynamicModalForm
    },
    data: function() {
        return {
            fields: [],
            validFields: [],
            lists: [],
            total: 0,
            show: 0,
            showModal: false,
            noNext: false,
            noPrev: false,
            isLoading: false,
            pagination: {},
            limit: 0,
            startOrder: 1,
            modalDep: {
                title: this.enableAdd.modalTitle,
                submit: 'Tambah',
                modalType: this.enableAdd.modalType,
                isEdit: false,
                fetchEditUrl: '',
                updateUrl: '',
                extra: this.enableAdd.extra,
            }
        }
    },
    props: {
        tableDep: {
            fieldType: String,
            ajaxUri: String,
            showLimit: Number,
            deleteUrl: String,
            enableEdit: {
                valid: true
            }
        },
        enableAdd: {
            type: Object,
            default: function () {
                return {
                    valid: false,
                    title: 'Tambah...',
                    modalTitle: 'Judul',
                    modalType: '',
                    extra: {
                        pembinaMutuId: 0
                    }
                }
            }
        },
        enableEdit: {
            type: Object,
            default: function () {
                return {
                    valid: false,
                    title: 'Edit',
                    modalTitle: 'Edit',
                    modalType: 'editriwayatpendidikan',
                    extra: {}
                }
            }
        }
    },
    methods: {
        mapResult(fields, lists) {
            return lists.map(list => {
                const newobj = {}

                newobj.id = list.id
                fields.forEach(field => {
                    if (!field.decorator) {
                        newobj[field.origin] = list[field.origin]
                    }
                })

                return newobj
            })
        },
        getData(l = 0, p = 1) {
            const q = {
                limit: (l > 0) ? l : this.tableDep.showLimit,
                page: p
            }

            const url = new UrlParse(`${BASE_API_URL}${this.tableDep.ajaxUri}`, true)

            url.query.limit = q.limit
            url.query.page = q.page

            fetch(url.toString())
                .then(stream => stream.json())
                .then(resp => {
                    const { data, pagination } = resp
                    this.pagination = pagination
                    this.noNext = (pagination && pagination.next) > 0 ? false : true
                    this.noPrev = (pagination && pagination.prev) > 0 ? false : true
                    this.lists = this.mapResult(this.fields, data)
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
            const url = `${BASE_API_URL}${this.tableDep.deleteUrl}${id}`

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

        const getFields = TableDict.TableFieldData[vm.tableDep.fieldType]

        vm.fields = TableDict.TableFieldData[vm.tableDep.fieldType]
        vm.validFields = getFields.filter(field => !field.decorator)
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