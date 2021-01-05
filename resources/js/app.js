
require('./bootstrap');

window.Vue = require('vue');

//css
// import '../../public/css/main.scss';

//router
import VueRouter from 'vue-router';

//vuex(states)
import Vuex from 'vuex'
Vue.use(Vuex);

//where states are globally stored
import store from './store';

//sweetalert
import Swal from 'sweetalert2';

//vForm
import { Form, HasError, AlertError } from 'vform';



//FlutterWave payment
import Flutterwave from  'flutterwave-vue-v3'
//FLWPUBK-ef3f99af7ec1c029e59b55deb1c8fee6-X <-- use this public key for a live payment session
Vue.use(Flutterwave, { publicKey: 'FLWPUBK_TEST-a0666b74be0cfbf3fd44453cc3b41a12-X' } )



Vue.use(VueRouter);
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

//global vform variable
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//using progressBar
// Vue.use(VueProgressBar, {
//     color: 'rgb(143, 255, 199)',
//     failedColor: 'red',
//     height: '4px'
// })

import App from './components/App';
import Home from './components/Home';
import Login from './components/Login';
import Register from './components/Register';
import SingleProduct from './components/SingleProduct';
import Checkout from './components/Checkout';
import Confirmation from './components/Confirmation'; 
import UserBoard from './components/UserBoard'
import Admin from './components/Admin'
import Fruits from './components/categories/Fruits'
import Vegetables from './components/categories/Vegetables'
import Cart from './components/Cart';
import AdminRegister from './components/AdminRegister';

//admin panel components
import AdminLogin from './components/chief/AdminLogin';
import AdminPanel from './components/chief/AdminPanel';
import Overview from './components/chief/Overview';
import Products from './components/chief/Products';
import Orders from './components/chief/Orders';
import Users from './components/chief/Users';
import Reports from './components/chief/Reports';



//capitalize filter for first letter
Vue.filter('capitalize', function(text) {
    text = text.toString();
    return text.charAt(0).toUpperCase() + text.slice(1);
});

//formatNumber() function being used as a filter
Vue.filter('formatCurrency', function (value) {
	return formatNumber(value, 2, '.', ',');
});

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

            ]
        },
        {
            path: '/admin/login',
            name: 'admin-login',
            component: AdminLogin,
        },
        {
            path: '/vendor-register',
            name: 'vendor',
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
            path: '/admin/:page',
            name: 'admin-pages',
            component: Admin,
            meta: {
                requiresAuth: true,
                is_admin: true
            },
            
        },
        {
            path: '/admin',
            name: 'admin',
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
                    next({ name: 'admin' })
                }
            }
            next()
        }
    } else {
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
