<template>
    <div>
        <canvas id="orders-chart"></canvas>
        <!-- <h3>Yo</h3> -->
    </div>
</template>

<script>
import Chart from 'chart.js'
export default {
    data() {
        return {
            orders: null,
            delivers: null,
            pendings: null,
        }
    },
    mounted() {
        this.createChart('orders-chart');
    },
    methods: {
        async createChart(chartId) {
            await axios.get('/api/all-orders/').then(response => this.orders = response.data)
            await axios.get('/api/delivers/').then(response => this.delivers = response.data)
            await axios.get('/api/pendings/').then(response => this.pendings = response.data)

            let o = this.orders;
            let d = this.delivers;
            let p = this.pendings;
            // console.log("buyers:" +b);
            const ctx = document.getElementById(chartId);
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    // labels: ['Buyers', 'Vendors'],
                    datasets: [
                        {
                            label: 'Orders piechart',
                            data: [o, d, p],
                            backgroundColor: [
                                'blue',
                                '#5cb85c',
                                '#292b2c'
                            ],
                            borderColor: [
                                '#36495d',
                            ],
                            borderWidth: 3
                        }
                    ],
                },
                options: {
                    responsive: true
                }
            })
        }
    },
}
</script>