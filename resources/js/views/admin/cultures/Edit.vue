<template>
    <modal title="Uređivanje kulture" ref="modal" size="m">
        <form method="post" @submit.prevent="editCulture">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabranu kulturu!</div>
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
            <div class="form-section row">
                <div class="form-control">
                    <label for="active">Status aktivnosti:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <div class="form-control">
                            <label class="toggle-switch green"
                                   :class="{'invalid' : errors && errors.active}">
                                <input type="checkbox"
                                       id="active"
                                       v-model="formData.active">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="error" v-if="errors && errors.active && errors.active[0]">{{errors.active[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Spremi promjene
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Edit",
    props: ['culture'],
    data(){
        return {
            loading: true,
            users: [],
            formData: {
                name: null,
                active: false,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editCulture(){
            this.loading = true;
            axios.post("/admin/edit-culture/"+this.culture.id, this.formData).then((response) => {
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
    },
    created(){
        if(this.culture === null){
            return this.$root.$emit('error');
        }
        this.formData.name = this.culture.name;
        this.formData.active = this.culture.active;

        this.loading = false;
    }
}
</script>

<style scoped>

</style>
