// @ts-nocheck

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


//sweetalert
import Swal from 'sweetalert2';
//global sweet alert
window.Swal = Swal;
//toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
//using toast globally
window.Toast = Toast;


//vForm
import { Form, HasError, AlertError } from 'vform';
//global vform variables
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


//FlutterWave payment
import Flutterwave from  'flutterwave-vue-v3'
//FLWPUBK-ef3f99af7ec1c029e59b55deb1c8fee6-X <-- use this public key for a live payment session
Vue.use(Flutterwave, { publicKey: 'FLWPUBK_TEST-cb9f8594ac0431197c84d4de4af7e6e9-X' } )

//laravel-vue-pagination dependency global variable
Vue.component('pagination', require('laravel-vue-pagination'));


//firebase config
import firebase from "firebase";
//firebase auth on the Administrator
var firebaseConfig = {
    apiKey: "AIzaSyDUfmX9uIEag4MEYPlJgIMI2esPSNCeRFw",
    authDomain: "vue-firebase-auth-7e65f.firebaseapp.com",
    projectId: "vue-firebase-auth-7e65f",
    storageBucket: "vue-firebase-auth-7e65f.appspot.com",
    messagingSenderId: "1098044983266",
    appId: "1:1098044983266:web:8fcf1a44f5cfbd7870dbbb"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);


//components
// import App from './components/App';
import App from './components/main/revised/App';
import NavBar from './components/main/revised/NavBar'
import Hero from './components/main/revised/Hero'
// import Home from './components/main/Home';
import Home from './components/main/revised/Home';
import Footer from './components/main/revised/Footer';
import Login from './components/main/Login';
import Register from './components/main/Register';
// import SingleProduct from './components/main/SingleProduct';
import SingleProduct from './components/main/revised/SingleProduct';
// import Checkout from './components/main/Checkout';
import Checkout from './components/main/revised/Checkout';
import Confirmation from './components/main/Confirmation'; 
// import MultipleCheckout from './components/main/MultipleCheckout';
import MultipleCheckout from './components/main/revised/MultipleCheckout';
// import UserBoard from './components/main/UserBoard'
import UserBoard from './components/main/revised/UserBoard'
import Admin from './components/main/Admin'
import Fruits from './components/categories/Fruits'
import Vegetables from './components/categories/Vegetables'
// import Cart from './components/main/Cart';
import Cart from './components/main/revised/Cart';
import AdminRegister from './components/main/AdminRegister';

//admin panel components
import AdminLogin from './components/chief/AdminLogin';
import AdminPanel from './components/chief/AdminPanel';
import Overview from './components/chief/Overview';
import Products from './components/chief/Products';
import Orders from './components/chief/Orders';
import Users from './components/chief/Users';
import Reports from './components/chief/Reports';

//Reset password
import ForgotPassword from './components/resetpassword/ForgotPassword.vue';
import ResetPasswordForm from './components/resetpassword/ResetPasswordForm.vue';


//filters
//capitalize filter for first letter
Vue.filter('capitalize', function(text) {
    text = text.toString();
    return text.charAt(0).toUpperCase() + text.slice(1);
});

//formatNumber() function being used as a filter
Vue.filter('formatCurrency', function (value) {
	return formatNumber(value, 2, '.', ',');
});

//change 1s to vendors and 0s to buyer
Vue.filter('setType', function(type) {
    if(type == 0) {
        return "Buyer";
    } else if(type == 1) {
        return "Vendor";
    }
})


//routes
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/reset-password',
            name: 'reset-password',
            component: ForgotPassword,
            meta: {
                auth: false
            }
        },
        {
            path: '/reset-password/:token',
            name: 'reset-password-form',
            component: ResetPasswordForm,
            meta: {
                auth: false
            }
        },
        {
            path: '/admin/panel',
            name: 'admin-panel',
            component: AdminPanel,
            children: [
                {
                    path: 'overview',
                    name: 'overview',
                    component: Overview,
                },
                {
                    path: 'products',
                    name: 'products',
                    component: Products,
                },
                {
                    path: 'orders',
                    name: 'orders',
                    component: Orders,
                },
                {
                    path: 'users',
                    name: 'users',
                    component: Users,
                },
                {
                    path: 'reports',
                    name: 'reports',
                    component: Reports,
                },
            ],
            meta: {
                fbAuthRequired: true
            }
        },
        {
            path: '/admin/login',
            name: 'admin-login',
            component: AdminLogin,
        },
        {
            path: '/vendor-register',
            name: 'vendor-register',
            component: AdminRegister
        },
        {
            path: '/cart',
            name: 'cart',
            component: Cart
        },
        {
            path: '/fruits',
            name: 'fruits',
            component: Fruits
        },
        {
            path: '/vegetables',
            name: 'vegetables',
            component: Vegetables
        },
        {
            path: '/',
            name: 'home',
            component: Home
        }, 
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/products/:id',
            name: 'single-products',
            component: SingleProduct
        },
        {
            path: '/confirmation',
            name: 'confirmation',
            component: Confirmation
        },
        {
            path: '/multi-checkout',
            name: 'multi-checkout',
            component: MultipleCheckout
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: Checkout,
            props: (route) => ({ pid: route.query.pid })
        },
        {
            path: '/dashboard',
            name: 'userboard',
            component: UserBoard,
            meta: {
                requiresAuth: true,
                is_user: true
            }
        },
        {
            path: '/vendor/:page',
            name: 'admin-pages',
            component: Admin,
            meta: {
                requiresAuth: true,
                is_admin: true
            },
            
        },
        {
            path: '/vendor',
            name: 'vendor',
            component: Admin, 
            meta: {
                requiresAuth: true,
                is_admin: true
            }
            
        },

        
    ]
})

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if(localStorage.getItem('bigStore.jwt') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            let user = JSON.parse(localStorage.getItem('bigStore.user'))
            if(to.matched.some(record => record.meta.is_admin)) {
                if(user.is_admin == 1) {
                    next()
                }
                else {
                    next({ name: 'userboard' })
                }
            }
            else if(to.matched.some(record => record.meta.is_user)) {
                if(user.is_admin == 0) {
                    next()
                }
                else {
                    next({ name: 'vendor' })
                }
            }
            next()
        }
    } 
    // else if (to.matched.some(record => record.meta.fbAuthRequired)) {
    //     if (firebase.auth().currentUser) {
    //       next();
    //     } else {
    //       alert('You must be logged in to see this page');
    //       next({
    //         path: '/admin/login',
    //       });
    //     }
    // }
    else {
        next()
    }
})

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store: new Vuex.Store(store)
});
