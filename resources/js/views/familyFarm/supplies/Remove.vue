<template>
    <modal title="Uklanjanje sredstva" ref="modal" size="s">
        <form method="post" @submit.prevent="removeSupply">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite ukloniti odabrano sredstvo?</div>
                <div class="text">
                    <div class="txt-bold">{{supply.supply.name}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Ukloni sredstvo
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Remove",
    props: ['supply'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        removeSupply(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/family-farm/remove-supply/"+this.supply.id).then((response) => {
                    this.$root.$emit('getFamilyFarmSupplies', () => {
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
