<template>
    <div class="container">
        <div class="row">
            <!-- offset-md-3 -->
            <div class="col-md-5 mr-5">
                <div class="order-box">
                    <img :src="product.image" :alt="product.name" class="img-fluid rounded">
                    <h2 class="title" v-html="product.name"></h2>
                    <p class="small-text text-muted float-left">RF {{product.price}}</p>
                    <p class="small-text float-right">Available units: {{product.units}}</p>
                    <br>
                    <hr>

                    <label class="row"><span class="col-md-2 float-left">Quantity: </span><input type="number" name="units" min="1" :max="product.units" class="col-md-2 float-left ml-2" v-model="quantity" @change="checkUnits"></label>
                </div>
                <br>
                
                <div>
                    <div v-if="!isLoggedIn">
                        <h2>You need to login to continue</h2>
                        <button class="col-md-4 btn btn-primary float-left" @click="login">Login</button>
                        <button class="col-md-4 btn btn-danger float-right" @click="register">Create an account</button>
                    </div>
                </div>
            </div>
            <div class="address col-md-5" v-if="isLoggedIn">
                <form>
                    <label class="col-md-4 col-form-label mb-3"><b>Delivery Address:</b></label><br>
                <!-- v-if="isLoggedIn" if needed -->
                <label class="col-md-4 col-form-label" >Street Number:</label>
                <input type="text" id="address" v-model="address" class="col-sm-6 form-control ml-3" placeholder="KN 142 st" required><br>
                <button class="col-md-4 btn btn-sm btn-success float-left ml-3" v-if="isLoggedIn" @click="payViaService">Check out</button>
                
                </form>       
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['pid'],
    data() {
        return {
            // address: [],
            address: "",
            isLoggedIn: null,
            quantity: 1,
            product: [],
            user: null,

            paymentData: {
                tx_ref: this.generateReference(),
                amount: 10,
                currency: 'RWF',
                payment_options: 'card,mobilemoneyrwanda',
                redirect_url: '',
                meta: {
                'counsumer_id': '7898',
                'consumer_mac': 'kjs9s8ss7dd'
                },
                customer: {
                name: 'Hannibal Scott',
                email: 'hannibalscott0@gmail.com',
                phone_number: '0780679487'
                } ,
                customizations: {
                title: 'Customization Title',
                description: 'Customization Description',
                logo: 'https://flutterwave.com/images/logo-colored.svg'
                },
                callback: this.makePaymentCallback,
                onclose: this.closedPaymentModal
        }
        }
    },
    mounted() {
        //checking authentication status
        this.isLoggedIn = localStorage.getItem('bigStore.jwt') != null;
    },
    beforeMount() {
        axios.get(`/api/products/${this.pid}`).then(response => this.product = response.data);
        if(localStorage.getItem('bigStore.jwt') != null) {
            this.user = JSON.parse(localStorage.getItem('bigStore.user'));
            axios.defaults.headers.common['Content-Type'] = 'application/json';
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bigStore.jwt');
        }
    },
    methods: {
        login() {
            this.$router.push({name: 'login', params: {nextUrl: this.$route.fullPath}});
        },
        register() {
            this.$router.push({name: 'register', params: {nextUrl: this.$route.fullPath}});
        },
        placeOrder(e) {
            e.preventDefault();
            let address = this.address;
            let product_id = this.product.id;
            let quantity = this.quantity;
            let user_id = this.user.id;

            axios.post('api/orders/', {user_id,address, quantity, product_id})
                .then(response => this.$router.push('/confirmation'))
                .catch(error=> console.error(error))

        },
        checkUnits(e) {
            if(this.quantity > this.product.units) {
                this.quantity = this.product.units;
            }
        },
        payViaService() {
            this.payWithFlutterwave(this.paymentData) 
        },
        makePaymentCallback(response) {
            console.log("Payment callback", response)
        },
        closedPaymentModal() {
            this.placeOrder(e);
            console.log('payment modal is closed');
        },
        generateReference(){
        let date = new Date()
        return date.getTime().toString();
        }

    },
}
</script>

<style scoped>
    .small-text { font-size: 18px; }
    .order-box { border: 1px solid #cccccc; padding: 10px 15px; }
    .title { font-size: 36px; }
    .address { border: 1px solid #cccccc; margin-bottom: auto;}
    .col-md-5 {  padding-bottom: 15px;  }
</style>