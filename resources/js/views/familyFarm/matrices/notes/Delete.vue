<template>
    <modal title="Brisanje bilješke" ref="modal" size="s">
        <form method="post" @submit.prevent="removeNote">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite obrisati odabranu bilješku?</div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Obriši bilješku
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Delete",
    props: ['note'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        removeNote(){
            this.loading = true;
            axios.post("/family-farm/matrix/delete-note/"+this.note.id).then((response) => {
                this.$root.$emit('getNotes', () => {
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
        if(this.note === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
