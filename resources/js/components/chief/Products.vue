<template>
    <div>
        <div class="container">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <p class="m-0">Fruits</p>
                                    <p class="text-white-50 small m-0">{{fruits}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <p class="m-0">Veggies</p>
                                    <p class="text-white-50 small m-0">{{veggies}}</p>
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
                    <th>#</th>
                    <th>Name</th>
                    <th>Units</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Qr code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(product, index) in products" :key="index">
                    <td>{{index+1}}</td>
                    <td v-html="product.name"></td>
                    <td v-html="product.units"></td>
                    <td v-html="product.price"></td>
                    <td v-html="product.category"></td>
                    <td v-html="product.description"></td>
                    <td>
                        <a :href="product.qrcodeUrl" download>Download</a>
                    </td>
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
            products: [],
            fruits: [],
            veggies: [],
            cat: null
        }
    },
    mounted() {
        axios.get('/api/products').then((response) => {
            this.products = response.data
            console.log(this.products);
            // this.cat = response.data.category
            response.data.forEach(data => {
                // console.log(Array.from(data.category));
                // console.log(Object.keys(data));
                
                // console.log(data.category);
            });
        });
        axios.get('/api/fruits').then(response => this.fruits = response.data)
        axios.get('/api/vegetables').then(response => this.veggies = response.data)

    },
    methods: {
        printPdf() {
            window.print();
        }
    },
}
</script>