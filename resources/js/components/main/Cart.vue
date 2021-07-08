<template>
    <div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Cart</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="goHome()">
                  <span aria-hidden="true">&times;</span>
               </button>
               </div>
               <div v-if="$store.state.cart.length > 0" class="modal-body">
                  <p v-for="product in $store.state.cart" :key="product.id">
                     {{product.name}} | {{product.quantity}} unit(s) = RF {{product.totalPrice}}
                     <span class=" float-right removebtn" title="Remove from cart" @click.prevent="removeFromCart(product)" aria-hidden="true" style="cursor:pointer">&times;</span>
                  </p>
                  <p>Total: RF {{totalPrice}}</p>
               </div>
               <div class="modal-footer">
               <button @click="goToCheckout()" type="button" class="btn btn-secondary" data-dismiss="modal">Go to checkout</button>
               </div>
            </div>
         </div>
         </div>
    </div> 
</template>

<script>
export default {
   computed: {
      totalPrice: function() {
         let total = 0;
         for(let product of this.$store.state.cart) {
            total += product.totalPrice;
         }
         return total.toFixed();
      }
   },
   methods: {
      removeFromCart: function(product) {
         this.$store.commit('removeFromCart', product);
      },
      goHome: function() {
         this.$router.push('/');
      },
      goToCheckout() {
         this.$router.push('/multi-confirmation');
         //empty cart
         this.removeFromCart()
         
      }
   },
}
</script>

<style scoped>
   .removebtn {
      color: red;
   }
</style>