<template>
    <div>
        <div class="container">
            <div class="row" id="prod-detail">
                <div class="col-sm-12 col-md-6 prod-img">
                    <img class="img-fluid" id="img-manip" :src="product.image" :alt="product.name" loading="lazy">
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
                        <div class="col">
                            <h1>{{ product.price }} RF/</h1>
                            <p>{{ product.units }} items remaining<br></p>
                            <a :href="product.certificate" id="link" download class="font-weight-bolder">
                                Open Certficate here!
                            </a>
                            <router-link :to="{ path: '/checkout?pid=' + product.id }" class="btn btn-primary buy-now" type="button">
                                Buy Now
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// @ts-nocheck
export default {
    data() {
        return {
            product: [],
            showModal: false,
            name: null,
            user_type: 0,
            isLoggedIn: localStorage.getItem('bigStore.jwt') != null,
        };
    },
    //before the component is rendered
    beforeMount() {
        let url = `/api/products/${this.$route.params.id}`;
        axios.get(url)
        .then((response) => {
            this.product = response.data;
            // console.log(response.data);
            }
        );
    },
    mounted() {
        this.setDefaults();
    },
    methods: {
    }
}
</script>

<style scoped>
@media (max-width: 796px) {
  #right-down .buy-now {
    font-size: 20px;
  }
}

#right-down .buy-now {
  background: white;
  color: black;
  font-weight: 700;
  font-size: 25px;
  border: 3px solid black;
  /* float: right; */
  width: 100%;
  margin-top: 20px;
  text-decoration: none;
}


#prod-detail {
  margin-top: 40px;
}

#img-manip {
  border-radius: 8px;
  height: 100%;
}

.prod-img img {
  object-fit: cover;
  width: 100%;
  /*height: 335px;*/
}

@media (max-width: 992px) {
  .prod-img img {
    width: 100%;
    object-fit: cover;
    height: 363px;
  }
}

@media (max-width: 1024px) {
  .prod-img img {
    width: 100%;
    object-fit: cover;
    height: 366px;
  }
}

@media (max-width: 414px) {
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

#right-down h1 {
  color: black;
  font-family: 'Playfair Display';
  font-weight: 600;
  opacity: .5;
}

#right-down p {
  font-size: 21px;
}

#right-down button {
  background: white;
  color: black;
  max-width: 116px;
  font-weight: 700;
  font-size: 20px;
  border: 3px solid black;
  margin-top: 15px;
}

#right-down #link {
  text-decoration: underline;
  margin-top: 5px;
  font-size: large;
  color: black;
}

@media (min-width: 300px) {
  #right-down #link {
  }
}

hr {
  border: 1.5px solid;
  margin-right: 20px;
}

@media (max-width: 768px) {
  hr {
    margin-top: 5px;
    margin-bottom: 0px;
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

</style>