<template>
    <modal title="" ref="modal" size="s">
        <form method="post" @submit.prevent="removeMatrixOperation">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite {{matrixOperation.ended ? 'otvoriti' : 'završiti'}} operaciju?</div>
                <div class="text" v-if="!matrixOperation.ended">Kada završite operaciju više za nju nećete moći dodavati procese.</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    {{matrixOperation.ended ? 'Otvori' : 'Završi'}} operaciju
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "End",
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
            axios.post("/family-farm/matrix/end-operation/"+this.matrixOperation.id).then((response) => {
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
