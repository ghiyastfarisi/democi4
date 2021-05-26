<template>
    <div>
        <div class="box" v-cloak v-if="showData">
            <div class="block">
                <nav class="level">
                    <div class="level-left">
                        <p class="title is-4 is-spaced">Data Kapasitas Sarana dan Prasarana</p>
                    </div>
                </nav>
                <div class="field">
                    <div class="control">
                        <table class="table is-fullwidth is-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Unit</th>
                                    <th>Kapasitas</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l, i) in list" :key="i">
                                    <td>{{ l.name }}</td>
                                    <td>{{ l.nilai_unit }}</td>
                                    <td>{{ l.nilai_kapasitas !== null ? numberWithCommas(l.nilai_kapasitas) : 0 }}</td>
                                    <td>Kg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    created() {},
    components: {},
    data: function() {
        return {
            showError: false,
            showData: false,
            list: {}
        }
    },
    props: {
        blockDep: {
            type: Object,
            default: function () {
                return {
                    ajaxUri: ''
                }
            }
        }
    },
    methods: {
        numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        getData() {
            const url = BASE_API_URL + this.blockDep.ajaxUri

            fetch(url)
                .then(stream => stream.json())
                .then(resp => {
                    const { data } = resp

                    if (data && data !== null) {
                        this.showData = true
                        this.list = data
                    } else {
                        this.showError = true
                    }

                    return true
                })
                .catch(err => {
                    console.error('Err while FetchAjax:', err)
                    console.error(err)
                })
        }
    },
    mounted() {
        let vm = this
        vm.getData()
    }
};
</script>