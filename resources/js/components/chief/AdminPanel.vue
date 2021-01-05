<template>
    <div>
        <div class="page-wrapper default-theme sidebar-bg bg1 toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="#" @click="toggleMenu">
            <i class="fas fa-bars"></i>
        </a>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <!-- sidebar-brand  -->
                <div class="sidebar-item sidebar-brand">
                    <a href="#">PHeshop Panel</a>
                    <div id="close-sidebar" @click="toggleMenu">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <!-- sidebar-header  -->
                <div class="sidebar-item sidebar-header d-flex flex-nowrap">
                    <!-- <div class="user-pic">
                        <img class="img-responsive img-rounded" src="img/user.jpg" alt="User picture">
                    </div> -->
                    <div class="user-info">
                        <span class="user-name">
                            <strong>Administrator</strong>
                        </span>
                        <!-- <span class="user-role">Administrator</span> -->
                        <span class="user-status">
                            <i class="fa fa-circle"></i>
                            <span>Online</span>
                        </span>
                    </div>
                </div>
                <!-- sidebar-search  -->
                <div class="sidebar-item sidebar-search">
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control search-menu" placeholder="Search...">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- sidebar-menu  -->
                <div class=" sidebar-item sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span>General</span>
                        </li>
                        <li class="sidebar-dropdown">
                            <router-link to="/admin/panel/overview">
                                <i class="fa fa-chart-bar"></i>
                                <span class="menu-text">Overview</span>
                                <span class="badge badge-pill badge-warning">New</span>
                            </router-link>
                        </li>
                        <li class="sidebar-dropdown">
                            <router-link to="/admin/panel/products">
                                <i class="fa fa-bars"></i>
                                <span class="menu-text">Products</span>
                                <!-- <span class="badge badge-pill badge-danger">{{products.length}}</span> -->
                            </router-link>
                        </li>
                        <li class="sidebar-dropdown">
                            <router-link to="/admin/panel/orders">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="menu-text">Orders</span>
                            </router-link>
                        </li>
                        <li class="sidebar-dropdown">
                            <router-link to="/admin/panel/users">
                                <i class="fa fa-users"></i>
                                <span class="menu-text">Users</span>
                            </router-link>
                        </li>
                        <li class="header-menu">
                            <span>Extra</span>
                        </li>
                        <li>
                            <router-link to="/admin/panel/reports">
                                <i class="fa fa-file-export"></i>
                                <span class="menu-text">Reports</span>
                                <span class="badge badge-pill badge-primary">Beta</span>
                            </router-link>
                        </li>
                        <li>
                            <a href="#" @click="logout">
                                <i class="fa fa-power-off"></i>
                                <span class="menu-text">Logout</span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>
        </nav>
        <!-- page-content  -->
        <main class="page-content pt-2">
            <div id="overlay" class="overlay"></div>
            <div class="container-fluid p-5">
                <router-view></router-view>
            </div>
        </main>
        <!-- page-content" -->
    </div>
    <!-- page-wrapper -->
    </div>
</template>

<script>
export default {
    methods: {
        toggleMenu() {
            $(".page-wrapper").toggleClass("toggled");
        },
        logout() {
            this.$router.push({name: 'admin-login'});
            //or add a question to confuze them🙄
            Toast.fire({
                icon: 'success',
                title: 'Admin is logged out'
            })
        }
    },
}
</script>

<style lang="scss" scoped>
    @import 'resources/sass/main.scss';
    @import 'resources/sass/theme.scss';
</style>