import Vue from 'vue';

let cart = window.localStorage.getItem('cart');
let cartCount = window.localStorage.getItem('cartCount');
// let price = window.localStorage.getItem('price');

let store = {
    state: {
        cart: cart ? JSON.parse(cart) : [],
        cartCount: cartCount ? parseInt(cartCount) : 0,
        // price: price ? parseInt(price) : 0
    },
    mutations: {
        addToCart(state, product) {
            let found = state.cart.find(good => good.id == product.id);

            if(found) {
                found.quantity++;
                found.totalPrice = found.quantity * found.price;
            } else {
                state.cart.push(product);
                Vue.set(product, 'quantity', 1);
                Vue.set(product, 'totalPrice', product.price);
            }
            state.cartCount++;
            this.commit('saveCart');
        },
        deductFromCart(state, product) {
            let found = state.cart.find(good => good.id == product.id);

            if(found) {
                found.quantity--;
                found.totalPrice = found.quantity * found.price;
            } else {
                state.cart.push(product);
                Vue.set(product, 'quantity', 1);
                Vue.set(product, 'totalPrice', product.price);
            }
            state.cartCount--;
            this.commit('saveCart');
        },
        removeFromCart(state, product) {
            let index = state.cart.indexOf(product);

            if(index > -1) {
                let good = state.cart[index];
                state.cartCount -= good.quantity;

                state.cart.splice(index, 1);
            }
            this.commit('saveCart');
        },
        saveCart(state) {
            window.localStorage.setItem('cart', JSON.stringify(state.cart))
            window.localStorage.setItem('cartCount', state.cartCount);
        }
    },
    
}

export default store;