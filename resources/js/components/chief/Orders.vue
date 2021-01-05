<template>
    <div>
        <div class="container">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <p class="m-0">Pending</p>
                                    <p class="text-white-50 small m-0">{{pendings}}</p>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-4 mb-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <p class="m-0">Ordered</p>
                                    <p class="text-white-50 small m-0">{{orders.length}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card text-white bg-dark shadow">
                                <div class="card-body">
                                    <p class="m-0">Delivered</p>
                                    <p class="text-white-50 small m-0">{{delivers}}</p>
                                </div>
                            </div>
                        </div>
                 </div> 
            </div>
        <div class="col-lg-9 col-md-8">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead class="thead-inverse">
                  <tr>
                    <th></th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Delivery Address</th>
                    <th>is Delivered?</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(order, index) in orders" v-bind:key="index">
                    <td>{{index+1}}</td>
                    <td v-html="order.product.name"></td>
                    <td>{{order.quantity}}</td>
                    <td>{{order.quantity * order.product.price}}</td>
                    <td>{{order.address}}</td>
                    <td>{{order.is_delivered == 1 ? "Yes" : "No"}}</td>
                    <td v-if="order.is_delivered == 0"><button class="btn btn-success" @click="deliver(index)">Deliver</button></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            orders: [],
            delivers: [],
            pendings: []
        }
    },
    mounted() {
        axios.get('/api/all-orders/').then(response => this.orders = response.data)
        axios.get('/api/delivers/').then(response => this.delivers = response.data)
        axios.get('/api/pendings/').then(response => this.pendings = response.data)
    },
    methods: {
      deliver(index) {
          let order = this.orders[index];
          axios.patch(`/api/orders/${order.id}/deliver`)
              .then(response => {
                  this.orders[index].is_delivered = 1;
                  //force Vue instance to rerender
                  this.$forceUpdate();
          })
      }
    },
}
</script>