<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-2">
                <img
                    :src="product.image"
                    :alt="product.name"
                    class="img-fluid rounded"
                />
                <h3 class="title" v-html="product.name"></h3>
                <p class="text-muted">{{ product.description }}</p>
                <h4>
                    <span class="small-text text-muted float-left"
                        >RF {{ product.price }}</span
                    >
                    <span class="small-text float-right"
                        >Available quantity: {{ product.units }}</span
                    >
                </h4>
                <br />
                <hr />
                <router-link
                    :to="{ path: '/checkout?pid=' + product.id }"
                    class="col-md-4 btn btn-sm btn-primary float-right"
                    >Buy Now</router-link
                >
                <div>
                    <p id="pdfLink" @click="openPdf()">
                        Open this product's Certificate
                    </p>
                </div>
            </div>
        </div>
        
        <!-- <div class="mt-3">
            <p>Seller: 
                <a href="#">{{name}}</a>
            </p>
        </div> -->
    </div>
</template>

<script>
// @ts-nocheck
export default {
    data() {
        return {
            product: [],
            showModal: false,
            name: null,
            user_type: 0,
            isLoggedIn: localStorage.getItem('bigStore.jwt') != null,
        };
    },
    //before the component is rendered
    beforeMount() {
        let url = `/api/products/${this.$route.params.id}`;
        axios.get(url)
        .then((response) => {
            this.product = response.data;
            // console.log(response.data);
            }
        );
    },
    mounted() {
        this.setDefaults();
    },
    methods: {
        openPdf() {
            //https://documents.adventistarchives.org/Books/HT1888.pdf
            let height = window.innerHeight;
            Swal.fire({
                title: '<strong>RSB Certicate</strong>',
                html:
                    // '<iframe src="https://documents.adventistarchives.org/Books/HT1888.pdf" style="width: 100%;height: 100%;border: none;"></iframe>',
                    '<center><embed type="application/pdf" src="https://documents.adventistarchives.org/Books/HT1888.pdf" width="500px" height="750px"/></center>',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                cancelButtonText:
                    '<i class="fa fa-thumbs-down"></i>',
                cancelButtonAriaLabel: 'Thumbs down'
                })
        },
        setDefaults() {
            // let user = JSON.parse(localStorage.getItem('bigStore.user'));
            // console.log(user);
            // if(user.is_admin) {
            //     this.name = user.name;
            // }
        },
    }
}
</script>


<style scoped lang="scss">
.small-text {
    font-size: 18px;
}
.title {
    font-size: 36px;
}
#pdfLink {
    cursor: pointer;
    color: blue;
    text-decoration: underline;
}
#pdf {
    width: 100%;
}

.modal-dialog {
  width: 100%;
  height: 100%;
  margin: 0 auto;
  padding: 0;
}

.modal-content {
  height: auto;
  min-height: 100%;
  border-radius: 0;
}

.modal .modal-body {
    max-height: 100%;
  overflow-y: auto;
}
</style>
