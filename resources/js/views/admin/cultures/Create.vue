<template>
    <modal title="Kreiranje kulture" ref="modal" size="m">
        <form method="post" @submit.prevent="createCulture">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="name" class="required">Naziv:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.name}"
                               id="name"
                               v-model="formData.name">
                    </div>
                    <div class="error" v-if="errors && errors.name && errors.name[0]">{{errors.name[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Kreiraj novu kulturu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Create",
    data(){
        return {
            loading: false,
            formData: {
                name: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        createCulture(){
            this.loading = true;
            axios.post("/admin/create-culture", this.formData).then((response) => {
                this.$root.$emit('getAppCultures', () => {
                    this.$toast.success(response.data.success);
                    this.$refs.modal.close();
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
