<template>
    <div>
        <div class="container">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <p class="m-0">Buyers</p>
                                    <p class="text-white-50 small m-0">{{buyers}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <p class="m-0">Vendors</p>
                                    <p class="text-white-50 small m-0">{{vendors}}</p>
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
                    <td>Name</td>
                    <td>Email</td>
                    <td>Joined</td>
                    <td>Type</td>

                    <!-- <td>Total Orders</td>  -->

                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(user, index) in users" v-bind:key="index">
                    <td>{{index+1}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.created_at}}</td>
                    <td>{{user.is_admin | setType}}</td>
                    <!-- <td>{{user.orders.length}}</td> -->

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
        users: [],
        buyers: [],
        vendors: []
      }
    },
    mounted() {
      axios.get('/api/all-users/').then(response => this.users = response.data)
      axios.get('/api/buyers/').then(response => this.buyers = response.data)
        axios.get('/api/vendors/').then(response => this.vendors = response.data)
    },
}
</script>