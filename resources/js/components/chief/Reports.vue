<template>
    <div>
        <div class="row">
            <div class="float-left ml-3" style="font-weight:600;font-size: 1.5rem;line-height: 1.75rem;">
                <p>GENERATE THE OVERALL RECORDS IN THE STORE</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 col-xl-3 mb-4" style="cursor:pointer" @click="printPdfProducts()">
                <div class="card shadow border-left-primary py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col mr-2 pb-1">
                                <div class="text-uppercase text-primary font-weight-bold text-xs mb-1">
                                    <span>Products</span>
                                </div>
                                <div class="text-dark font-weight-bold h5 mb-0 mt-2">
                                    <span>Click to generate all products report</span>
                                </div>
                            </div>
                            <div class="col-auto ml-3"><i class="fas fa-calendar fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4" style="cursor:pointer" @click="printPdfOrders()">
                <div class="card shadow border-left-success py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col mr-2 pb-4">
                                <div class="text-uppercase text-success font-weight-bold text-xs mb-1">
                                    <span>Orders</span>
                                </div>
                                <div class="text-dark font-weight-bold h5 mb-0 mt-2">
                                    <span>Click to generate all orders report</span>
                                </div>
                            </div>
                            <div class="col-auto ml-3"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 mb-4" style="cursor:pointer" @click="printPdfUsers()">
                <div class="card shadow border-left-warning py-2">
                    <div class="card-body">
                        <div class="row align-items-center no-gutters">
                            <div class="col mr-2 pb-4">
                                <div class="text-uppercase text-warning font-weight-bold text-xs mb-1">
                                    <span>Users</span>
                                </div>
                                <div class="text-dark font-weight-bold h5 mb-0 mt-2">
                                    <span>Click to generate all users report</span>
                                </div>
                            </div>
                            <div class="col-auto ml-3 "><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                        </div>
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
            orders: {},
            users: {}
        }
    },
    mounted() {
        axios.get('/api/products/').then(response => {this.products = response.data;})
        axios.get('/api/all-users/').then((response) => {this.users = response.data})
        axios.get('/api/all-orders/').then(response => this.orders = response.data)
    },
    methods: {
        // printPdfProducts() {
        //     let type = 'products';
        //     let data = (key) => {
        //         return {
        //             'name': key.name,
        //             'units': key.units,
        //             'price': key.price,
        //             'category': key.category,
        //             'description': key.description,
        //             'certificate': key.certificate ? 'Uploaded' : 'No certificate',
        //             'created_at': key.created_at,
        //         }
        //     };
        //     let head = ['ProductName', 'Units', 'Price/Unit', 'Category', 'Description', 'IsCertificate', 'UploadedAt'];
        //     this.printPdf(type, data, head);
        // },
        // printPdf(type, data, head) {
        //     const doc = new jsPDF();
        //     //select data only from the whole object
        //     let dataArr = Object.values(`this.${type}.data`);
        //     //turn data into an array of objects
        //     let dataObjArr = dataArr.map((key) => (data))
        //     //turn the array of objects into an array of arrays
        //     let dataArrArr = dataObjArr.map(Object.values);

        //     //pdf stuff
        //     doc.setFontSize(13);
        //     doc.text('8 selected products report', 14, 15)
        //     doc.autoTable({
        //     margin: { top:20 },
        //         head: [head],
        //         body: dataArrArr,
        //     })
        //     doc.save(`${Date.now()}_all_products_report.pdf`);
        // },

        // bruh this👇 is not reusablility, find a better way to write it 🚫🚫🚫⛔⛔
        printPdfProducts() {
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
            doc.text('All products uploaded by vendors', 14, 15)
            doc.autoTable({
            margin: { top:20 },
                head: [['ProductName', 'Units', 'Price/Unit', 'Category', 'Description', 'IsCertificate', 'UploadedAt']],
                body: productsDataArrArr,
            })
            doc.save(`${Date.now()}_all_products_report.pdf`);
        },
        printPdfUsers() {
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
            doc.text('All current registered users', 14, 15)
            doc.autoTable({
            margin: { top:20 },
            head: [['Name', 'Email', 'Joined', 'Type']],
            body: usersDataArrArr,
            })
            doc.save(`${Date.now()}_all_users_report.pdf`); 
        },
        printPdfOrders() {
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
            doc.text('All orders made until now', 14, 15)
            doc.autoTable({
                margin: { top:20 },
                head: [['ProductID', 'Quantity', 'Delivery Address', 'IsDelivered']],
                body: ordersDataArrArr,
            })
            doc.save(`${Date.now()}_all_orders_report.pdf`);
        },
    },
}
</script>