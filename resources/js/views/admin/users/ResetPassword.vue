<template>
    <modal title="Resetiranje lozinke" ref="modal" size="s">
        <form method="post" @submit.prevent="resetPassword">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="text">
                <div>Jeste li sigurni da želite korisniku</div>
                <div><strong>{{user.full_name}}</strong></div>
                <div>resetirati lozinku na početnu vrijednost?</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Resetiraj lozinku
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "ResetPassword",
    props: ['user'],
    data(){
        return {
            loading: true,
            message: null,
            errors: null
        }
    },
    methods: {
        resetPassword(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/admin/reset-password/"+this.user.id).then((response) => {
                    this.$root.$emit('getAppUsers', () => {
                        this.$toast.success(response.data.success);
                        this.$refs.modal.close();
                    });
                }).catch((errors) => {
                    this.message = errors.response.data.message;
                    this.errors = errors.response.data.errors;
                    this.loading = false;
                });
            });
        }
    },
    created() {
        if(this.user === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
