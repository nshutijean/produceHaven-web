<template>
    <div>
        <div class="container">
            <div class="row" id="prod-detail">
                <div class="col-sm-12 col-md-6 prod-img">
                    <img :src="product.image" :alt="product.name" class="img-fluid" id="img-manip" loading="lazy">
                </div>
                <div class="col-sm-12 col-md-6" id="right">
                    <div class="row" id="right-up">
                        <div class="col">
                            <h1>{{ product.name }}</h1>
                            <p>{{ product.description }}<br></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="right-down">
                        <div class="col mt-3">
                            <h1>{{ product.price * quantity }} RF/</h1>
                            <!-- <p class="mt-3 font-weight-bold">{{ product.units }} items remaining</p> -->
                            <div id="qty">
                                <p class="font-weight-light">Quantity:</p>
                                <input name="units" min="1" :max="product.units" v-model="quantity" @change="checkUnits" type="number">
                            </div>
                            <div v-if="!isLoggedIn">
                                <div id="buttons" class="d-flex justify-content-between">
                                    <button @click="login()" class="btn btn-primary signin" type="button">Sign in</button>
                                    <button @click="register()" class="btn btn-primary new-account" type="button">Create account</button>
                                </div>
                            </div>
                            <div v-if="isLoggedIn">
                                <div class="d-flex align-items-center" id="location">
                                    <p class="font-weight-light">Location:</p>
                                    <input type="text" placeholder="KN 142 st." required>
                                </div>
                                <div id="buttons1">
                                    <button  @click="placeOrder" class="btn btn-primary pay-now" type="button">
                                        Pay Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            p_price: null,
        }
    },
    mounted() {
        //checking authentication status
        this.isLoggedIn = localStorage.getItem('bigStore.jwt') != null;
        // console.log(this.p_price)
    },
    beforeMount() {
        axios.get(`/api/products/${this.pid}`).then(response => {
            this.product = response.data;
            this.p_price = this.product.price * this.quantity;
            console.log(this.p_price)
            
        });
        if(localStorage.getItem('bigStore.jwt') != null) {
            this.user = JSON.parse(localStorage.getItem('bigStore.user'));
            axios.defaults.headers.common['Content-Type'] = 'application/json';
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bigStore.jwt');
        }
        // console.log(this.user)
    },
    computed: {
      totalPrice: function() {
        let total = 0; 
        for(let p of this.product) {
            total += p.price * this.quantity;
        }
        return total.toFixed();
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
            //confirm with flutterwave first
            this.payViaService()
            e.preventDefault();
            let address = this.address;
            let product_id = this.product.id;
            let quantity = this.quantity;
            let user_id = this.user.id;

            axios.post('api/orders/', {user_id,address, quantity, product_id})
                .then(response => console.log(response.data))
                .catch(error=> console.error(error))

            //Empty cart
            // this.products.splice(0, this.products.length); 
            // this.$store.state.cartCount = 0;
        },
        checkUnits(e) {
            if(this.quantity > this.product.units) {
                this.quantity = this.product.units;
            }
        },
        payViaService() {
            this.payWithFlutterwave({
                tx_ref: this.generateReference(),
                amount: this.product.price * this.quantity,
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
                        id: this.user.subAccID,
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
            // this.$router.push('/confirmation');
            console.log('payment modal is closed');
        },
        generateReference(){
            let date = new Date()
            return date.getTime().toString();
        },
        // getPrice() {
        //     return this.p_price;
        // }

    },
}
</script>

<style scoped>

#buttons1 {
  margin-top: 35px;
}

@media (max-width: 796px) {
  #right-down .pay-now {
    font-size: 20px;
  }
}

#buttons1 .pay-now {
  background: white;
  color: black;
  font-weight: 700;
  font-size: 25px;
  border: 3px solid black;
  /* float: right; */
  width: 100%;
  /* margin-top: 20px; */
}

#location p {
  /*padding: 30px;*/
  padding-top: 15px;
  /* margin-right: 10px; */
}

#location {
    margin-top: 20px;
}

#location input {
  border-radius: 3px;
  color: black;
  border: 2px solid black;
  outline: none;
  text-indent: 15px;
  margin-left: 10px;
}
#right-down #qty input {
  margin-left: 10px;
  margin-bottom: 16px;
  border-radius: 3px;
  color: black;
  border: 2px solid black;
  outline: none;
  text-indent: 15px;
  padding-left: 10px;
}

#prod-detail {
  margin-top: 100px;
}

#img-manip {
  border-radius: 8px;
  height: 100%;
}

.prod-img img {
  object-fit: cover;
  width: 100%;
  /*height: 346px;*/
}

@media (max-width: 992px) {
  .prod-img img {
    width: 100%;
    object-fit: cover;
    height: 377px;
  }
}

@media (max-width: 1024px) {
  .prod-img img {
    width: 100%;
    object-fit: cover;
    height: 377px;
  }
}

@media (max-width: 796px) {
  .prod-img img {
    width: 100%;
    object-fit: cover;
    height: 400px;
  }
}

@media (max-width: 414px) {
  #right-up {
    text-align: center;
  }
}

@media (max-width: 576px) {
  #right-up {
    text-align: center;
  }
}

#right-up h1 {
  color: black;
  font-weight: 700;
  margin-top: -4px;
}

#right-up p {
  font-size: 21px;
}

@media (max-width: 414px) {
  #right-down {
    text-align: center;
  }
}

@media (max-width: 576px) {
  #right-down h1, p {
    text-align: center;
  }
}

#right-down h1 {
  color: black;
  font-family: 'Playfair Display';
  font-weight: 600;
  opacity: .5;
}

#right-down p {
  font-size: 21px;
}

#right-down .signin {
  background: white;
  color: black;
  max-width: 128px;
  font-weight: 700;
  font-size: 25px;
  border: 3px solid black;
  float: left;
}

@media (max-width: 796px) {
  #right-down .signin {
    font-size: 20px;
  }
}

#right-down .new-account {
  background: white;
  color: black;
  /* max-width: 241px; */
  font-weight: 700;
  font-size: 25px;
  border: 3px solid black;
  /* float: right; */
}

/* @media (min-width: 300px) {
    #right-down .new-account {
        width: 100%;
    }
} */

@media (max-width: 796px) {
  #right-down .new-account {
    font-size: 20px;
  }
}

#buttons {
  margin-top: 35px;
}

hr {
  border: 1.5px solid;
  margin-right: 20px;
}

@media (max-width: 796px) {
  hr {
    margin-top: 5px;
    margin-bottom: 15px;
  }
}

@media (max-width: 992px) {
  hr {
    margin-top: 20px;
    margin-bottom: 10px;
  }
}

@media (max-width: 411px) {
  hr {
    margin-left: 20px;
  }
}

#right {
  padding-left: 38px;
}

@media (max-width: 576px) {
  #right {
    padding-left: 12px;
    margin-top: 46px;
  }
}

#qty, #location {
  display: flex;
  margin-bottom: -20px;
  padding: 0 !important;
  justify-content: center;
  margin-top: 15px;
  /* margin-left: 30px; */
}

@media (min-width: 768px) {
    #qty, #location {
        justify-content: start;
    }
}

</style>