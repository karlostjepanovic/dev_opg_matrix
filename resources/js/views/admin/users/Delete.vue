<template>
    <modal title="Brisanje korisnika" ref="modal" size="s">
        <form method="post" @submit.prevent="deleteUser">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="text">Jeste li sigurni da želite obrisati odabranog korisnika?</div>
            <div class="text">
                <div class="txt-bold">{{user.full_name}}</div>
                <div class="txt-small txt-gray">{{'('+user.username+')'}}</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši korisnika
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Remove",
    props: ['user'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        deleteUser(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/admin/delete-user/"+this.user.id).then((response) => {
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
