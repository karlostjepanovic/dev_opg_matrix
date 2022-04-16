<template>
    <modal title="Dodjeljivanje tokena" ref="modal" size="m">
        <form method="post" @submit.prevent="save">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> ovom radnjom ćete ujedno korisnika proglasiti administratorom sustava!</div>
            <div class="container">
                <div class="qr w-3">
                    <div class="text">
                        <div><strong>Tajni ključ</strong></div>
                        <div>{{token.code}}</div>
                    </div>
                    <div class="text">
                        <img :src="token.qr" alt="qr" height="150">
                    </div>
                </div>
                <div class="form-wrap w-7">
                    <div class="message" v-if="message">{{message}}</div>
                    <div class="text">
                        <div>Za postavljanje tokena potrebno je instalirati <strong>Google Autentifikator</strong> aplikaciju na mobilni uređaj.</div>
                    </div>
                    <div class="text">
                        <div>Pokrenite aplikaciju <strong>Google Autentifikator</strong> i zatim skenirajte <em>QR</em> kôd ili upišite tajni ključ za dodavanje tokena. Nakon aktivacije, upišite jednokratnu lozinku koju je generirao token.</div>
                    </div>
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
                            <label for="otp">Lozinka:</label>
                        </div>
                        <div class="form-control w-7">
                            <div class="field">
                                <input type="password"
                                       class="green"
                                       :class="{'invalid' : errors && errors.otp}"
                                       id="otp"
                                       v-model="formData.otp">
                            </div>
                            <div class="error" v-if="errors && errors.otp && errors.otp[0]">{{errors.otp[0]}}</div>
                        </div>
                    </div>
                    <div class="form-section center">
                        <button
                            type="submit"
                            class="green"
                            :disabled="loading">
                            Dodijeli token
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "SetToken",
    props: ['user'],
    data(){
        return {
            loading: true,
            token: {
                qr: null,
                code: null
            },
            formData: {
                otp: null
            },
            message: null,
            errors: null
        }
    },
    methods: {
        save() {
            this.loading = true;
            axios.post("/otp/check", {
                code: this.token.code,
                otp: this.formData.otp,
            }).then((response) => {
                axios.post("/admin/set-token/"+this.user.id, {
                    code: this.token.code
                }).then((response) => {
                    this.$root.$emit('getAppUsers', () => {
                        this.$toast.success(response.data.success);
                        this.$refs.modal.close();
                    });
                }).catch((errors) => {
                    this.message = errors.response.data.message;
                    this.errors = errors.response.data.errors;
                    this.loading = false;
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.formData.otp = null;
                this.loading = false;
            });
        }
    },
    created() {
        if(this.user === null){
            return this.$root.$emit('error');
        }
        axios.post("/otp/generate").then((response) => {
            this.token = response.data;
            const myImage = new Image();
            myImage.src = this.token.qr;
            myImage.onload = () => {
                this.loading = false;
            }
        }).catch(() => {
            return this.$root.$emit('error');
        });
    }
}
</script>

<style scoped>
.container {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}

.container .qr {
}

.container .form-wrap {
    padding-left: 50px;
}
</style>
