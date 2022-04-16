<template>
    <modal title="uklanjanje tokena" ref="modal" size="s">
        <form method="post" @submit.prevent="remove">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> ovom radnjom ćete ujedno korisniku ukloniti ulogu administratora sustava!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="text">
                <div>Jeste li sigurni da želite ukloniti token korisniku?</div>
                <div><strong>{{user.full_name}}</strong></div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Ukloni token
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "RemoveToken",
    props: ['user'],
    data(){
        return {
            loading: true,
            message: null,
            errors: null
        }
    },
    methods: {
        remove(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/admin/remove-token/"+this.user.id).then((response) => {
                    this.$root.$emit('getAppUsers', () => {
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
        if(this.user === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
