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
                        {{ enableAdd.addDep.title }}
                    </span>
                </a>
                <DynamicModalForm v-bind:modal-dep="enableAdd.addDep" v-if="showModal" @toggle-close="ToggleModal" @update-table="UpdateTable"/>
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
                                <a v-if="enableEdit.valid" @click="openEditModal(list.id)" class="button is-small is-primary mb-1" href="javascript:void(0)">
                                    <span class="icon">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                </a>
                                <a v-if="tableDep.deleteUrl" class="button is-small is-danger mb-1" @click="openDialog(list.id)" href="javascript:void(0)">
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
            <DynamicModalForm
                v-if="enableEdit.valid && showModalEdit"
                v-bind:modal-dep="enableEdit.editDep"
                @toggle-close="showModalEdit=!showModalEdit"
                @update-table="UpdateTable"
            />
        </div>
    </div>
</template>

<script>
const UrlParse = require('url-parse')
const DynamicModalForm = require('./forms/dynamicmodalform').default
const TableDict = require('../lib/tabledictionary')
const Swal = require('sweetalert2')
const { HandleDelete } = require('../lib/form')

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
            showModalEdit: false,
            baseEditFetchUrl: '',
            baseEditSubmitUrl: '',
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
                    addDep: {}
                }
            }
        },
        enableEdit: {
            type: Object,
            default: function () {
                return {
                    valid: false,
                    editDep: {}
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
        openEditModal(id) {
            this.enableEdit.editDep.fetchEditUrl = `${this.baseEditFetchUrl}${id}`
            this.enableEdit.editDep.updateUrl = `${this.baseEditSubmitUrl}${id}`
            this.showModalEdit = !this.showModalEdit
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
        async actionDelete(id) {
            const result = await HandleDelete(`${BASE_API_URL}${this.tableDep.deleteUrl}${id}`)

            if (result.isError) {
                Swal.fire(ParseError(result.message), '', 'error')
            } else {
                Swal.fire(result.message, '', 'success')
            }


            this.UpdateTable()
        }
    },
    created() {
        let vm = this
        const getFields = TableDict.TableFieldData[vm.tableDep.fieldType]

        vm.fields = TableDict.TableFieldData[vm.tableDep.fieldType]
        vm.validFields = getFields.filter(field => !field.decorator)

        this.baseEditFetchUrl = this.enableEdit.editDep.fetchEditUrl
        this.baseEditSubmitUrl = this.enableEdit.editDep.updateUrl
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