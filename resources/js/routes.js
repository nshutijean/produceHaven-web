//a list of components
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
import AdminLogin from './components/chief/AdminLogin';
import AdminPanel from './components/chief/AdminPanel';
import Overview from './components/chief/Overview';

//exported routes to app.js
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin-panel',
            name: 'admin-panel',
            component: AdminPanel,
            
        },
        {
            path: '/admin-login',
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