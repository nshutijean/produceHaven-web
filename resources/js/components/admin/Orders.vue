<template>
    <div>
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <td></td>
                    <td>Product</td>
                    <td>Quantity</td>
                    <td>Cost</td>
                    <td>Delivery Address</td>
                    <td>is Delivered?</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order, index) in orders.data" :key="index">
                    <td>{{index+1}}</td> 
                    <td>{{order.product.name}}</td>
                    <td>{{order.quantity}}</td>
                    <td>{{order.quantity * order.product.price}}</td>
                    <td>{{order.address}}</td>
                    <td>{{order.is_delivered == 1 ? "Yes" : "No"}}</td>
                    <td v-if="order.is_delivered == 0">
                        <button class="btn btn-success" @click="deliver(index)">Deliver</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- <div>
            <pagination :data="orders" @pagination-change-page="getResults"></pagination>
        </div> -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            orders: [],
            user: [],
            p_name: "",
            address: "",
            quantity: "",
            cost: "",
            phonenumber: "",
            buyer_id: null,
            buyer_name: ""
        }
    },
    mounted() {
        // this.getResults();
        axios.get('/api/all-orders/').then(
            response => {
                this.orders = response.data
            }
        );
    },
    methods: {
        // getResults(page = 1) {
        //     axios.get('/api/orders?page=' + page)
        //     .then(response => {
        //         this.orders = response.data;
        //     });
		// },
        shipIt() {
            let pp_name = this.p_name;
            let d_address = this.address;
            let p_quantity = this.quantity;
            let pn = this.phonenumber;
            let b_name = this.buyer_name;

            let message = `Hello Biras, please deliver: %0a%0a🛒 Product: *${pp_name}* %0a🔢 Quantity: *${p_quantity}* %0ato %0a👑 Client: *${b_name}* %0a🏡 Address: *${d_address}* %0a📞 Phonenumber: *${pn}* %0a%0afrom our store. %0a%0aThanks`;
            Swal.fire({
                title: '<strong>Deliver</strong>',
                icon: 'info',
                html:
                    '<em><i>Deliver with Biras Delivery</i></em>',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:
                    `<a target="_blank" href="https://api.whatsapp.com/send?phone=+250780679487&text=${message}" style="color:white"><i class="fa fa-thumbs-up"></i></a>`,
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText:
                    '<i class="fa fa-thumbs-down"></i>',
                cancelButtonAriaLabel: 'Thumbs down'
            })
        },
        //retrieve info of a buyer who made the order
        getBuyerInfo(buyer_id) {
            axios.get(`/api/users/${buyer_id}`)
            .then((response) => {
                this.user = response.data;
                this.phonenumber = response.data.phoneNumber;
                this.buyer_name = response.data.name;
                // console.log(this.buyer_name)
            });
        },
        deliver(index) {
            let order = this.orders[index];
            this.p_name = order.product.name;
            this.address = order.address;
            this.quantity = order.quantity;
            this.cost = order.quantity * order.product.price;

            //buyer_info
            this.buyer_id = order.user_id;
            this.getBuyerInfo(this.buyer_id);
            

            //send delivery details to whatsapp
            this.shipIt();

            axios.patch(`/api/orders/${order.id}/deliver`)
                .then(response => {
                    this.orders[index].is_delivered = 1;
                    console.log(response);
                    //force Vue instance to rerender
                    this.$forceUpdate();
            })

        },
    },
}
</script>

<style scoped>

</style>