<template>
    <modal :title="(matrix.locked ? 'Otključavanje' : 'Zaključavanje') + ' matrice'" ref="modal" size="s">
        <form method="post" @submit.prevent="lock">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite {{matrix.locked ? 'otključati' : 'zaključati'}} odabranu matricu?</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    {{ matrix.locked ? 'Otključaj' : 'Zaključaj' }} matricu
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
        lock(){
            this.loading = true;
            axios.post("/family-farm/lock-matrix").then((response) => {
                this.$matrix.refresh().then(() => {
                    this.$toast.success(response.data.success);
                    this.$refs.modal.close();
                }).catch(() => {
                    return this.$root.$emit('error');
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
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
