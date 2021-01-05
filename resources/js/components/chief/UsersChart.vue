<template>
    <div>
        <canvas id="users-chart"></canvas>
        <!-- <h3>Yo</h3> -->
    </div>
</template>

<script>
import Chart from 'chart.js'
export default {
    data() {
        return {
            vendors: null,
            buyers: null
        }
    },
    mounted() {
        this.createChart('users-chart');
    },
    methods: {
        async createChart(chartId) {
            await axios.get('/api/buyers/').then((response) => {this.buyers = response.data;})
            await axios.get('/api/vendors/').then((response) =>{this.vendors = response.data;})

            let b = this.buyers;
            let v = this.vendors;
            // console.log("buyers:" +b);
            const ctx = document.getElementById(chartId);
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    // labels: ['Buyers', 'Vendors'],
                    datasets: [
                        {
                            label: 'Users piechart',
                            data: [b, v],
                            backgroundColor: [
                                'blue',
                                '#5cb85c' 
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