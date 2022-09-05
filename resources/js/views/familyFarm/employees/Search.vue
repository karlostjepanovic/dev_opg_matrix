<template>
    <modal title="Dodavanje djelatnika" ref="modal" size="s">
        <form method="post" @submit.prevent="search">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="form-control">
                    <label for="username" class="required">Korisničko ime:</label>
                </div>
                <div class="form-control">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.username}"
                               id="username"
                               v-model="formData.username">
                    </div>
                    <div class="error" v-if="errors && errors.username && errors.username[0]">{{errors.username[0]}}</div>
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
    username: "Search",
    data(){
        return {
            loading: false,
            formData: {
                username: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        search(){
            this.loading = true;
            axios.post("/search-user/username", this.formData).then((response) => {
                if(response.data.id){
                    this.$modals.push({
                        box: require("./Add").default,
                        props: { user: response.data },
                    });
                    this.$refs.modal.close();
                }else{
                    this.message = "Korisnik s ovim korisničkim imenom ne postoji!";
                    this.formData.username = null;
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
