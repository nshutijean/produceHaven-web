<template>
    <div>
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(product, index) in products" :key="index" @dblclick="editingItem = product">
                    <td>{{index+1}}</td>
                    <td v-html="product.name"></td>
                    <td v-html="product.category">
                        <!-- <select id="cat" v-model="product.category">
                            <option selected value="category">Fruit</option>
                            <option value="vegetable">Vegetable</option>
                        </select> -->
                    </td>
                    <td v-html="product.units">    
                        <!-- <input type="text" v-model="product.units"> -->
                    </td>
                    <td v-html="product.price">
                        <!-- <input type="text" v-model="product.price"> -->
                    </td>
                    <td v-html="product.description">
                        <!-- <input type="text" v-model="product.description"> -->
                    </td>
                </tr>
            </tbody>
        </table >
        <Modal @close="endEditing" :product="editingItem" v-show="editingItem != null"/>
        <Modal @close="addProduct" :product="addingProduct" v-show="addingProduct != null"/>
        <br>
        <button class="btn btn-primary" @click="newProduct">Add New Product</button>
    </div>
</template>

<script>
import Modal from './Modal';
export default {
    
    data() {
        return {
            products: [],
            editingItem: null,
            addingProduct: null
        }
    },
    components: {Modal},
    beforeMount() {
        axios.get('/api/products/').then(response => this.products = response.data)
    },
    methods: {
        newProduct() {
            this.addingProduct = {
                name: null,
                units: null,
                price: null,
                image: null,
                description: null,
                category: null
            }
        },
        endEditing(product) {
            this.editingItem = null;

            let index = this.products.indexOf(product);
            let name = product.name;
            let units = product.units;
            let price = product.price;
            let description = product.description;
            let category = product.category;

            //update
            axios.put(`/api/products/${product.id}`, {name, units, price, description, category})
                .then(response => this.products[index] = product)
        },
        addProduct(product) {
            this.addingProduct = null;

            let name = product.name;
            let units = product.units;
            let price = product.price;
            let description = product.description;
            let image = product.image;
            let category = product.category

            axios.post("/api/products/", {name, units, price, description, image, category})
                .then(response => this.products.push(products))
        }
    },
}
</script>

<style scoped>

</style>