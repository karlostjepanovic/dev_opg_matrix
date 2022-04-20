<template>
    <modal title="Brisanje katastarske čestice" ref="modal" size="s">
        <form method="post" @submit.prevent="removeCadastralParcel">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati odabranu katastarsku česticu?</div>
                <div class="text">
                    <div class="txt-bold">{{cadastralParcel.arcode}}</div>
                    <div class="txt-small txt-gray">br. {{cadastralParcel.number}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši katastarsku česticu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Delete",
    props: ['cadastralParcel'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        removeCadastralParcel(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/family-farm/delete-cadastral-parcel/"+this.cadastralParcel.id).then((response) => {
                    this.$root.$emit('getCadastralParcels', () => {
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
        if(this.cadastralParcel === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
