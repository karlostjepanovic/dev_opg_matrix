<template>
    <modal title="Brisanje sredstva" ref="modal" size="s">
        <form method="post" @submit.prevent="deleteSupply">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati odabrano sredstvo?</div>
                <div class="text">
                    <div class="txt-bold">{{supply.name}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši sredstvo
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "DeleteSupply",
    props: ['supply'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        deleteSupply(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/admin/delete-supply/"+this.supply.id).then((response) => {
                    this.$root.$emit('getAppSupplies', () => {
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
        if(this.supply === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
