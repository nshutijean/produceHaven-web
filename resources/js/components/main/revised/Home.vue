<template>
    <div>
        <hero></hero>
            <div class="container-fluid" id="content">
            <div class="row no-gutters justify-content-end align-items-start">
                <div class="col-md-3" id="sidebar">
                    <div class="row">
                        <div id="filters" style="background-color: #e9ffeb;">
                            <div class="col-md-12">
                                <p style="font-family: Nunito, sans-serif;">Filter products:</p>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                  <label for="exampleFormControlSelect1" style="font-weight: 500">By category</label>
                                  <select v-model="category" class="form-control" id="exampleFormControlSelect1">
                                    <option value="">All</option>
                                    <option value="fruit">Fruits</option>
                                    <option value="vegetable">Vegetable</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9" id="products-page" style="margin-right: 0;">
                    <div class="row">
                        <div class="col">
                            <p style="font-size: 36px;font-family: 'Playfair Display', serif;font-weight: bold;">Products/Produce</p>
                        </div>
                    </div>
                    <div class="row no-gutters" id="products">
                        <div v-for="(product,index) in filteredProducts" :key="index" class="col-md-4 col-sm-6" id="prod">
                            <div class="card">
                                <div class="card-body">
                                    <div id="prod-img">
                                      <img :src="product.image" :alt="product.name" class="img-fluid" id="img-manip" loading="lazy">
                                    </div>
                                    <div>
                                        <h4 class="mt-3 cd-t"><span class="float-right text-muted">{{ product.price }} RF</span>{{ product.name }}</h4>
                                    </div>
                                    <div style="font-size: 20px;">
                                        <span class="float-right type-style" style="background-color: rgb(52,254,1);">
                                          {{ product.category }}
                                        </span>
                                        <p>{{ product.units }} items</p>
                                    </div>
                                    <div>
                                      <router-link :to="{path: '/products/'+product.id}" class="btn btn-light buy-btn" type="button">Details</router-link>
                                      <button @click="addToCart(product)" class="btn btn-secondary float-right buy-btn" type="button">Add to cart</button>
                                    </div>
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
import Hero from './Hero.vue'
export default {
    components: {
        'hero': Hero
    },
    data() {
        return {
            products: [],
            category: "",
            fruitColor: "yellow",
            vegColor: "green"
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
            Toast.fire({
                icon: 'success',
                title: 'Item added to cart'
            })
        },
    },
}
</script>

<style scoped>
    #prod {
  margin-bottom: 10px;
}

#prod-img {
  height: 150px;
}

#img-manip {
  height: 100%;
}

#prod-img #img-manip {
  object-fit: cover;
  width: 100%;
}

@media (min-width: 768px) {
  #sidebar .row {
    padding-top: 70px;
  }
}

@media (min-width: 300px) {
  #sidebar .row {
    margin: auto;
  }
}

.dropdown .btn {
  margin-bottom: 20px;
}

#sidebar p {
  margin-bottom: 0;
  font-weight: 600;
  padding-bottom: 10px;
}

#filters {
  border: 1px solid rgba(0,128,0,0.2);
  border-radius: 4px;
  padding-top: 10px;
  width: 100%;
}

#cart i {
  font-size: 20px;
}

@media (min-width: 300px) {
  #search {
    margin-top: 8px;
    margin-bottom: 8px;
  }
}

#search {
  border: solid 1px black;
  border-radius: 20px;
  padding-right: 100px;
  /*margin-top: 5px;*/
  background-color: #dcdcdc;
  text-indent: 12px;
  max-height: 25px;
  /*margin: 0;*/
}

input[type="search"]::placeholder {
  /*padding-left: 10px;*/
  font-size: 14px;
}

#bg-image {
  background-size: cover;
  position: relative;
  min-width: 100%;
}

#bg-image {
  padding: 30px;
  /* background: url(bg-image.png) no-repeat; */
  background-size: cover;
  max-width: 100%;
  /*padding-bottom: 200px;*/
}

@media (min-width: 1200px) {
  #products {
    padding-right: 10px;
  }
}

@media (min-width: 300px) {
  #products-page .row .col p {
    margin-left: 15px;
  }
}

.buy-btn {
  border: 1px solid black;
  border-radius: 20px;
  padding: 6px 20px 6px 20px;
}

@media (max-width: 768px) {
  h4 {
    font-size: 17px;
  }
}

#cat-list {
  list-style-type: none;
}

.type-style {
  background-color: rgb(254,219,1);
  border-radius: 14px;
  padding: 0px 10px;
  opacity: .7;
  color: white;
}

#cat-list {
  font-size: 32px;
  padding: 30px;
  /*position: fixed;*/
  /*z-index: 1;*/
}

#cat-list li {
  padding: 20px;
  font-weight: 300;
  background: rgb(255,255,255);
  border: 1px solid rgb(0,0,0);
  /*border-radius: 28px;*/
  margin-bottom: 50px;
  margin-top: -28px;
  text-align: center;
}

#cat-list li {
  /*display: inline;*/
  font-size: 20px;
  border-radius: 14px;
}

/* #sidebar {
  position: fixed;
  z-index: 1;
  overflow-x: hidden;
  height: 100%;
} */

</style>