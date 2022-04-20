<template>
    <modal title="Uređivanje katastarske čestice" ref="modal" size="m">
        <form method="post" @submit.prevent="editCadastralParcel">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabranu katastarsku česticu!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="number" class="required">Broj:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.number}"
                               id="name"
                               v-model="formData.number">
                    </div>
                    <div class="error" v-if="errors && errors.number && errors.number[0]">{{errors.number[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="arcode" class="required">Arkôd:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.arcode}"
                               id="arcode"
                               v-model="formData.arcode">
                    </div>
                    <div class="error" v-if="errors && errors.arcode && errors.arcode[0]">{{errors.arcode[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="area" class="required">Površina:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green w-3"
                               :class="{'invalid' : errors && errors.area}"
                               id="area"
                               v-model="formData.area">
                        <span>m<sup>2</sup></span>
                    </div>
                    <div class="error" v-if="errors && errors.area && errors.area[0]">{{errors.area[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="community" class="required">Katastarska općina:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.community}"
                               id="community"
                               v-model="formData.community">
                    </div>
                    <div class="error" v-if="errors && errors.community && errors.community[0]">{{errors.community[0]}}</div>
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
    props: ['cadastralParcel'],
    data(){
        return {
            loading: true,
            formData: {
                number: null,
                arcode: null,
                area: null,
                community: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editCadastralParcel(){
            this.loading = true;
            axios.post("/family-farm/edit-cadastral-parcel/"+this.cadastralParcel.id, this.formData).then((response) => {
                this.$root.$emit('getCadastralParcels', () => {
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
        if(this.cadastralParcel === null){
            return this.$root.$emit('error');
        }
        this.formData.number = this.cadastralParcel.number;
        this.formData.arcode = this.cadastralParcel.arcode;
        this.formData.area = this.cadastralParcel.area;
        this.formData.community = this.cadastralParcel.community;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
