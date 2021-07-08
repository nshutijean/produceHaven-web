<template>
    <div class="col-md-6 mx-auto mt-4">
        <h5>Product to checkout</h5>
        <div v-if="$store.state.cart.length > 0">
            <p v-for="product in $store.state.cart" :key="product.id">
               {{product.name}} | {{product.quantity}} unit(s) = RF {{product.totalPrice}}
               <span class="float-right" title="Remove from cart" @click.prevent="removeFromCart(product)" aria-hidden="true" style="cursor:pointer">&times;</span>
            </p>
            <p>Total: RF {{totalPrice}}</p>
         </div>
         <div>
            <div v-if="!isLoggedIn">
               <h2>You need to login to continue</h2>
               <button class="col-md-4 btn btn-primary float-left" @click="login">Login</button>
               <button class="col-md-4 btn btn-danger float-right" @click="register">Create an account</button>
            </div>
         </div>
         <div class="address col-md-5" v-if="isLoggedIn">
            <form>
               <label class=" col-form-label mb-3"><b>Delivery Address:</b></label><br>
            <!-- v-if="isLoggedIn" if needed -->
            <label class=" col-form-label" >Street Number (<small>Be specific</small>):</label>
            <input type="text" id="address" v-model="address" class="col-sm-6 form-control ml-3" placeholder="KN 142 st" required><br>
            <button class="col-md-6 btn btn-sm btn-success float-left ml-3" v-if="isLoggedIn" @click="placeOrder">Pay Now</button>
            
            </form>       
         </div> 
    </div>
</template>

<script>
export default {
   data() {
      return {
         isLoggedIn: null,
         product: [],
         user: null,
         address: "",
         products: this.$store.state.cart,
         total: 0,
      }
   },

   mounted() {
      this.isLoggedIn = localStorage.getItem('bigStore.jwt') != null;
      console.log(this.user);
   },
   beforeMount() {
      // axios.get(`/api/products/${this.pid}`).then(response => {
      //       this.product = response.data;
      //       this.p_price = this.product.price;
            
      // });
      if(localStorage.getItem('bigStore.jwt') != null) {
         this.user = JSON.parse(localStorage.getItem('bigStore.user'));
         axios.defaults.headers.common['Content-Type'] = 'application/json';
         axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bigStore.jwt');
      }
   },
   computed: {
      totalPrice: function() {
         // let total = 0;
         for(let product of this.products) {
            this.total += product.totalPrice;
         }
         return this.total.toFixed();
      }
   },
   methods: {
      removeFromCart: function(product) {
         this.$store.commit('removeFromCart', product);
      },
      login() {
            this.$router.push({name: 'login', params: {nextUrl: this.$route.fullPath}});
      },
      register() {
         this.$router.push({name: 'register', params: {nextUrl: this.$route.fullPath}});
      },
      placeOrder(e) {
         e.preventDefault();
         this.payViaService();
         for(let product of this.products) {
            let address = this.address;
            let product_id = product.id;
            let quantity = product.units;
            let user_id = product.user_id;

            axios.post('api/orders/', {user_id,address, quantity, product_id})
               .then(response => this.$router.push('/confirmation'))
               .catch(error=> console.error(error))
         }

         //Empty cart
         this.products.splice(0, this.products.length);
         this.$store.state.cartCount = 0;

      },
      payViaService() {
            this.payWithFlutterwave({
                tx_ref: this.generateReference(),
                amount: this.totalPrice,
                currency: 'RWF',
                payment_options: 'card,mobilemoneyrwanda',
                redirect_url: '',
                meta: {
                'counsumer_id': `${this.user.id}_${Date.now()}_phe`,
                'consumer_mac': 'kjs9s8ss7dd'
                },
                customer: {
                name: this.user.name,
                // email: this.user.email,
                email: 'hannibalscott0@gmail.com',
                phone_number: this.user.phoneNumber
                },
                subaccounts: [
                    {
                        id: "RS_4D9EDA58015523DFFDD186FD4C408A33",
                        transaction_charge_type: 'percentage',
                        transaction_charge: 0.005
                    }
                ],
                customizations: {
                title: 'PHe purchase',
                description: 'Thank you for choosing PHe',
                logo: 'https://flutterwave.com/images/logo-colored.svg'
                },
                callback: this.makePaymentCallback,
                onclose: this.closedPaymentModal
            }) 
      },
      makePaymentCallback(response) {
         console.log("Payment callback", response)
         this.$router.push('/confirmation');
      },
      closedPaymentModal() {
         console.log('payment modal is closed');
      },
      generateReference(){
         let date = new Date()
         return date.getTime().toString();
      },
      makeTotal() {
         return this.totalPrice;
      }
   },
}
</script>