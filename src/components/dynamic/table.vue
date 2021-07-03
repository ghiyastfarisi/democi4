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
            <div v-if="!tableDep.searchable">
                <div class="columns is-multiline mb-2">
                    <div class="column is-3">
                        <div class="control" :class="searchStatus">
                            <input class="input" @input="debounceSearch" required type="text" placeholder="Search...">
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="enableAdd.valid" class="mb-4">
                <a href="javascript:void(0)" :class="{ 'is-loading': isLoading }" class="button is-success is-outlined" @click="UpdateTable">
                    <span class="icon">
                        <i class="fas fa-sync"></i>
                    </span>
                </a>
                <a href="javascript:void(0)" class="button is-primary" @click="AddClick">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span>
                        {{ enableAdd.displayLink }}
                    </span>
                </a>
                <DynamicModalForm
                    v-bind:modal-dep="enableAdd.useModal"
                    v-if="showModal"
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
                            <th v-for="(field, index) in tableDep.renderObject" :key="index">{{ field.fieldName }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list, listIndex) in lists" :key="list.id">
                            <td>{{ startOrder + listIndex }}</td>
                            <td v-for="(field, index) in tableDep.renderObject" :key="index">
                                <template v-if="field.link">
                                    <a :href="list[field.objectField].link">{{ list[field.objectField].value }}</a>
                                </template>
                                <template v-else-if="field.type && field.type === 'setting'">
                                    <span v-for="(fc, fci) in field.content" :key="fci">
                                        <template v-if="fc.useLink">
                                            <a :href="fc.useLink.url" :class="fc.class" class="button is-small mb-1 mr-1">
                                                <span v-if="fc.icon" class="icon">
                                                    <i :class="fc.icon"></i>
                                                </span>
                                                <span v-if="fc.text">{{fc.text}}</span>
                                            </a>
                                        </template>
                                        <template v-else-if="fc.useModal">
                                            <a @click="SettingModal(fc.type, list[fc.useModal.sourceId])" :class="fc.class" class="button is-small mb-1 mr-1" href="javascript:void(0)">
                                                <span v-if="fc.icon" class="icon">
                                                    <i :class="fc.icon"></i>
                                                </span>
                                                <span v-if="fc.text">{{fc.text}}</span>
                                            </a>
                                        </template>
                                        <template v-else></template>
                                    </span>
                                </template>
                                <template v-else>
                                    {{ list[field.objectField] }}
                                </template>
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
                v-if="enableEdit.fetchEditUrl != '' && showModalEdit"
                v-bind:modalDep="enableEdit"
                @toggle-close="showModalEdit=!showModalEdit"
                @update-table="UpdateTable"
            />
        </div>
    </div>
</template>

<script>
import UrlParse from 'url-parse'
import DynamicModalForm from '../forms/dynamicmodalform.vue'
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
            page: 1,
            startOrder: 1,
            showModalEdit: false,
            baseEditFetchUrl: '',
            baseEditSubmitUrl: '',
            searchMessage: null,
            searchTyping: null,
            searchDebounceInfo: null,
            searchStatus: ''
        }
    },
    props: {
        tableDep: {
            type: Object,
            default: function() {
                return {
                    searchable: false,
                    renderObject: [],
                    ajaxUri: '',
                    showLimit: 5
                }
            }
        },
        enableAdd: {
            type: Object,
            default: function() {
                return {
                    valid: false,
                    displayLink: 'Tambah Data Baru',
                    useModal: null,
                    useLink: null
                }
            }
        },
        enableEdit: {
            type: Object,
            default: function() {
                return {
                    fetchEditUrl: '',
                    updateUrl: '',
                    modalType: '',
                    extra: {}
                }
            }
        }
    },
    methods: {
        clearDebounce() {
            clearTimeout(this.searchDebounceInfo)
        },
        debounceSearch(event) {
            this.searchMessage = null
            this.searchTyping = 'You are typing'
            this.clearDebounce()
            this.searchStatus = 'is-loading'
            this.searchDebounceInfo = setTimeout(() => {
                this.searchTyping = null
                this.searchMessage = event.target.value
                this.getData()
            }, 600)
        },
        linkParser(path, parser, list) {
            return parser.map(p => {
                const toreplace = new RegExp(`{${p.replace}}`, "gi")
                const replacewith = list[p.with]

                return `${this.baseUrl}${path.replace(toreplace, replacewith)}`
            })
        },
        mapResult(fields, lists) {
            return lists.map(list => {
                const newobj = {}

                newobj.id = list.id
                fields.forEach(field => {
                    if (field.link) {
                        const [ link ] = this.linkParser(field.link.path, field.link.parser, list)

                        newobj[field.objectField] = {
                            value: field.link.text ? field.link.text : list[field.objectField],
                            link
                        }
                    } else if (field.content) {
                        field.content.map(c => {
                            if (c.useLink) {
                                const [ link ] = this.linkParser(c.useLink.path, c.useLink.parser, list)

                                c.useLink.url = link
                            }

                            return c
                        })
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

            this.clearDebounce()
            this.searchStatus = ''

            if (this.searchMessage) {
                url.query.keyword = this.searchMessage
            }

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
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        },
        ToggleModal() {
            this.showModal = !this.showModal
        },
        SettingModal(type, sourceId) {
            if (type === 'edit') {
                return this.openEditModal(sourceId)
            } else if (type === 'delete') {
                return this.openDialog(sourceId)
            }
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
            this.enableEdit.fetchEditUrl = `${this.baseEditFetchUrl}${id}`
            this.enableEdit.updateUrl = `${this.baseEditSubmitUrl}${id}`
            this.showModalEdit = !this.showModalEdit
        },
        AddClick() {
            if (this.enableAdd.valid && this.enableAdd.useModal) {
                return this.ToggleModal()
            } else if (this.enableAdd.valid && this.enableAdd.useLink) {
                const rdl = this.enableAdd.useLink.url ? this.enableAdd.useLink.url : BASE_URL;

                return window.location.replace(rdl)
            }
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
        this.baseEditFetchUrl = this.enableEdit.fetchEditUrl
        this.baseEditSubmitUrl = this.enableEdit.updateUrl
    },
    mounted() {
        this.getData()
    }
}
</script>