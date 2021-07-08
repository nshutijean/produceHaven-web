<template>
    <div>
        <div class="card">
        <div class="row">
            <div class="col-md-12 cart">
                <div class="title">
                    <div class="row">
                        <div class="col">
                            <h4><b>Shopping Cart</b></h4>
                        </div>
                        <div class="col align-self-center text-right text-muted"> {{ $store.state.cartCount }} items </div>
                    </div>
                </div>
                <div v-if="$store.state.cart.length > 0" class="row border-top border-bottom">
                    <div v-for="product in $store.state.cart" :key="product.id" class="row main align-items-center justify-content-center">
                        <div class="col-2">
                            <img class="img-fluid" :src="product.image" :alt="product.name">
                        </div>
                        <div class="col">
                            <div class="row text-muted">{{ product.category }}</div>
                            <div class="row"> {{ product.name }} </div>
                        </div>
                        <div id="qty" class="col">  
                            <a href="#" @click.prevent="deductFromCart(product)">-</a>
                            <a class="border" style="margin-right:auto">{{ product.quantity }}</a>
                            <a href="#" @click.prevent="addToCart(product)">+</a> 
                        </div>
                        <div class="col">
                            RF {{ product.totalPrice }} 
                            <span @click.prevent="removeFromCart(product)"  class="close">&#10005;</span>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center">
                    <h1 class="font-weight-bolder">😬</h1>
                    <p class="small">The cart is empty</p>
                </div>
                <p v-if="$store.state.cart.length > 0" class="text-right mt-2">Total: RF <b>{{ totalPrice }}</b></p>
                <div class="buttons-below d-flex justify-content-between">
                    <div @click="goHome()" class="back-to-shop"><a href="#">&leftarrow;</a><span class="text-muted">Back to shop</span></div>
                    <div @click="goToCheckout()" class="back-to-shop"><span class="text-muted">Go to checkout</span><a href="#">&rightarrow;</a></div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
   computed: {
      totalPrice: function() {
         let total = 0; 
         for(let product of this.$store.state.cart) {
            total += product.totalPrice;
         }
         return total.toFixed();
      }
   },
   methods: {
       addToCart: function(product) {
            this.$store.commit('addToCart', product);
        },
      deductFromCart: function(product) {
          this.$store.commit('deductFromCart', product);
      },
      removeFromCart: function(product) {
         this.$store.commit('removeFromCart', product);
      },
      goHome: function() {
         this.$router.push('/');
      },
      goToCheckout() {
         this.$router.push('/multi-checkout');
         //empty cart
         this.removeFromCart()
         
      }
   },
}
</script>

<style scoped>
@media(max-width:414px) {
    #qty {
        display: flex;
        flex-direction: column;
    }
}

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
    max-width: 700px;
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
    /* border-bottom-left-radius: 1rem;
    border-top-left-radius: 1rem */
    border-radius: 1rem;
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
    width: 100%;
    display: flex;
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
    font-size: 0.7rem;
    cursor: pointer;
    color:orangered
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
    padding: 2vh 0
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