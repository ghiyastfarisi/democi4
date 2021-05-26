<template>
    <div>
        <div class="box" v-cloak v-if="showData">
            <div class="block">
                <nav class="level">
                    <div class="level-left">
                        <p class="title is-4 is-spaced">Data Tenaga Kerja</p>
                    </div>
                </nav>
                <div class="field">
                    <label class="label"> Karyawan Tetap </label>
                    <div class="control">
                        {{ list.karyawan_tetap_p }} Perempuan
                    </div>
                    <div class="control">
                        {{ list.karyawan_tetap_l }} Laki-laki
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Karyawan Harian </label>
                    <div class="control">
                        {{ list.karyawan_harian_p }} Perempuan
                    </div>
                    <div class="control">
                        {{ list.karyawan_harian_l }} Laki-laki
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Jumlah Hari Kerja per Bulan </label>
                    <div class="control">
                        {{ list.hari_kerja_bulan }} Hari
                    </div>
                </div>
                <div class="field">
                    <label class="label"> Jumlah Shift per Hari </label>
                    <div class="control">
                        {{ list.shift_kerja_hari }} Shift
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