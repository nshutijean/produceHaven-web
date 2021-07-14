<template>
    <div class="col-lg-12 col-md-8">
    <div>
      <div
        class="float-left"
        style="font-weight: 600; font-size: 1.125rem; line-height: 1.75rem"
      >
        <p>Users who made orders in this store</p>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead class="thead-inverse">
            <tr style="font-weight: 600;">
                <td>#</td>
                <td>Name</td>
                <td>Email</td>
                <td>Phonenumber</td>
                <td>Joined</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user, index) in users" :key="index">
                <td>{{index+1}}</td>
                <td>{{user.name}}</td>
                <td>{{user.email}}</td>
                <td>{{user.phoneNumber}}</td>
                <td>{{user.created_at}}</td>
                <!-- <td>{{user.orders.length}}</td> -->
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            vendorUser: null,
            order: null,
            newUsers: [],
            orders: [],
            newOrders: []
        }
    },
    beforeMount() {
        this.vendorUser = JSON.parse(localStorage.getItem('bigStore.user'));
        this.getUsersWithOrders()
    },
    methods: {
        getUsersWithOrders() {
            axios.get('/api/orders/')
            .then(
                response => {
                    this.orders = response.data
                    this.orders.forEach(order => {
                        // orders from this specific vendor
                        if(this.vendorUser.id == order.product.user_id) {
                            axios.get(`/api/users/${order.user_id}`)
                            .then((response) => {
                                //insert response.data into this.users array
                                this.users.push(response.data)
                                console.log(this.users)
                            }).catch((err) => {
                                console.error(err)
                            });
                        } 
                    });
                }
            )
            .catch(err => {
                console.log(err)
            })
        }
    },
}
</script>

<style scoped>

</style>