<template>
    <modal title="Kreiranje matrice" ref="modal" size="m">
        <form method="post" @submit.prevent="createMatrix">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="text txt-red">Molimo da pažljivo upišete podatke jer se oni naknadno neće moći
                urediti!</div>
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
                    <label for="cadastral_parcel_id" class="required">Katastarska čestica:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <select class="input green"
                                :class="{'invalid' : errors && errors.cadastral_parcel_id}"
                                id="cadastral_parcel_id"
                                v-model="formData.cadastral_parcel_id">
                            <option :value="null">--- odaberite katastarsku česticu ---</option>
                            <option v-for="cadastralParcel in cadastralParcels" :key="cadastralParcel.id" :value="cadastralParcel.id">{{cadastralParcel.full_name}}</option>
                        </select>
                    </div>
                    <div class="error" v-if="errors && errors.cadastral_parcel_id && errors.cadastral_parcel_id[0]">{{errors.cadastral_parcel_id[0]}}</div>
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
                    <label for="culture_id" class="required">Kultura:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <select class="input green"
                                :class="{'invalid' : errors && errors.culture_id}"
                                id="culture_id"
                                v-model="formData.culture_id">
                            <option :value="null">--- odaberite kulturu ---</option>
                            <option v-for="culture in cultures" :key="culture.id" :value="culture.id">{{culture.name}}</option>
                        </select>
                    </div>
                    <div class="error" v-if="errors && errors.culture_id && errors.culture_id[0]">{{errors.culture_id[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Kreiraj matricu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Create",
    data(){
        return {
            loading: true,
            cultures: [],
            cadastralParcels: [],
            formData: {
                name: null,
                area: null,
                culture_id: null,
                cadastral_parcel_id: null
            },
            message: null,
            errors: null
        }
    },
    methods: {
        createMatrix(){
            this.loading = true;
            axios.post("/family-farm/create-matrix", this.formData).then((response) => {
                this.$root.$emit('getMatrices', () => {
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
        axios.post("/family-farm/get-cultures").then((response) => {
            this.cultures = response.data;
            axios.post("/family-farm/get-cadastral-parcels").then((response) => {
                this.cadastralParcels = response.data;
                this.loading = false;
            }).catch(() => {
                return this.$root.$emit('error');
            });
        }).catch(() => {
            return this.$root.$emit('error');
        });
    }
}
</script>

<style scoped>

</style>
