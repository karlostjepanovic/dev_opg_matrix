<template>
    <main-template title="Osobni podatci">
        <div class="container">
            <div class="form-wrap">
                <form method="post" @submit.prevent="updateProfile">
                    <loading-overlay v-show="loading"></loading-overlay>
                    <div class="message" v-if="message">{{message}}</div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label>Ime:</label>
                        </div>
                        <div class="form-control w-6">
                            <div class="field">{{loggedUser.firstname}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label>Prezime:</label>
                        </div>
                        <div class="form-control w-6">
                            <div class="field">{{loggedUser.lastname}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label>Korisničko ime:</label>
                        </div>
                        <div class="form-control w-6">
                            <div class="field">{{loggedUser.username}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label>Adresa e-pošte:</label>
                        </div>
                        <div class="form-control w-6">
                            <div class="field">{{loggedUser.email}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label for="phone">Telefon:</label>
                        </div>
                        <div class="form-control w-6">
                            <div class="field">
                                <input type="text"
                                       class="green"
                                       :class="{'invalid' : errors && errors.phone}"
                                       id="phone"
                                       v-model="formData.phone">
                            </div>
                            <div class="error" v-if="errors && errors.phone && errors.phone[0]">{{errors.phone[0]}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label>Veličina slova:</label>
                        </div>
                        <div class="form-control w-6">
                            <div class="field">
                                <div class="font-size-wrap large">
                                    <input type="radio"
                                           class="green"
                                           value="l"
                                           id="large"
                                           v-model="formData.font_size">
                                    <div class="form-control">
                                        <label for="large" class="label">Velika slova</label>
                                    </div>
                                </div>
                                <div class="font-size-wrap normal">
                                    <input type="radio"
                                           class="green"
                                           value="n"
                                           id="normal"
                                           v-model="formData.font_size">
                                    <div class="form-control">
                                        <label for="normal" class="label">Normalna slova</label>
                                    </div>
                                </div>
                                <div class="font-size-wrap small">
                                    <input type="radio"
                                           class="green"
                                           value="s"
                                           id="small"
                                           v-model="formData.font_size">
                                    <div class="form-control">
                                        <label for="small" class="label">Mala slova</label>
                                    </div>
                                </div>
                            </div>
                            <div class="error" v-if="errors && errors.font_size && errors.font_size[0]">{{errors.font_size[0]}}</div>
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
            </div>
        </div>
    </main-template>
</template>

<script>
export default {
    name: "Profile",
    data(){
        return {
            loading: true,
            formData: {
                phone: null,
                font_size: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        updateProfile(){
            this.loading = true;
            axios.post("/update-profile", this.formData).then((response) => {
                this.$loggedUser.refresh().then(() => {
                    this.message = false;
                    this.errors = false;
                    this.$toast.success(response.data.success);
                }).catch(() => {
                    return window.location.href = '/';
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
            }).finally(() => {
                this.loading = false;
            });
        }
    },
    mounted() {
        this.$loggedUser.refresh().then((response) => {
            this.formData.phone = response.phone;
            this.formData.font_size = response.fontSize;
            this.loading = false;
        }).catch(() => {
            return window.location.href = '/';
        });
    },
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
    }
}
</script>

<style scoped>
.container {
    margin: 0 auto;
}

@media screen and (max-width: 1674px) {
    .container {
        width: calc(50% - 60px);
    }
}

@media screen and (max-width: 1324px) {
    .container {
        width: calc(60% - 30px);
    }
}

@media screen and (max-width: 1068px) {
    .container {
        width: calc(80% - 10px);
    }
}

.container > *:not(:last-child) {
    margin-bottom: 20px;
}

.form-wrap {
    width: 100%;
}

.form-section {
    justify-content: flex-end;
}

.font-size-wrap {
    display: flex;
    align-items: center;
}

.font-size-wrap .label {
    margin-left: 10px;
}

.font-size-wrap.large .label { font-size: 16px!important; }
.font-size-wrap.normal .label { font-size: 14px!important; }
.font-size-wrap.small .label { font-size: 12px!important; }
</style>
