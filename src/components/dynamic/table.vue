<template>
    <div class="ajaxtable">
        <div class="has-text-centered" v-if="showLoading">
            <span class="icon is-large">
                <i class="fas fa-2x fa-cog fa-spin"></i>
            </span>
            <span class="title is-3">Loading Data</span>
        </div>
        <div class="box notification is-danger" v-if="showError">
            <span class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <span>
                Belum ada data tersimpan
            </span>
        </div>
        <div class="box">
            <!-- <div v-if="enableAdd.valid" class="mb-4">
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
            </div> -->
            <h2 class="title is-6">Showing {{ show }} of {{ total }} data</h2>
            <div class="table-container">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th><abbr title="number">#</abbr></th>
                            <th v-for="(field, index) in tableDep.renderObject" :key="index">{{ field.fieldName }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, listIndex) in lists" :key="list.id">
                            <th>{{ startOrder + listIndex }}</th>
                            <td v-for="(field, index) in tableDep.renderObject" :key="index">
                                <span v-if="field.link">
                                    <a :href="list[field.objectField].link">{{ list[field.objectField].value }}</a>
                                </span>
                                <span v-else>
                                    {{ list[field.objectField] }}
                                </span>
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
            <!-- <DynamicModalForm
                v-if="enableEdit.valid && showModalEdit"
                v-bind:modal-dep="enableEdit.editDep"
                @toggle-close="showModalEdit=!showModalEdit"
                @update-table="UpdateTable"
            /> -->
        </div>
    </div>
</template>

<script>
import UrlParse from 'url-parse'
import DynamicModalForm from '../forms/dynamicmodalform.vue'
// import TableDict from '../lib/tabledictionary'
import Swal from 'sweetalert2'
import { HandleDelete, ParseError } from '../../lib/form'

export default {
    components: {
        DynamicModalForm
    },
    data: function() {
        return {
            baseUrl: BASE_URL,
            showLoading: true,
            showError: false,
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
            renderObject: Array,
            ajaxUri: String,
            showLimit: Number,
            // fieldType: String,
            // deleteUrl: String,
            // detailUrl: String,
            // enableEdit: {
            //     valid: true
            // }
        },
    },
    methods: {
        mapResult(fields, lists) {
            return lists.map(list => {
                const newobj = {}

                newobj.id = list.id
                fields.forEach(field => {
                    if (field.link) {
                        let linkPath = field.link.path

                        field.link.parser.forEach(parser => {
                            const toreplace = new RegExp(`{${parser.replace}}`, "gi")
                            const replacewith = list[parser.with]

                            linkPath = linkPath.replace(toreplace, replacewith)
                        })
                        newobj[field.objectField] = {
                            value: list[field.objectField],
                            link: `${this.baseUrl}${linkPath}`
                        }
                    } else {
                        newobj[field.objectField] = list[field.objectField]
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
                    this.lists = this.mapResult(this.tableDep.renderObject, data)
                    this.total = pagination.total
                    this.show = data.length
                    this.limit = q.limit
                    this.startOrder = ((pagination.current - 1) * q.limit) + 1
                    this.showLoading = false
                    if (pagination.total > 0) {
                        this.showError = false
                    } else {
                        this.showError = true
                    }
                    console.log(this.lists)
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
            // this.enableEdit.editDep.fetchEditUrl = `${this.baseEditFetchUrl}${id}`
            // this.enableEdit.editDep.updateUrl = `${this.baseEditSubmitUrl}${id}`
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
    mounted() {
        let vm = this
        vm.getData()
    }
}
</script>