<template>
    <div>
        <canvas id="products-chart"></canvas>
        <!-- <h3>Yo</h3> -->
    </div>
</template>

<script>
import Chart from 'chart.js'
export default {
    data() {
        return {
            fruits: null,
            veggies: null
        }
    },
    mounted() {
        this.createChart('products-chart');
    },
    methods: {
        async createChart(chartId) {
            await axios.get('/api/fruits').then(response => this.fruits = response.data)
            await axios.get('/api/vegetables').then(response => this.veggies = response.data)

            let f = this.fruits;
            let v = this.veggies;
            // console.log("fruits:" +f);
            const ctx = document.getElementById(chartId);
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    // labels: ['Buyers', 'Vendors'],
                    datasets: [
                        {
                            label: 'Products piechart',
                            data: [f, v],
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