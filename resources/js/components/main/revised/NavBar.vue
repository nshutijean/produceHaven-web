<template>
    <div>
        <nav class="navbar navbar-light navbar-expand-md">
        <div class="container-fluid">
            <router-link :to="{name: 'home'}" class="navbar-brand" href="#" style="font-family: 'Playfair Display', serif; color: #38c172"><strong>ProduceHaven</strong></router-link>
            <a class="navbar-brand" href="#" style="font-family: 'Playfair Display', serif;">Blog</a>
            <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav text-center ml-auto" style="font-size: 15px;">
                    <li class="nav-item" role="presentation">
                        <router-link :to="{name: 'vendor-register'}" class="nav-link text-dark" href="#">Sell</router-link>
                    </li>
                    <li class="nav-item" role="presentation">
                        <router-link :to="{name: 'login'}" v-if="!isLoggedIn" class="nav-link text-dark" href="#">Login</router-link>
                    </li>
                    <li class="nav-item" role="presentation">
                        <router-link :to="{name: 'register'}" v-if="!isLoggedIn" class="nav-link text-dark" href="#">Register</router-link>
                    </li>
                    <span v-if="isLoggedIn">
                        <router-link :to="{name: 'userboard'}" class="nav-link" v-if="user_type == 0">Hi, {{name}}</router-link>
                        <router-link :to="{name: 'vendor'}" class="nav-link" v-if="user_type == 1">Hi, {{name}}</router-link>
                    </span>
                    <li class="nav-link" v-if="isLoggedIn" @click="logout" style="cursor: pointer">Logout</li>
                    <li class="nav-item" v-if="isLoggedIn" role="presentation" id="cart">
                        <router-link :to="{name: 'cart'}" class="nav-link text-dark" href="#" v-if="user_type == 0">
                             <i class="fa fa-shopping-cart" aria-hidden="true"></i>My cart({{$store.state.cartCount}})
                        </router-link>
                    </li>
                </ul>
                <!-- full search feature missing -->
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><input class="mx-auto" type="search" id="search" placeholder="Search product"></li>
                </ul>
            </div>
        </div>
    </nav>
    </div>
</template>

<script>
import Cart from '../Cart.vue';
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
.navbar {
    padding-top: 15px;
    padding-bottom: 15px;
    background: white;
}
</style>