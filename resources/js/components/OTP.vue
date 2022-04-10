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
                        <div class="message" v-if="message">{{message}}</div>
                        <div class="img">
                            <img src="/assets/enter-otp.svg" alt="otp">
                        </div>
                        <div class="form-section">
                            <div class="digits">
                                <input type="text" class="digit" v-model="digits.d1" ref="d1" placeholder=" " @input="enterDigit(1)">
                                <input type="text" class="digit" v-model="digits.d2" ref="d2" placeholder=" " @input="enterDigit(2)">
                                <input type="text" class="digit" v-model="digits.d3" ref="d3" placeholder=" " @input="enterDigit(3)">
                                <input type="text" class="digit" v-model="digits.d4" ref="d4" placeholder=" " @input="enterDigit(4)">
                                <input type="text" class="digit" v-model="digits.d5" ref="d5" placeholder=" " @input="enterDigit(5)">
                                <input type="text" class="digit" v-model="digits.d6" ref="d6" placeholder=" " @input="enterDigit(6)">
                            </div>
                            <div class="error txt-center" v-if="errors && errors.otp && errors.otp[0]">{{errors.otp[0]}}</div>
                        </div>
                        <div class="form-section center">
                            <button
                                type="submit"
                                class="green"
                                :disabled="loading">
                                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
                                </svg>
                                Potvrdi
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
            digits: {
                d1: null,
                d2: null,
                d3: null,
                d4: null,
                d5: null,
                d6: null,
            },
            formData: {
                otp: null
            },
            message: null,
            errors: null
        };
    },
    methods: {
        close(){
            let index = this.$modals.findIndex(f => f.id === this.$vnode.key);
            this.$modals.splice(index + 1, 1);
        },
        enterDigit(d){
            switch (d) {
                case 1:
                    this.$refs.d2.focus();
                    break;
                case 2:
                    this.$refs.d3.focus();
                    break;
                case 3:
                    this.$refs.d4.focus();
                    break;
                case 4:
                    this.$refs.d5.focus();
                    break;
                case 5:
                    this.$refs.d6.focus();
                    break;
                case 6:
                    break;
            }
        },
        submitOTP () {
            this.loading = true;
            this.formData.otp = Object.values(this.digits).join('');
            axios.post("/verify-otp", this.formData).then((response) => {
                this.resolve();
                this.close();
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.digits.d1 = null;
                this.digits.d2 = null;
                this.digits.d3 = null;
                this.digits.d4 = null;
                this.digits.d5 = null;
                this.digits.d6 = null;
                this.loading = false;
                this.$refs.d1.focus();
            });
        },
    },
    mounted(){
        this.loading = true;
        setTimeout(() => {
            this.$refs.d1.focus();
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
}

.otp-modal-title {
    font-size: 150%;
    color: var(--dark-green);
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
</style>
