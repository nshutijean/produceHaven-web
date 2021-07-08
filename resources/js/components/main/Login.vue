<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        <span>Not registered yet?</span><router-link :to="{name: 'register'}" class="ml-2">Register Here!</router-link>
                        <br>
                        <span style="font-size: 18px;"><b>Login</b></span> 
                    </div>
                        <form @submit.prevent="handleSubmit" method="post">
                            <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" v-model="form.email"
                                    :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div> 
                            </div>
                            <!-- <div class="form-group">
                                <label>Email</label>
                                <input v-model="form.email" type="text" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" v-model="form.password"
                                    :class="{ 'is-invalid': form.errors.has('password') }" min="6">
                                    <has-error :form="form" field="password"></has-error>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <!-- <button type="submit" class="btn btn-primary" @click="handleSubmit"> -->
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                                <!-- <div class="col-md-6 float-right">
                                    <router-link to="/reset-password">Forgot Password?</router-link>
                                </div> -->
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form : new Form({
                email: '',
                password: '' 
            })
        }
    },
    methods: {
        handleSubmit(e) {
                console.log(this.form)
                let email = this.form.email;
                let password = this.form.password;
                this.form.post('api/login', {email, password})
                .then((response) => {
                    
                    let user = response.data.user;
                    let is_admin = user.is_admin;

                    // let user_1 = JSON.parse(localStorage.getItem('bigStore.user'));
                    // console.log(user_1);

                    localStorage.setItem('bigStore.user', JSON.stringify(user));
                    localStorage.setItem('bigStore.jwt', response.data.token);

                    if(email !== "" && password !== "") {
                        if(localStorage.getItem('bigStore.jwt') != null) {
                            this.$emit('loggedIn');
                            if(this.$route.params.nextUrl != null) {
                                this.$router.push(this.$route.params.nextUrl);
                            } else {
                                // this.$router.push((is_admin == 1 ? 'vendor' : 'dashboard'));
                                // this.$store.state.cartCount = 0;
                                if(is_admin == 1) {
                                    this.$router.push('/vendor');
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Logged in as a vendor'
                                    })
                                } else {
                                    this.$router.push('/dashboard');
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Logged in as a buyer'
                                    })
                                }
                            }
                            // this.form.reset();
                        }  
                    } 
                }).catch((err) => {
                    Toast.fire({
                        icon: 'error',
                        title: 'The given data was invalid'
                    })
                });
            
        },
    },
}
</script>