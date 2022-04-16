<template>
    <div id="otp-modal">
        <div class="otp-modal-container" ref="otpModal">
            <div class="otp-modal-header">
                <div class="otp-modal-title">JEDNOKRATNA LOZINKA</div>
            </div>
            <div class="otp-modal-body-wrap">
                <div class="otp-modal-body">
                    <form method="post" @submit.prevent="submitOTP">
                        <loading-overlay v-show="loading"></loading-overlay>
                        <div class="form-section">
                            <div class="txt-center">Za izvršavanje radnje potrebno je upisati jednokratnu lozinku!</div>
                        </div>
                        <div class="message" v-if="message">{{message}}</div>
                        <div class="img">
                            <img src="/assets/enter-otp.svg" alt="otp">
                        </div>
                        <div class="form-section">
                            <div class="digits">
                                <input type="text"
                                       class="digit"
                                       v-for="(digit, i) in digits"
                                       v-model="digits[i]"
                                       :ref="'d'+i"
                                       placeholder=" "
                                       @input="onInput(i)"
                                       @keydown.delete="onDelete(i, $event)"
                                       maxlength="1">
                            </div>
                            <div class="error txt-center" v-if="errors && errors.otp && errors.otp[0]">{{errors.otp[0]}}</div>
                        </div>
                        <div class="form-section center">
                            <button
                                type="submit"
                                class="green otp-button"
                                :disabled="loading">
                                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                                </svg>
                                Potvrdi
                            </button>
                            <button
                                type="button"
                                class="red otp-button"
                                :disabled="loading"
                                @click="close">
                                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                </svg>
                                Odustani
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "OTP",
    props: ['resolve'],
    data() {
        return {
            loading: false,
            digits: [null, null, null, null, null, null],
            formData: {
                otp: null
            },
            message: null,
            errors: null
        };
    },
    /*
    * polja za znemenke treba staviti u polja te im pristupiti preko indeksa, a za unos i brisanje se koristi samo jedna metoda koja preko event argumenta zna koja je
    * radnja učinjena te sukladno njoj onda izvršava promjenu fokusa nad poljima za znamenke
    * */
    methods: {
        close(){
            let index = this.$modals.findIndex(f => f.id === this.$vnode.key);
            this.$modals.splice(index + 1, 1);
        },
        onInput(i){
            if(this.digits[i] !== ""){
                const element = this.$refs['d'+(i + 1)] ? this.$refs['d'+(i + 1)][0] : false;
                if(element){
                    element.focus();
                /*}else if(i === 5){
                    const element = this.$refs['d'+ i ] ? this.$refs['d'+ i][0] : false;
                    element.blur();*/
                }
            }
        },
        onDelete(i, e){
            let element;
            if(this.digits[i] === "" || this.digits[i] === null) {
                this.digits[i - 1] = null;
                element = this.$refs['d' + (i - 1)] ? this.$refs['d' + (i - 1)][0] : false;
            }else{
                this.digits[i] = null;
                element = this.$refs['d' + i]  ? this.$refs['d' + i][0] : false;
            }
            if(element){
                element.focus();
            }
        },
        submitOTP () {
            this.loading = true;
            this.formData.otp = this.digits.join('');
            axios.post("/otp/verify", this.formData).then((response) => {
                this.resolve();
                this.close();
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.digits = this.digits.map(() => {
                    return null;
                });
                this.loading = false;
                this.$refs['d0'][0].focus();
            });
        },
    },
    mounted(){
        this.loading = true;
        setTimeout(() => {
            this.$refs['d0'][0].focus();
            this.loading = false;
        }, 500);
    }
}
</script>

<style scoped>
#otp-modal {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
}

#otp-modal:before {
    position: absolute;
    content: "";
    background: #000;
    opacity: 0.3;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}

.otp-modal-container {
    position: relative;
    z-index: 1;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
    min-width: 400px;
    background: white;
    width: 450px;
    border-radius: 5px;
    overflow: hidden;
}

.otp-modal-header {
    position: relative;
    line-height: 60px;
    height: 60px;
    text-transform: uppercase;
    background: white;
    overflow: hidden;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
    background: var(--dark-green);
}

.otp-modal-title {
    font-size: 150%;
    color: white;
    margin-left: 10px;
}

.otp-modal-body-wrap {
    background: white;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    padding: 0 7px 7px 7px;
    overflow: hidden;
}

.otp-modal-body {
    padding: 14px 7px 7px 7px;
    max-height: calc(100vH - 100px);
    overflow: auto;
}

.img {
    text-align: center;
}

.img img {
    height: 250px;
}

.digits {
    display: flex;
    align-items: center;
    justify-content: center;
}

.digits .digit {
    font-size: 300%;
    color: var(--gray);
    width: 40px;
    height: 60px;
    text-align: center;
    border: 1px solid transparent;
    border-radius: unset!important;
    padding: unset!important;
    font-family: Consolas,serif;
}

.digits .digit:not(:last-child) {
    margin-right: 10px;
}

.digits .digit:not(:placeholder-shown) {
    border-bottom: 4px solid var(--green);
}

.digits .digit:placeholder-shown {
    border-bottom: 4px solid var(--light-gray);
}

.otp-modal-body::-webkit-scrollbar {
    width: 12px;
    height: 18px;
}

.otp-modal-body::-webkit-scrollbar-track {
    background-color: transparent;
}

.otp-modal-body::-webkit-scrollbar-thumb {
    border: 3px solid transparent;
    background-clip: padding-box;
    -webkit-border-radius: 10px;
    background-color: #d3d3d3;
    -webkit-box-shadow: inset -1px -1px 0 rgb(0 0 0 / 5%), inset 1px 1px 0 rgb(0 0 0 / 5%);
}

.otp-button {
    width: 120px!important;
}
</style>
