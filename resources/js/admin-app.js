require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'

//css
// import '../../public/css/main.scss';

//router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

//vuex(states)
import Vuex from 'vuex'
Vue.use(Vuex);

// where states are globally stored
import store from './store';

// import components
import AdminLogin from './components/chief/AdminLogin';
import AdminPanel from './components/chief/AdminPanel';
import Orders from './components/chief/Orders';
import OrdersChart from './components/chief/OrdersChart';
import Users from './components/chief/Users';
import UsersChart from './components/chief/UsersChart';
import Products from './components/chief/Products';
import ProductsChart from './components/chief/ProductsChart';
import Overview from './components/chief/Overview';
import Reports from './components/chief/Reports';

// route config
const routes = new VueRouter({
    routes: [
        {
            path: '/admin-panel',
            name: 'admin-panel',
            component: AdminPanel,
            children: [
                {
                    path: 'overview',
                    name: 'overview',
                    component: Overview
                },
                {
                    path: 'users',
                    name: 'users',
                    component: Users
                },
                {
                    path: 'orders',
                    name: 'orders',
                    component: Orders
                },
            ]
        },
    ]
});
