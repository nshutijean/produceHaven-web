<template>
    <div class="container" style="margin-top: 30px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        <span>Have an account?</span><router-link :to="{name: 'login'}" class="ml-2">Login Here!</router-link>
                        <br>
                        <span style="font-size: 18px;"><b>Register as vendor</b></span> 
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input v-model="form.name" type="text" name="name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email Address</label>
                                <div class="col-md-6">
                                    <input v-model="form.email" type="email" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                            </div>
                            <!-- Phone number -->
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Phone Number</label>
                                <div class="col-md-6">
                                    <input v-model="form.phoneNumber" type="text" name="phoneNumber"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phoneNumber') }">
                                    <has-error :form="form" field="phoneNumber"></has-error>
                                </div>
                            </div>
                            <!-- Subaccount ID -->
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Sub ID (<small>If you do not have this, contact us</small>)</label>
                                <div class="col-md-6">
                                    <input v-model="form.subAccID" type="text" name="subAccID"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('subAccID') }">
                                    <has-error :form="form" field="subAccID"></has-error>
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input v-model="form.password" type="password" name="password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                    <has-error :form="form" field="password"></has-error>
                                </div>
                            </div>
                            <!-- Confirm password -->
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input v-model="form.c_password" type="password" name="c_password"
                                    class="form-control">
                                    <!-- <has-error :form="form" field="password_confirmation"></has-error> -->
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click.prevent="handleSubmit">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['nextUrl'],
    data() {
        return {
            form: new Form({
                name: "",
                email: "",
                password: "",
                c_password: "",
                phoneNumber: "",
                subAccID: ""
            })
            
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            
            let name = this.form.name;
            let email = this.form.email;
            let password = this.form.password;
            let c_pass = this.form.c_password;
            let pn = this.form.phoneNumber;
            let subAccID = this.subAccID;

            this.form.post('api/register-vendor', {name, email, password, c_pass, pn, subAccID})
                .then(response => {
                    let data = response.data;
                    localStorage.setItem('bigStore.user', JSON.stringify(data.user));
                    localStorage.setItem('bigStore.jwt', data.token);
                    if(localStorage.getItem('bigStore.jwt') != null) {
                        this.$emit('loggedIn');
                        let nextUrl = this.$route.params.nextUrl;
                        this.$router.push((nextUrl != null ? nextUrl : '/'))

                        Toast.fire({
                            icon: 'success',
                            title: 'You are now registered'
                        })
                    }
                    this.form.reset();
                }).catch((err) => {
                    if(this.form.password !== this.form.c_password) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Passwords do not match'
                        })
                    } 
                    else {
                        if( this.form.name == '' | this.form.email == '' | this.form.phoneNumber == '' | this.form.password == '' | this.form.c_password == '') {
                            Toast.fire({
                                icon: 'error',
                                title: 'Please fill in the form'
                            })
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Oops😑 An error occured!'
                            })
                        }
                        
                    }
                    
                });
        }
    },
}
</script>