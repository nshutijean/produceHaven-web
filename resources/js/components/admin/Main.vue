<template>
    <div>
        <div class="row" style="font-family: 'Playfair Display', serif;">
            <div class="col-md-4 product-box d-flex align-content-center justify-content-center flex-wrap">
                <a class="big-text" href="/vendor/orders">Orders ({{newOrders.length}})</a>
                <p class="text-center font-weight-bold small" style="font-family: Nunito, sans-serif;">Orders that are supposed to be delivered</p>
            </div>
            <hr>
            <div class="col-md-4 product-box d-flex align-content-center justify-content-center flex-wrap">
                <a class="big-text" href="/vendor/products">Products ({{products.length}})</a>
                <p class="text-center font-weight-bold small" style="font-family: Nunito, sans-serif;">Products uploaded by me to the store</p>
            </div>
            <div class="col-md-4 product-box d-flex align-content-center justify-content-center flex-wrap">
                <a class="big-text" href="/vendor/users">Users ({{users.length}})</a>
                <p class="text-center font-weight-bold small" style="font-family: Nunito, sans-serif;">Users who made the orders</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
            orders: [],
            newOrders: [],
            products: [],
            users: [],
            vendorUser: null
        }
    },
    beforeMount() {
        this.vendorUser = JSON.parse(localStorage.getItem('bigStore.user'));
    },
    mounted() {
        axios.get('/api/users/').then(response => this.users = response.data)
        axios.get('/api/showWithAuth/').then(response => this.products = response.data)
        axios.get('/api/orders/').then(response => {
            this.orders = response.data;
            this.orders.forEach(order => {
                //orders from this specific vendor
                if(this.vendorUser.id == order.product.user_id) {
                    return this.newOrders = this.orders
                }
            });

        })
        // console.log(user); 
    },
}
</script>

<style scoped>
    .big-text { font-size: 28px; }
    .product-box { border: 1px solid #cccccc; padding: 10px 15px; height: 22vh }
    a {
        color: #38c172;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>