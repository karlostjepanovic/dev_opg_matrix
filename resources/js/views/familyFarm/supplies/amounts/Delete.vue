<template>
    <modal title="Brisanje zalihe" ref="modal" size="s">
        <form method="post" @submit.prevent="deleteAmount">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati odabranu zalihu?</div>
                <div class="text">
                    <div class="txt-bold">{{supply.supply.name}}</div>
                    <div class="txt-small">{{amount.bill_number}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši zalihu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Delete",
    props: ['supply', 'amount'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        deleteAmount(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/family-farm/supply/delete-amount/"+this.amount.id).then((response) => {
                    this.$root.$emit('getAmounts', () => {
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
        if(this.amount === null || this.supply === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
