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
                    <router-link :to="{name: 'vendor'}" class="nav-link mr-2">Sell</router-link>
                    <router-link :to="{name: 'login'}" v-if="!isLoggedIn" class="mr-3 mt-2">Login</router-link>
                    <router-link :to="{name: 'register'}" v-if="!isLoggedIn" class="mt-2">Register</router-link>
                    <span v-if="isLoggedIn">
                        <router-link :to="{name: 'userboard'}" class="nav-link" v-if="user_type == 0">Hi, {{name}}</router-link>
                        <router-link :to="{name: 'admin'}" class="nav-link" v-if="user_type == 1">Hi, {{name}}</router-link>
                    </span>
                    <li class="nav-link" v-if="isLoggedIn" @click="logout">Logout</li>
                    <!-- <li class="nav-link mt-1.5 mr-2" data-toggle="modal" data-target="#exampleModal"> <i class="fa fa-shopping-cart" aria-hidden="true"></i>My cart({{$store.state.cartCount}})</li> -->
                    <router-link :to="{name: 'cart'}" class="nav-link mt-1.5 mr-2" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>My cart({{$store.state.cartCount}})
                    </router-link>
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
            isLoggedIn: localStorage.getItem('bigStore.jwt') != null
        }
    },
    mounted() {
        this.setDefaults()
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
        },
    },
}
</script>

<style scoped>

</style>