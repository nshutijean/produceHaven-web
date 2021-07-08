<template>
    <div>
        <div class="container">
            <div class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto">
                <h2 class="title">All your orders</h2>
            </div>
            <div class="row">
                <div v-for="(order, index) in orders" :key="index" class="col-md-4 col-lg-4">
                    <div class="card">
                        <div id="prod-img">
                            <img :src="order.product.image" :alt="order.product.name" class="img-fluid" id="img-manip" loading="lazy">
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{ order.product.name }}</h4>
                            <div class="d-flex justify-content-between align-items-center" id="price-qty">
                                <p id="price">RF {{order.product.price * order.quantity}}</p>
                                <p id="qty">{{order.quantity}} items</p>
                            </div>
                            <div id="location">
                                <p>Drop-off location:&nbsp;
                                    <span>{{order.address || "Not specified"}}</span>
                                </p>
                            </div>
                            <div id="status">
                                <p>Status:&nbsp;
                                    <span>
                                        {{order.is_delivered == 1 ? "Shipped😁" : "Not shipped yet😬"}}
                                    </span>
                                </p>
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
    data() {
        return {
            user: null,
            orders: []
        } 
    },
    beforeMount() {
        this.user = JSON.parse(localStorage.getItem('bigStore.user'));

        axios.defaults.headers.common['Content-Type'] ='application/json';
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bigStore.jwt');

        axios.get(`api/users/${this.user.id}/orders`)
            .then(response => this.orders = response.data)
            .catch(error => { console.log(error)})
    },
}
</script>

<style scoped>
.hero-section { background: #38c172; height: 20vh; align-items: left; margin-bottom: 20px; margin-top: -10px; }
.title { font-size: 60px; color: #ffffff; }
@media (max-width: 414px) {
    .title {
        font-size: 32px;
    }
}
.container {
  margin-top: 30px;
}

#prod-img #img-manip {
  object-fit: cover;
  width: 100%;
}

#img-manip {
  height: 100%;
}

#prod-img {
  height: 190px;
}

@media (min-width: 300px) {
  .card {
    margin-bottom: 20px;
  }
}

.card-title {
  font-size: 32px;
  font-weight: 700;
}

@media (min-width: 1200px) {
  .card-body {
    margin-left: 20px;
    margin-right: 20px;
  }
}

p span {
  /*width: 50px;*/
  font-weight: 500;
}

#price-qty #price {
  font-weight: 500;
  font-style: italic;
}


</style>