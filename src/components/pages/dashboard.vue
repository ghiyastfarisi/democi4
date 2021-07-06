<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="box">
                Welcome dashboard
            </div>
        </div>
        <div class="column is-12">
            <div class="box">
                <DashboardBarChart
                    :styles="{
                        height: '330px',
                        position: 'relative'
                    }"
                    :options="{
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }"
                    :chartdata="{
                        labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                        datasets: [{
                            label: 'Jumlah UPI dibina',
                            data: [0,0,0,0,0,0,0,0,0,0,0,0],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    }"
                />
            </div>
        </div>
    </div>
</template>

<script>
import DashboardBarChart from '../chart/dashboardbar'

export default {
    components: {
        DashboardBarChart
    },
    data: function() {
        return {}
    },
    props: {},
    methods: {}
};
</script>
