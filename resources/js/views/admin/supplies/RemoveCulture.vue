<template>
    <modal title="Uklanjanje kulture" ref="modal" size="s">
        <form method="post" @submit.prevent="removeCulture">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite ukloniti odabranu kulturu?</div>
                <div class="text">
                    <div class="txt-bold">{{culture.name}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Ukloni kulturu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "RemoveCulture",
    props: ['culture'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        removeCulture(){
            this.loading = true;
            axios.post("/admin/supply/remove-culture/"+this.culture.pivot.id).then((response) => {
                this.$root.$emit('getAppSupplies', () => {
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
        if(this.culture === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
