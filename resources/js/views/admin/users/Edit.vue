<template>
    <modal title="Uređivanje korisnika" ref="modal" size="m">
        <form method="post" @submit.prevent="editUser">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke odabranog korisnika!</div>
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
                    <label for="oib" class="required">OIB:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.oib}"
                               id="oib"
                               v-model="formData.oib">
                    </div>
                    <div class="error" v-if="errors && errors.oib && errors.oib[0]">{{errors.oib[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="username" class="required">Korisničko ime:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.username}"
                               id="username"
                               v-model="formData.username">
                    </div>
                    <div class="error" v-if="errors && errors.username && errors.username[0]">{{errors.username[0]}}</div>
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
    props: ['user'],
    data(){
        return {
            loading: true,
            formData: {
                firstname: null,
                lastname: null,
                username: null,
                oib: null,
                email: null,
                phone: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editUser(){
            this.loading = true;
            axios.post("/admin/edit-user/"+this.user.id, this.formData).then((response) => {
                this.$root.$emit('getAppUsers', () => {
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
        this.formData.firstname = this.user.firstname;
        this.formData.lastname = this.user.lastname;
        this.formData.username = this.user.username;
        this.formData.oib = this.user.oib;
        this.formData.email = this.user.email;
        this.formData.phone = this.user.phone;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
