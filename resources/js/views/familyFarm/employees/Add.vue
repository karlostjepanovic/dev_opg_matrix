<template>
    <modal title="Dodavanje djelatnika" ref="modal" size="m">
        <form method="post" @submit.prevent="save">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="text txt-red">Potvrdite dodavanje korisnika ukoliko su prikazani rezultati pretraživanja ispravni!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Korisnik:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">{{user.full_name}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Adresa e-pošte:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">{{user.email}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Korisničko ime:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">{{user.username}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="admin_role">Administrator OPG-a:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <div class="form-control">
                            <label class="toggle-switch green"
                                   :class="{'invalid' : errors && errors.admin_role}">
                                <input type="checkbox"
                                       id="admin_role"
                                       v-model="formData.admin_role">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="error" v-if="errors && errors.admin_role && errors.admin_role[0]">{{errors.admin_role[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Spremi djelatnika
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Add",
    props: ['user'],
    data(){
        return {
            loading: true,
            formData: {
                user_id: null,
                admin_role: false,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        save(){
            this.loading = true;
            axios.post("/family-farm/add-employee", this.formData).then((response) => {
                this.$root.$emit('getEmployees', () => {
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
        if(this.user === null){
            return this.$root.$emit('error');
        }
        this.formData.user_id = this.user.id;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
