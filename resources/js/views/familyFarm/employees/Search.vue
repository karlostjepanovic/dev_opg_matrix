<template>
    <modal title="Dodavanje djelatnika" ref="modal" size="s">
        <form method="post" @submit.prevent="search">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="oib" class="required">OIB:</label>
                </div>
                <div class="form-control w-8">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.oib}"
                               id="oib"
                               v-model="formData.oib">
                    </div>
                    <div class="error" v-if="errors && errors.oib && errors.oib[0]">{{errors.oib[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Pretraži korisnika
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    oib: "Search",
    data(){
        return {
            loading: false,
            formData: {
                oib: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        search(){
            this.loading = true;
            axios.post("/search-user/oib", this.formData).then((response) => {
                if(response.data.id){
                    this.$modals.push({
                        box: require("./Add").default,
                        props: { user: response.data },
                    });
                    this.$refs.modal.close();
                }else{
                    this.message = "Korisnik s ovim OIB-om ne postoji!";
                    this.formData.oib = null;
                    this.loading = false;
                }
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
