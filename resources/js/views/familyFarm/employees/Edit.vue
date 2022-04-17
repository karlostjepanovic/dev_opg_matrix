<template>
    <modal title="Uređivanje djelatnika" ref="modal" size="m">
        <form method="post" @submit.prevent="editUser">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabranog djelatnika!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="firstname" class="required">Ime:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.firstname}"
                               id="firstname"
                               v-model="formData.firstname">
                    </div>
                    <div class="error" v-if="errors && errors.firstname && errors.firstname[0]">{{errors.firstname[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="lastname" class="required">Prezime:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.lastname}"
                               id="lastname"
                               v-model="formData.lastname">
                    </div>
                    <div class="error" v-if="errors && errors.lastname && errors.lastname[0]">{{errors.lastname[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="email">Adresa e-pošte:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.email}"
                               id="email"
                               v-model="formData.email">
                    </div>
                    <div class="error" v-if="errors && errors.email && errors.email[0]">{{errors.email[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="title">Telefon:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.phone}"
                               id="title"
                               v-model="formData.phone">
                    </div>
                    <div class="error" v-if="errors && errors.phone && errors.phone[0]">{{errors.phone[0]}}</div>
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
                    Spremi promjene
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Edit",
    props: ['employee'],
    data(){
        return {
            loading: true,
            formData: {
                user_id: null,
                firstname: null,
                lastname: null,
                email: null,
                phone: null,
                admin_role: false
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editUser(){
            this.loading = true;
            axios.post("/family-farm/edit-employee/"+this.employee.pivot.id, this.formData).then((response) => {
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
        if(this.employee === null){
            return this.$root.$emit('error');
        }
        this.formData.user_id = this.employee.id;
        this.formData.firstname = this.employee.firstname;
        this.formData.lastname = this.employee.lastname;
        this.formData.email = this.employee.email;
        this.formData.phone = this.employee.phone;
        this.formData.admin_role = this.employee.pivot.admin_role;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
