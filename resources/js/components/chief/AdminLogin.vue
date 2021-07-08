<template>
    <div class="container" style="margin-top: 40px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">            
                        <span style="font-size: 18px;"><b>Admin Login</b></span> 
                    </div>
                        <form @submit.prevent="handleSubmit" method="post">
                            <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input required type="email" class="form-control" name="email" v-model="form.email"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input required type="password" class="form-control" name="password" v-model="form.password"
                                    min="6">
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <!-- <button type="submit" class="btn btn-primary" @click="handleSubmit"> -->
                                    <button ref="lgnBtn" type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                    <div class="float-right mt-2">
                                        <span>Are you a buyer?</span>
                                        <router-link :to="{name: 'login'}" class="ml-2">Login Here!</router-link>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </div>
</template>

<script>
import firebase from 'firebase';
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
            let email = this.form.email;
            let password = this.form.password;
            this.$refs.lgnBtn.innerHTML = 'Logging in ...'
            //login with firebase
            if(email !== "" || password !== "") {
                firebase.auth().signInWithEmailAndPassword(email, password)
                .then(() => {
                    // alert('Successfully logged in');
                    this.$router.push('/admin/panel/overview');
                    Toast.fire({
                        icon: 'success',
                        title: 'Logged in 😊'
                    })
                })
                .catch((error) => {
                    Toast.fire({
                        icon: 'error',
                        title: `${error.message}`
                    })
                    this.$refs.lgnBtn.innerHTML = 'Login'
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Fields empty ⛔'
                })
            } 
        }
    },
}
</script>