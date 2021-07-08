<template>
    <div>
        <div class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto">
        <h2 class="text-center">
            <span class="title"><strong>Thank you!</strong></span><br>
            <span class="medium-text">Your order has been placed 🎉</span><br>
            <div class="orders-link">
                <span>👉🏾</span>
                <router-link :to="{name: 'userboard'}" class="small-link">
                    See your orders
                </router-link>
                <span>👈🏾</span>
            </div>
        </h2>
    </div>
    </div>
</template>

<script>
export default {
    
}
</script>

<style scoped>
    .medium-text { font-size: 36px; }
    .small-link { font-size: 24px; text-decoration: underline; color: #777; padding-top: 40px;}
    .hero-section { height: 80vh; align-items: center; margin-top: -20px; margin-bottom: 20px; }
    .title { font-size: 60px; }
    .orders-link {margin-top: 30px;}
</style>