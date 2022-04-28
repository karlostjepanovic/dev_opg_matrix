<template>
    <modal title="Brisanje procesa" ref="modal" size="s">
        <form method="post" @submit.prevent="deleteProcess">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="editing"><strong>PAŽNJA:</strong> brisanjem procesa će sve evidentirane korištene zalihe ponovno postati slobodne za korištenje!</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati odabrani proces?</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši proces
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Delete",
    props: ['process'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        deleteProcess(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/family-farm/matrix/operation/delete-process/"+this.process.id).then((response) => {
                    this.$root.$emit('getOperations', () => {
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
        if(this.process === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
