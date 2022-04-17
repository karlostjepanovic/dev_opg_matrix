<template>
    <modal title="Uklanjanje djelatnika" ref="modal" size="s">
        <form method="post" @submit.prevent="removeEmployee">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section">
                <div class="text">Jeste li sigurni da želite ukloniti odabranog djelatnika?</div>
                <div class="text">
                    <div class="txt-bold">{{employee.full_name}}</div>
                    <div class="txt-small txt-gray">{{employee.username}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="red"
                    :disabled="loading">
                    Ukloni djelatnika
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Remove",
    props: ['employee'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    methods: {
        removeEmployee(){
            this.$root.$emit('verifyOTP', () => {
                this.loading = true;
                axios.post("/family-farm/remove-employee/"+this.employee.pivot.id).then((response) => {
                    this.$root.$emit('getEmployees', () => {
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
        if(this.employee === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
