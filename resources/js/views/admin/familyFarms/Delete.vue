<template>
    <modal title="Brisanje OPG-a" ref="modal" size="s">
        <form method="post" @submit.prevent="deleteFamilyFarm">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="text">Jeste li sigurni da želite obrisati odabrani OPG?</div>
            <div class="text">
                <div class="txt-bold">{{familyFarm.name}}</div>
                <div class="txt-small txt-gray">{{'('+familyFarm.place+')'}}</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši OPG
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Remove",
    props: ['familyFarm'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        deleteFamilyFarm(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/admin/delete-family-farm/"+this.familyFarm.id).then((response) => {
                    this.$root.$emit('getAppFamilyFarms', () => {
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
        if(this.familyFarm === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
