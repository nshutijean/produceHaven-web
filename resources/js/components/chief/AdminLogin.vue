<template>
    <div class="container">
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
                                    <input type="email" class="form-control" name="email" v-model="form.email"
                                    :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                            </div>
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
                let email = this.form.email;
                let password = this.form.password;

                if(email == "admin@email.com" && password == "123456") {
                    this.$router.push({name: 'admin-panel'});
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Email and password do not match'
                    })
                }

                
            
        }
    },
}
</script>