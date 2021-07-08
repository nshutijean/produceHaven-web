<template>
    <div>
        <div class="container">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <p class="m-0">Fruits</p>
                                    <p class="text-white-50 small m-0">{{fruits.length != 0 ? fruits : "0"}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <p class="m-0">Veggies</p>
                                    <p class="text-white-50 small m-0">{{veggies.length != 0 ? veggies : "0"}}</p>
                                </div>
                            </div>
                        </div>
                 </div> 
            </div>
        <div class="col-lg-9 col-md-8">
            <div>
              <div class="float-left" style="font-weight:600;font-size: 1.125rem;line-height: 1.75rem;">
                <p>List of uploaded products</p>
              </div>
              <div>
                  <button @click="printPdf()" class="btn btn-success float-right mb-2">Generate Report</button>
              </div>
            </div>
            <div>
              <!-- the input search bar -->
              <div class="input-group input-group-sm mb-3 mt-4">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-sm">Search by name</span>
                </div>
                <input v-model="prodName" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
              </div>
            </div>
            <div class="table-responsive pr-3">
              <table class="table table-striped">
                <thead class="thead-inverse">
                  <tr>
                    <th>#</th>
                    <th>ProductName</th>
                    <th>Units</th>
                    <th>Price/Unit</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Qr code</th>
                    <th>IsCertificate</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(product, index) in filterByName" :key="index">
                    <td>{{index+1}}</td>
                    <td v-html="product.name"></td>
                    <td v-html="product.units"></td>
                    <td v-html="product.price"></td>
                    <td v-html="product.category"></td>
                    <td v-html="product.description"></td>
                    <td>
                        <!-- <a :href="product.qrcodeUrl" @click="printPdf()">Download</a> -->
                        <a :href="product.qrcodeUrl" :download="product.name">
                            Download
                        </a>
                        <!-- <img :src="product.qrcodeUrl" alt=""> -->
                    </td>
                    <td>{{ product.certificate ? 'Uploaded👍' : 'No certificate😒' }}</td>
                  </tr>
                </tbody>
              </table>
                <div>
                    <pagination :data="products" @pagination-change-page="getResults"></pagination>
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
            products: {},
            fruits: [],
            veggies: [],
            cat: null,
            prodName: ""
        }
    },
    mounted() {
        // axios.get('/api/products').then((response) => { 
        //     this.products = response.data.data
        //     console.log(this.products);
        // });
        this.getResults();
        axios.get('/api/fruits').then(response => this.fruits = response.data)
        axios.get('/api/vegetables').then(response => this.veggies = response.data)
        // console.log(this.products);

    },
    computed: {
        filterByName() {
            let productsArr = Object.values(this.products.data);
            // console.log(productsArr)
            return productsArr.filter(product => !product.name.indexOf(this.prodName));
        }
    },
    methods: {
        getResults(page = 1) {
            axios.get('/api/products?page=' + page)
                .then(response => {
                    this.products = response.data;
                });
		},
        //make it a landscape format
        printPdf() {
            const doc = new jsPDF();
            //select data only from the whole object
            let productsData = Object.values(this.products.data);
            //turn data into an array of objects
            let productsDataObjArr = productsData.map((key) => ({
                'name': key.name,
                'units': key.units,
                'price': key.price,
                'category': key.category,
                'description': key.description,
                'certificate': key.certificate ? 'Uploaded' : 'No certificate',
                'created_at': key.created_at,
            }))
            //turn the array of objects into an array of arrays
            let productsDataArrArr = productsDataObjArr.map(Object.values);

            //pdf stuff
            doc.setFontSize(13);
            doc.text('Selected products Report', 14, 15)
            doc.autoTable({
            margin: { top:20 },
                head: [['ProductName', 'Units', 'Price/Unit', 'Category', 'Description', 'IsCertificate', 'UploadedAt']],
                body: productsDataArrArr,
            })
            doc.save(`${Date.now()}_some_products_report.pdf`);
      },
        
    },
}
</script>