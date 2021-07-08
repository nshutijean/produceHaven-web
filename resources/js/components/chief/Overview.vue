<template>
    <div class="overview">
        <div class="container-fluid">
            <div class="intro">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-dark mb-4">Overview</h3>
                        <!-- analytics -->
                        <div class="row">
                            <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow border-left-primary py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <div class="text-uppercase text-primary font-weight-bold text-xs mb-1">
                                                    <span>Products</span>
                                                </div>
                                                <div class="text-dark font-weight-bold h5 mb-0">
                                                    <span>{{products !== [] ? products : 0}}</span>
                                                </div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-list fa-2x"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow border-left-success py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <div class="text-uppercase text-success font-weight-bold text-xs mb-1">
                                                    <span>Orders</span>
                                                </div>
                                                <div class="text-dark font-weight-bold h5 mb-0">
                                                    <span>{{orders !== [] ? orders : 0}}</span>
                                                </div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-blue-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow border-left-warning py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <div class="text-uppercase text-warning font-weight-bold text-xs mb-1">
                                                    <span>Buyers</span>
                                                </div>
                                                <div class="text-dark font-weight-bold h5 mb-0">
                                                    <span>{{buyers.length != 0 ? buyers : "0"}}</span>
                                                </div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-user fa-2x text-green-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3 mb-4">
                                <div class="card shadow border-left-warning py-2">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <div class="text-uppercase text-warning font-weight-bold text-xs mb-1">
                                                    <span>Vendors</span>
                                                </div>
                                                <div class="text-dark font-weight-bold h5 mb-0">
                                                    <span>{{vendors.length != 0 ? vendors : "0"}}</span>
                                                </div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-user fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <!-- users chart -->
                <div class="row">
                <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary font-weight-bold m-0">Registered users piechart</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <users-chart></users-chart>
                                    <div class="text-center small mt-4">
                                        <span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Buyers</span>
                                        <span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;Vendors</span>
                                    </div>
                                </div>
                            </div>                            
                    </div>
                </div>
                <!-- orders chart -->
                <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary font-weight-bold m-0">Orders piechart</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <orders-chart style=""></orders-chart>
                                    <div class="text-center small mt-4">
                                        <span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;Ordered</span>
                                        <span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Pending</span>
                                        <span class="mr-2"><i class="fas fa-circle text-dark"></i>&nbsp;Delivered</span>
                                    </div>
                                </div>
                            </div>                            
                    </div>
                </div>
                <!-- products chart -->
                <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary font-weight-bold m-0">Products piechart</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <products-chart></products-chart>
                                    <div class="text-center small mt-4">
                                        <span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Fruits</span>
                                        <span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;Veggies</span>
                                    </div>
                                </div>
                            </div>                            
                    </div>
                </div>
                    <!-- <div class="col-md-4">
                        <img
                            src="../../../img/svg/overview.svg"
                            alt=""
                            class="img-fluid"
                        />
                    </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>// @ts-nocheck

import UsersChart from './usersChart'
import OrdersChart from './OrdersChart'
import ProductsChart from './ProductsChart'
export default {
    components: {
        'users-chart': UsersChart,
        'orders-chart': OrdersChart,
        'products-chart': ProductsChart,
    },
    data() {
        return {
            user: null,
            products: [],
            users: [],
            orders: [],
            buyers: [],
            vendors: []
        }
    },
    mounted() {
        axios.get('/api/products/').then(response => {
            this.products = response.data.total;
            // console.log(response.data.total);
        })
        axios.get('/api/all-users/')
        .then((response) => {
            this.users = response.data
            // console.log(response.data);
        })
        axios.get('/api/all-orders/').then(response => this.orders = response.data.total)
        axios.get('/api/buyers/').then(response => this.buyers = response.data)
        axios.get('/api/vendors/').then(response => this.vendors = response.data)
        
    },
};
</script>

<style lang="sass" scoped>
    @import 'resources/sass/overview.scss'
</style>
