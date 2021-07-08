<template>
    <div>
        <div class="card">
    <div class="row">
        <div class="col-md-8 cart">
            <div class="title">
                <div class="row">
                    <div class="col">
                        <h4><b>Shopping Cart</b></h4>
                    </div>
                    <div class="col align-self-center text-right text-muted">{{ $store.state.cartCount }} items</div>
                </div>
            </div>
            <div v-if="$store.state.cart.length > 0" class="row border-top border-bottom">
                <div v-for="product in $store.state.cart" :key="product.id" class="row main align-items-center">
                    <div class="col-2"><img class="img-fluid" :src="product.image" :alt="product.name"></div>
                    <div class="col">
                        <div class="row text-muted">{{ product.category }}</div>
                        <div class="row">{{ product.name }}</div>
                    </div>
                    <div class="col"> 
                        <a href="#" class="border">{{ product.quantity }}</a>
                    </div>
                    <div class="col">RF {{ product.totalPrice }}
                        <!-- <span class="close">&#10005;</span> -->
                    </div>
                </div>
            </div>
            <div @click="goToCart()" class="back-to-shop">
                <a href="#">&leftarrow;</a>
                <span class="text-muted">Back to the cart</span>
            </div>
        </div>
        <div class="col-md-4 summary">
            <div>
                <h5><b>Summary</b></h5>
            </div>
            <hr>
            <div class="row">
                <div class="col" style="padding-left:0;">ITEMS: <b>{{ $store.state.cartCount }}</b></div>
                <div class="col text-right">RF {{ totalPrice }}</div>
            </div>
            <form>
                <label for="code">LOCATION (eg. KN 142 st.)</label>
                <input id="code" v-model="address" placeholder="Enter your location" required>
            </form>
            <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                <div class="col">TOTAL PRICE</div>
                <div class="col text-right">RF {{ totalPrice }}</div>
            </div> 
            <button v-if="isLoggedIn" @click="placeOrder" class="btn">PAY NOW</button>
        </div>
    </div>
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
       goToCart() {
           this.$router.push('/cart');
       },
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

<style scoped>
body {
    background: #ddd;
    min-height: 100vh;
    vertical-align: middle;
    display: flex;
    font-family: sans-serif;
    font-size: 0.8rem;
    font-weight: bold
}

.title {
    margin-bottom: 5vh
}

.card {
    margin: auto;
    max-width: 950px;
    width: 90%;
    box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    border-radius: 1rem;
    border: transparent;
    margin-top: 40px;
}

@media(max-width:767px) {
    .card {
        margin: 3vh auto
    }
}

.cart {
    background-color: #fff;
    padding: 4vh 5vh;
    border-bottom-left-radius: 1rem;
    border-top-left-radius: 1rem
}

@media(max-width:767px) {
    .cart {
        padding: 4vh;
        border-bottom-left-radius: unset;
        border-top-right-radius: 1rem
    }
}

.summary {
    background-color: #ddd;
    border-top-right-radius: 1rem;
    border-bottom-right-radius: 1rem;
    padding: 4vh;
    color: rgb(65, 65, 65)
}

@media(max-width:767px) {
    .summary {
        border-top-right-radius: unset;
        border-bottom-left-radius: 1rem
    }
}

.summary .col-2 {
    padding: 0
}

.summary .col-10 {
    padding: 0
}

.row {
    margin: 0
}

.title b {
    font-size: 1.5rem
}

.main {
    margin: 0;
    padding: 2vh 0;
    width: 100%
}

.col-2,
.col {
    padding: 0 1vh
}

a {
    padding: 0 1vh
}

.close {
    margin-left: auto;
    font-size: 0.7rem
}

img {
    width: 3.5rem
}

.back-to-shop {
    margin-top: 4.5rem;
    cursor: pointer;
}

h5 {
    margin-top: 4vh
}

hr {
    margin-top: 1.25rem
}

form {
    padding: 2vh 0;
    margin-top: 20px;
}

select {
    border: 1px solid rgba(0, 0, 0, 0.137);
    padding: 1.5vh 1vh;
    margin-bottom: 4vh;
    outline: none;
    width: 100%;
    background-color: rgb(247, 247, 247)
}

input {
    border: 1px solid rgba(0, 0, 0, 0.137);
    padding: 1vh;
    margin-bottom: 4vh;
    outline: none;
    width: 100%;
    background-color: rgb(247, 247, 247)
}

input:focus::-webkit-input-placeholder {
    color: transparent
}

.btn {
    background-color: #000;
    border-color: #000;
    color: white;
    width: 100%;
    font-size: 0.7rem;
    margin-top: 4vh;
    padding: 1vh;
    border-radius: 0
}

.btn:focus {
    box-shadow: none;
    outline: none;
    box-shadow: none;
    color: white;
    -webkit-box-shadow: none;
    -webkit-user-select: none;
    transition: none
}

.btn:hover {
    color: white
}

a {
    color: black
}

a:hover {
    color: black;
    text-decoration: none
}

#code {
    background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253), rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
    background-repeat: no-repeat;
    background-position-x: 95%;
    background-position-y: center
}
</style>