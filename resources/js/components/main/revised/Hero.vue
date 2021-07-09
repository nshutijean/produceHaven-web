<template>
    <div>
        <div class="jumbotron text-white">
            <div id="hero-content">
                <h1 style="font-family: Nunito, sans-serif;">
                    ProduceHaven Eshop
                </h1>
                <p style="font-family: Nunito, sans-serif;color: #ffffff;">
                    We provide you with produce-based products that have been
                    tested, approved and certified by
                    <strong>Rwanda Standards Board</strong>
                </p>
                <p>
                    <a href="https://www.rsb.gov.rw/" target="_blank" class="btn btn-success" role="button">
                        Learn about RSB<i
                            class="fas fa-arrow-right"
                            style="padding-left: 7px;"
                        ></i
                    ></a>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.jumbotron {
  background-image: url(http://www.donnatruly.com/wp-content/uploads/2015/04/11046923_10101936336403532_6827422000680841598_n.jpg);
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  border-radius: initial;
}
@media (min-width: 300px) {
  .jumbotron #hero-content {
    text-align: center;
  }
}

@media (min-width: 1200px) {
  .jumbotron #hero-content {
    text-align: left;
  }
}

/* .jumbotron p {
  max-width: 500px;
} */

.jumbotron h1 {
  font-weight: 600;
}
</style>