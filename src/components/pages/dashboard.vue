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
                        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                        datasets: [{
                            label: 'Jumlah UPI dibina per bulan',
                            data: [12, 19, 3, 5, 2, 3],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
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
