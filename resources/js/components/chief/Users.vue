<template>
    <div>
        <div class="container">
            <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <p class="m-0">Buyers</p>
                                    <p class="text-white-50 small m-0">{{buyers.length != 0 ? buyers : "0"}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <p class="m-0">Vendors</p>
                                    <p class="text-white-50 small m-0">{{vendors.length != 0 ? vendors : "0"}}</p>
                                </div>
                            </div>
                        </div>
                 </div> 
            </div>
        <div class="col-lg-9 col-md-8">
            <div>
              <div class="float-left" style="font-weight:600;font-size: 1.125rem;line-height: 1.75rem;">
                <p>List of registered user</p>
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
                <input v-model="username" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
              </div>
            </div>
            <div ref="toPDF" class="table-responsive">
              <table  class="table table-striped">
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
                  <tr v-for="(user, index) in filterByName" v-bind:key="index">
                    <td>{{index+1}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.created_at}}</td>
                    <td>{{user.is_admin | setType}}</td>
                    <!-- <td>{{user.orders.length}}</td> -->

                </tr>
                </tbody>
              </table>
              <div>
                    <pagination :data="users" @pagination-change-page="getResults"></pagination>
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
        users: {},
        buyers: [],
        vendors: [],
        username: ""
      }
    },
    mounted() {
      this.getResults();
      // axios.get('/api/all-users/').then(response => this.users = response.data)
      axios.get('/api/buyers/').then(response => this.buyers = response.data)
        axios.get('/api/vendors/').then(response => this.vendors = response.data)
    },
    computed: {
      filterByName() {
        let usersArr = Object.values(this.users.data);
        return usersArr.filter(user => !user.name.indexOf(this.username));
      }
    },
    methods: {
      // filterUser() {
      //     let filteredUsers = this.users.data.filter((user) => {
      //         return user.is_admin == this.type;
      //     })
      //     return this.users.data = filteredUsers;
      // },
      getResults(page = 1) {
        axios.get('/api/all-users?page=' + page)
            .then(response => {
                this.users = response.data;
            });
		  },
      printPdf() {
        const doc = new jsPDF();
        //select data only from the whole object
        let usersData = Object.values(this.users.data);
        //turn data into an array of objects
        let usersDataObjArr = usersData.map((key) => ({
          'name': key.name,
          'email': key.email,
          'joined': key.created_at,
          'type': key.is_admin == 1 ? 'Vendor' : 'Buyer'
        }))
        //turn the array of objects into an array of arrays
        let usersDataArrArr = usersDataObjArr.map(Object.values);

        //pdf stuff
        doc.setFontSize(16);
        doc.text('Selected users Report', 14, 15)
        doc.autoTable({
          margin: { top:20 },
          head: [['Name', 'Email', 'Joined', 'Type']],
          body: usersDataArrArr,
        })
        doc.save(`${Date.now()}_some_users_report.pdf`); 
      },
    },
}
</script>