<template>
    <div>
        <div class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto">
            <h3 class="title">Welcome to Produce Haven Trade</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <!-- slow -->
                        <!-- <ul>
                            <li><router-link :to="{name: 'fruits'}">Fruits</router-link></li>
                            <li><router-link :to="{name: 'vegetables'}">Vegetables</router-link></li>
                        </ul> -->
                        <!-- fast -->
                        <select v-model="category">
                            <option value="">All</option>
                            <option value="fruit">Fruit</option>
                            <option value="vegetable">Vegetable</option>
                        </select>
                        <ul>
                            <li @click="filterByDate()">Newly added</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-3 product-box rounded mr-0.5" v-for="(product,index) in filteredProducts" :key="index">
                            <router-link :to="{path: '/products/'+product.id}">
                                <img :src="product.image" :alt="product.name" class="img-fluid rounded mb-3">
                                <h5>
                                    <span v-html="product.name"></span>
                                    <span class="small-text text-muted float-right">RF {{product.price}}</span>
                                </h5>
                                <button class="col-md-4 btn btn-sm btn-primary float-right">Details</button> 
                                
                            </router-link>
                            <button class="col-md-4 btn btn-sm btn-dark float-right mr-2" @click="addToCart(product)">Add To cart</button>
                            <span class="float-left text-muted">{{product.category  | capitalize}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            products: [],
            category: ""
        }
    },
    //mounted() is called after a component is loaded
    mounted() {
        axios.get("api/products").then(
            //data from paginated resources
            response => {
                this.products = response.data.data
            }
        ).catch(() => {
            Toast.fire({
                icon: 'error',
                title: 'Sorry, the problem is on our side😬'
            })
        })
    },
    computed: {
        filteredProducts() {
            return this.products.filter(product => !product.category.indexOf(this.category));
        },
    },
    methods: {
        filterByDate() {
            return this.products.sort((a, b) => b.created_at - a.created_at);
            // console.log(sortedDate);
        },
        addToCart: function(product) {
            this.$store.commit('addToCart', product);
        },
    },
}
</script>


<style scoped>
    /* Scoped style: similar to the style encapsulation in Shadow DOM */
    .small-text {
        font-size: 14px;
    }
    .product-box {
        border: 1px solid #cccccc;
        padding: 10px 15px;
    }
    .hero-section {
        height: 30vh;
        background: #ababab;
        align-items: center;
        margin-bottom: 20px;
        margin-top: -20px;
    }
    .title {
        font-size: 60px;
        color: #ffffff;
    }
    img {
        width: 248px;
        height: 186px;
    }

</style>