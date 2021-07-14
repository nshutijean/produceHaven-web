<template>
    <div>
        <div class="col-lg-12 col-md-8">
            <div v-if="(products.data == [])" class="mt-3">
                <p class="warn">
                    No products uploaded yet! Use our
                    <a href="#">mobile app</a> to add one for better experience
                </p>
            </div>
            <div v-lse>
                <div>
                    <div
                        class="float-left"
                        style="font-weight: 600; font-size: 1.125rem; line-height: 1.75rem"
                    >
                        <p>All products uploaded on this store</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-inverse">
                            <tr class="font-weight-bold">
                                <td>#</td>
                                <td>Name</td>
                                <td>Category</td>
                                <td>Units</td>
                                <td>Price</td>
                                <td>Desc</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(product, index) in products"
                                :key="index"
                                @dblclick="editingItem = product"
                            >
                                <td>{{ index + 1 }}</td>
                                <td>{{ product.name }}</td>
                                <td>
                                    <!-- <select id="cat" v-model="product.category">
                    <option selected value="category">Fruit</option>
                    <option value="vegetable">Vegetable</option>
                </select> -->
                                    {{ product.category }}
                                </td>
                                <td>
                                    <!-- <input type="text" v-model="product.units"> -->
                                    {{ product.units }}
                                </td>
                                <td>
                                    <!-- <input type="text" v-model="product.price"> -->
                                    {{ product.price }}
                                </td>
                                <td>
                                    <!-- <input type="text" v-model="product.description"> -->
                                    {{ product.description }}
                                    <div
                                        class="float-right ml-5"
                                        style="color:red; cursor:pointer"
                                        @click="deleteProduct(product)"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal
            @close="endEditing"
            :product="editingItem"
            v-show="editingItem != null"
        />
        <Modal
            @close="addProduct"
            :product="addingProduct"
            v-show="addingProduct != null"
        />
        <br />
        <!-- <button class="btn btn-primary" @click="newProduct">
            Add New Product
        </button> -->
    </div>
</template>

<script>
import Modal from "./Modal";
export default {
    data() {
        return {
            products: {},
            editingItem: null,
            addingProduct: null
        };
    },
    components: { Modal },
    beforeMount() {
        axios
            .get("/api/showWithAuth/")
            .then(response => (this.products = response.data));
    },
    mounted() {
        // this.getResults();
    },
    methods: {
        newProduct() {
            this.addingProduct = {
                name: null,
                units: null,
                price: null,
                image: null,
                description: null,
                category: null,
                certificate: null
            };
        },
        endEditing(product) {
            this.editingItem = null;

            let index = this.products.data.indexOf(product);
            let name = product.name;
            let units = product.units;
            let price = product.price;
            let description = product.description;
            let category = product.category;

            //update
            axios
                .put(`/api/products/${product.id}`, {
                    name,
                    units,
                    price,
                    description,
                    category
                })
                .then(response => (this.products[index] = product));
        },
        // deletes by id
        deleteProduct(product) {
            let index = this.products.data.indexOf(product);
            // axios
            //     .delete(`/api/products/${product.id}`)
            //     .then(result => {
            //         // console.log("Deleting...")
            //         this.products.data.splice(index, 1);
            //         // console.log("Deleted!")
            //         Swal.fire(
            //             "Deleted!",
            //             "Your file has been deleted.",
            //             "success"
            //         );
            //     })
            //     .catch(err => {
            //         console.log(err);
            //     });
            Swal.fire({
                title: "Do you want to delete this product?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Delete`
                // denyButtonText: `Don't save`,
            }).then(result => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .delete(`/api/products/${product.id}`)
                        .then(result => {
                            // console.log("Deleting...")
                            this.products.data.splice(index, 1);
                            // console.log("Deleted!")
                            Swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                        })
                        .catch(err => {
                            console.log(err);
                        });
                }
                // else if (result.isDenied) {
                //     Swal.fire('Changes are not saved', '', 'info')
                // }
            });
        },
        addProduct(product) {
            this.addingProduct = null;

            let name = product.name;
            let units = product.units;
            let price = product.price;
            let description = product.description;
            let image = product.image;
            let category = product.category;
            let certificate = product.certificate;
            console.log("image: " + image + ", cert:" + certificate);

            axios
                .post("/api/products/", {
                    name,
                    units,
                    price,
                    description,
                    image,
                    category,
                    certificate
                })
                .then(response => {
                    this.products.push(product);
                    Toast.fire({
                        icon: "success",
                        title: "Product uploaded"
                    });
                })
                .catch(err => {
                    console.log("The freaking error is here:", err);
                });
        },
        getResults(page = 1) {
            axios.get("/api/products?page=" + page).then(response => {
                this.products = response.data;
                // console.log(this.products);
            });
        }
    }
};
</script>

<style scoped>
.warn {
    border: 1px solid grey;
    /* color: white; */
    padding: 3px;
    margin-top: 30px;
    padding-right: auto;
    text-align: center;
    background: white;
}

@media (max-width: 414px) {
    .warn {
        padding: 12px 4px;
    }
}
</style>
