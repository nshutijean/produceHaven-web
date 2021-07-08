<template>
  <div>
      <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
          <div class="container">
            <router-link :to="{name: 'home'}" class="navbar-brand">
                PHT
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto"></ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <router-link :to="{name: 'vendor-register'}" class="nav-link mr-2">Sell</router-link>
                    <router-link :to="{name: 'login'}" v-if="!isLoggedIn" class="mr-3 mt-2">Login</router-link>
                    <router-link :to="{name: 'register'}" v-if="!isLoggedIn" class="mt-2">Register</router-link>
                    <span v-if="isLoggedIn">
                        <router-link :to="{name: 'userboard'}" class="nav-link" v-if="user_type == 0">Hi, {{name}}</router-link>
                        <router-link :to="{name: 'vendor'}" class="nav-link" v-if="user_type == 1">Hi, {{name}}</router-link>
                    </span>
                    <li class="nav-link" v-if="isLoggedIn" @click="logout">Logout</li>
                    <!-- <li class="nav-link mt-1.5 mr-2" data-toggle="modal" data-target="#exampleModal"> <i class="fa fa-shopping-cart" aria-hidden="true"></i>My cart({{$store.state.cartCount}})</li> -->
                    <router-link :to="{name: 'cart'}" class="nav-link mt-1.5 mr-2" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>My cart({{$store.state.cartCount}})
                    </router-link>
                    
                    <div class="float-right" id="search-list">
                        <input v-model="query" type="text" name="search" placeholder="Search...">
                        <div>
                            <ul v-if="results.length > 0 && query">
                                <li v-for="result in results" :key="result.id">
                                    <router-link :to="{path: `/products/${pid}`}">
                                        <div v-text="p_name"></div>   
                                        <div v-text="p_price"></div>   
                                        <div v-text="p_units"></div>   
                                    </router-link>
                                    <!-- <p>Yooo!</p> -->
                                </li>
                            </ul>
                            <ul v-else-if="results.length == 0">
                                <li>No match found!</li>
                            </ul>
                        </div>
                    </div>
                </ul>
            </div> 
          </div>
      </nav>
      
      <main class="py-4">
        <router-view @loggedIn="change"></router-view>
      </main>
  </div>
</template>

<script>
import Cart from './Cart';
export default {
    component: {
        Cart
    },
    data() {
        return {
            name: null,
            user_type: 0,
            isLoggedIn: localStorage.getItem('bigStore.jwt') != null,
            query: null,
            results: [],
            pid: null,
            p_name: "",
            p_price: "",
            p_units: ""
        }
    },
    mounted() {
        this.setDefaults()
    },
    watch: {
        query(after, before) {
            this.searchProducts();
        }
    },
    methods: {
        //sets the name of the user when the user is logged in as well as the type of user logged in
        setDefaults() {
            if(this.isLoggedIn) {
                let user = JSON.parse(localStorage.getItem('bigStore.user'));
                this.name = user.name;
                this.user_type = user.is_admin;
            }
        },
        //checks the current login status anytime it is called and calls the setDefaults method
        change() {
            this.isLoggedIn = localStorage.getItem('bigStore.jwt') != null;
            this.setDefaults();
        },
        //logs the user out of the application and routes the user to the homepage
        logout() {
            localStorage.removeItem('bigStore.jwt');
            localStorage.removeItem('bigStore.user');
            
            this.change();
            this.$router.push('/');
            //Empty cart
            this.$store.state.cart.splice(0, this.$store.state.cart.length);
            this.$store.state.cartCount = 0;
            
        },

        searchProducts(){
            axios.get('api/search-product', {params:{query: this.query}})
            .then((response) => {
                this.results = response.data.data;

                //product id
                this.pid = response.data.data[0].searchable.id;

                //product name
                this.p_name = response.data.data[0].searchable.name;
                //product price
                this.p_price = response.data.data[0].searchable.price;
                //product units
                this.p_units = response.data.data[0].searchable.units;
            }).catch((err) => {
                console.log(err);
            });
        }
    },
}
</script>

<style scoped>
 #search-list {
     
 }

#search-list ul {
     background: white;
     color: black;
     border: solid 1px black;
     border-radius: 3px;
     display: inline;
     z-index: 1;
     position: absolute;

 }

 #navbarSupportedContent {
     z-index: 8;
     /* position: relative; */

 }
    
</style>