<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <slot name="header" v-html="data.name"></slot>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        Name: <input type="text" v-model="data.name"><br>
                        <label class="mt-2" for="cat">Category:</label>
                        <select id="cat" v-model="data.category">
                            <option value="fruit">Fruit</option>
                            <option value="vegetable">Vegetable</option>
                        </select><br>
                        Units: <br><input type="text" v-model="data.units"><br>
                        Price: <br><input type="text" v-model="data.price"><br><br>
                        <textarea v-model="data.description" placeholder="description"></textarea>
                        <span class="mt-2">
                            <!-- <img :src="data.image" v-show="data.image != null" class="img-fluid rounded"> -->
                            Image:
                            <input type="file" id="file" @change="attachImg">
                        </span>
                        <span class="mt-4">
                            Certificate:
                            <input type="file" id="file" @change="attachPdf">
                        </span>
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer-left" class="float-left">
                        <button class="modal-default-button" @click="closeIt">
                            Close
                        </button>
                    </slot>
                    <slot name="footer-right">
                        <button class="modal-default-button" @click.prevent="uploadFile">
                            Finish
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['product'],
    data() {
        return {
            attachment_img: null,
            attachment_pdf: null,
        }
    },
    computed: {
        data: function() {
            if(this.product != null) {
                return this.product;
            }
            else {
                return {
                    name: "",
                    units: "",
                    price: "",
                    description: "",
                    image: false,
                    category: "",
                    certificate: ""
                }
            }
        }
    },
    // mounted() {
    //     console.log(this.product);
    // },
    methods: {
        attachImg(event) {
            this.attachment_img = event.target.files[0];
            console.log(this.attachment_img);
        },
        attachPdf(event) {
            this.attachment_pdf = event.target.files[0];
            console.log(this.attachment_pdf);

        },
        uploadImage() {
            var formData = new FormData();
            formData.append("image", this.attachment_img)
            axios.post("/api/upload-image", formData, {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                this.product.image = response.data;
                console.log("Image uploaded!")
                this.uploadCertificate();
                this.$emit('close', this.product)
            })
            .catch(err => {
                console.log("Error uploading image: ", err)
            });
        },
        uploadCertificate() {
            var formData = new FormData();
            formData.append("certificate", this.attachment_pdf)
            axios.post("/api/upload-pdf", formData, {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                // console.log('Cert data:' ,response.data);
                this.product.certificate = response.data;
                console.log("Cert uploaded!")
                // this.$emit('close', this.product)
            })
            .catch((err) => {
                console.log("Error uploading cert: ",err);
            })
        },
        uploadFile(event){
        if(this.attachment_img != null){
            this.uploadImage();
        }
      },
      closeIt() {
          this.$emit('close', this.product);
      }
    },
}
</script>

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }
    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
    .modal-container {
        width: 300px;
        margin: 0px auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }
    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }
    .modal-body {
        margin: 20px 0;
    }
    .modal-default-button {
        float: right;
    }
    .modal-enter {
        opacity: 0;
    }
    .modal-leave-active {
        opacity: 0;
    }
    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>