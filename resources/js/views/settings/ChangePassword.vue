<template>
    <main-template title="Promjena lozinke">
        <div class="container">
            <div class="form-wrap">
                <form method="post" @submit.prevent="changePassword">
                    <loading-overlay v-show="loading"></loading-overlay>
                    <div class="message" v-if="message">{{message}}</div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label for="current_password" class="required">Trenutna lozinka:</label>
                        </div>
                        <div class="form-control w-5">
                            <div class="field">
                                <input type="password"
                                       class="green"
                                       :class="{'invalid' : errors && errors.current_password}"
                                       id="current_password"
                                       v-model="formData.current_password">
                            </div>
                            <div class="error" v-if="errors && errors.current_password && errors.current_password[0]">{{errors.current_password[0]}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label for="new_password" class="required">Nova lozinka:</label>
                        </div>
                        <div class="form-control w-5">
                            <div class="field">
                                <input type="password"
                                       class="green"
                                       :class="{'invalid' : errors && errors.new_password}"
                                       id="new_password"
                                       v-model="formData.new_password">
                            </div>
                            <div class="error" v-if="errors && errors.new_password && errors.new_password[0]">{{errors.new_password[0]}}</div>
                        </div>
                    </div>
                    <div class="form-section row">
                        <div class="form-control">
                            <label for="repeat_password" class="required">Nova lozinka <em>(ponovno)</em>:</label>
                        </div>
                        <div class="form-control w-5">
                            <div class="field">
                                <input type="password"
                                       class="green"
                                       :class="{'invalid' : errors && errors.repeat_password}"
                                       id="repeat_password"
                                       v-model="formData.repeat_password">
                            </div>
                            <div class="error" v-if="errors && errors.repeat_password && errors.repeat_password[0]">{{errors.repeat_password[0]}}</div>
                        </div>
                    </div>
                    <div class="form-section center">
                        <button
                            type="submit"
                            class="green"
                            :disabled="loading">
                            Promijeni lozinku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main-template>
</template>

<script>
export default {
    name: "ChangePassword",
    data(){
        return {
            loading: false,
            formData: {
                current_password: null,
                new_password: null,
                repeat_password: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        changePassword(){
            this.loading = true;
            axios.post("/change-password", this.formData).then((response) => {
                Object.keys(this.formData).forEach((i) => this.formData[i] = null);
                this.message = false;
                this.errors = false;
                this.$toast.success(response.data.success);
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
            }).finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>

<style scoped>
.container {
    width: 100%;
    padding: 5vH 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-wrap {
    width: 450px;
}
</style>
