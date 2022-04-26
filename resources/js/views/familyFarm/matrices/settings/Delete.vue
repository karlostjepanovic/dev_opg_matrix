<template>
    <modal title="Brisanje matrice" ref="modal" size="s">
        <form method="post" @submit.prevent="deleteMatrix">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati matricu?</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši matricu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Delete",
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        deleteMatrix(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/family-farm/delete-matrix").then((response) => {
                    this.$toast.success(response.data.success);
                    this.$refs.modal.close();
                    this.$router.push({name: 'showMatrices'}).catch(() => {});
                }).catch((errors) => {
                    this.message = errors.response.data.message;
                    this.errors = errors.response.data.errors;
                    this.loading = false;
                });
            });
        }
    },
    computed: {
        matrix: function () {
            return this.$matrix;
        },
    },
    created() {
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
