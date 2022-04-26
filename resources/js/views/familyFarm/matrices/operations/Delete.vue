<template>
    <modal title="Brisanje operacije" ref="modal" size="s">
        <form method="post" @submit.prevent="removeMatrixOperation">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati odabranu operaciju?</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši operaciju
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Delete",
    props: ['matrixOperation'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        removeMatrixOperation(){
            this.loading = true;
            axios.post("/family-farm/matrix/delete-operation/"+this.matrixOperation.id).then((response) => {
                this.$root.$emit('getOperations', () => {
                    this.$toast.success(response.data.success);
                    this.$refs.modal.close();
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
            });
        }
    },
    created() {
        if(this.matrixOperation === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
