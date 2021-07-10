<template>
    <div>
        <div class="container">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <p class="m-0">Ordered</p>
                                    <p class="text-white-50 small m-0">{{orders.total !== 0 ? orders.total : 0}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <p class="m-0">Pending</p>
                                    <p class="text-white-50 small m-0">{{pendings.length !== 0 ? pendings : "0"}}</p>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-4 mb-4">
                            <div class="card text-white bg-dark shadow">
                                <div class="card-body">
                                    <p class="m-0">Delivered</p>
                                    <p class="text-white-50 small m-0">{{delivers.length !== 0 ? delivers : "0"}}</p>
                                </div>
                            </div>
                        </div>
                 </div> 
            </div>
        <div class="col-lg-9 col-md-8">
            <div>
              <div class="float-left" style="font-weight:600;font-size: 1.125rem;line-height: 1.75rem;">
                <p>List of orders made</p>
              </div>
              <div>
                  <button @click="printPdf()" class="btn btn-success float-right mb-2">Generate Report</button>
              </div>
            </div>
            <div>
              <!-- the input search bar -->
              <div class="input-group input-group-sm mb-3 mt-4">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-sm">Search by productID</span>
                </div>
                <input v-model="productID" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead class="thead-inverse">
                  <tr>
                    <th>#</th>
                    <th>ProductID</th>
                    <th>Quantity</th>
                    <!-- <th>Cost</th> -->
                    <th>Delivery Address</th>
                    <th>is Delivered?</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(order, index) in orders.data" :key="index">
                    <td>{{index+1}}</td>
                    <td v-html="order.product_id"></td>
                    <td>{{order.quantity}}</td>
                    <!-- <td>{{order.price}}</td> -->
                    <td>{{order.address}}</td>
                    <td>{{order.is_delivered == 1 ? "Yes" : "No"}}</td>
                    <!-- <td v-if="order.is_delivered == 0"><button class="btn btn-success" @click="deliver(index)">Deliver</button></td> -->
                </tr>
                </tbody>
              </table>
                <div>
                    <pagination :data="orders" @pagination-change-page="getResults"></pagination>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import { jsPDF } from "jspdf";
import 'jspdf-autotable'
export default {
    data() {
        return {
            orders: {},
            delivers: [],
            pendings: [],
            productID: ""
        }
    },
    mounted() {
        // axios.get('/api/all-orders/').then(response => {
        //     this.orders = response.data
        //     console.log(this.orders);
        // })
        this.getResults();
        axios.get('/api/delivers/').then(response => this.delivers = response.data)
        axios.get('/api/pendings/').then(response => this.pendings = response.data)
    },
    computed: {
        // filterByID() {
        //     let ordersArr = Object.values(this.orders.data);
        //     console.log(ordersArr)
        //     return ordersArr.filter(order => !order.product_id.indexOf(this.productID));
        // }
    },
    methods: {
        getResults(page = 1) {
            let od;
            axios.get('/api/all-orders?page=' + page)
                .then(response => {
                    this.orders = response.data;
                });
		},
        printPdf() {
            const doc = new jsPDF();
            //select data only from the whole object
            let ordersData = Object.values(this.orders.data);
            //turn data into an array of objects
            let ordersDataObjArr = ordersData.map((key) => ({
            'product_id': key.product_id,
            'quantity': key.quantity,
            'address': key.address,
            'is_delivered': key.is_delivered == 1 ? 'Yes' : 'No' 
            }))
            //turn the array of objects into an array of arrays
            let ordersDataArrArr = ordersDataObjArr.map(Object.values);

            //pdf stuff
            doc.setFontSize(16);
            doc.text('Selected orders Report', 14, 15)
            doc.autoTable({
            margin: { top:20 },
            head: [['ProductID', 'Quantity', 'Delivery Address', 'IsDelivered']],
            body: ordersDataArrArr,
            })
            doc.save(`${Date.now()}_some_orders_report.pdf`); 
      },


    },
}
</script>