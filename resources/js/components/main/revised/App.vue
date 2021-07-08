<template>
    <div>
        <nav-bar></nav-bar>
        <!-- <NavBar /> -->
        <main>
            <router-view @loggedIn="change"></router-view>
        </main>
        <Footer />
    </div>
</template>

<script>
import NavBar from './NavBar.vue' 
import Footer from './Footer.vue'
export default {
    components: {
        'nav-bar': NavBar,
        Footer
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