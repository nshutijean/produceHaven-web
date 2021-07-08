<template>
    <div class="container">
        <div class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto">
            <h2 class="title">Dashboard</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <ul style="list-style-type:none">
                        <li class="active"><button class="btn" @click="setComponent('main')">Dashboard</button></li>
                        <li><button class="btn"  @click="setComponent('orders')">Orders</button></li>
                        <li><button class="btn" @click="setComponent('products')">Products</button></li>
                        <li><button class="btn" @click="setComponent('users')">Users</button></li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <!-- component changes when activeComponent changes -->
                    <component :is="activeComponent"></component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Main from '../admin/Main.vue';
import Users from '../admin/Users';
import Products from '../admin/Products';
import Orders from '../admin/Orders';

export default {
    data() {
        return {
            user: null,
            activeComponent: null
        }
    },
    components: {
        Main, Users, Products, Orders
    },
    beforeMount() {
        this.setComponent(this.$route.params.page);
        this.user = JSON.parse(localStorage.getItem('bigStore.user'));
        axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bigStore.jwt')
    },
    methods: {
        setComponent(value) {
            switch(value) {
                case "users": 
                    this.activeComponent = Users;
                    this.$router.push({name: 'admin-pages', params: {page: 'users'}});
                    break;
                case "orders":
                    this.activeComponent = Orders;
                    this.$router.push({name: 'admin-pages', params: {page: 'orders'}});
                    break;
                case "products":
                    this.activeComponent = Products;
                    this.$router.push({name: 'admin-pages', params: {page: 'products'}});
                    break;
                default:
                    this.activeComponent = Main;
                    this.$router.push({name: 'vendor'});
                    break;

            }
        }
    },
}
</script>

<style scoped>
    .hero-section { background: #38c172; height: 20vh; align-items: left; margin-bottom: 20px; margin-top: 20px; }
.title { font-size: 60px; color: #ffffff; }
@media (max-width: 414px) {
    .title {
        font-size: 32px;
    }
}
</style>