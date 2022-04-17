<template>
    <modal title="Dodavanje novog sredstva" ref="modal" size="m">
        <form method="post" @submit.prevent="createFamilyFarm">
            <loading-overlay v-show="loading"></loading-overlay>
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
                    <label for="manufacturer" class="required">Proizvođač:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.manufacturer}"
                               id="manufacturer"
                               v-model="formData.manufacturer">
                    </div>
                    <div class="error" v-if="errors && errors.manufacturer && errors.manufacturer[0]">{{errors.manufacturer[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="operation_type" class="required">Vrsta operacije:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <select class="input green"
                                :class="{'invalid' : errors && errors.operation_type}"
                                id="operation_type"
                                v-model="formData.operation_type">
                            <option :value="null">--- odaberite vrstu operacije ---</option>
                            <option value="sjetva">sjetva</option>
                            <option value="zaštita">zaštita</option>
                            <option value="prihrana">prihrana</option>
                        </select>
                    </div>
                    <div class="error" v-if="errors && errors.operation_type && errors.operation_type[0]">{{errors.operation_type[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="measure_unit" class="required">Mjerna jedinica:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field w-3">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.measure_unit}"
                               id="measure_unit"
                               v-model="formData.measure_unit">
                    </div>
                    <div class="error" v-if="errors && errors.measure_unit && errors.measure_unit[0]">{{errors.measure_unit[0]}}</div>
                </div>
            </div>
            <div class="form-section">
                <div class="form-control">
                    <label for="description">Opis:</label>
                </div>
                <div class="form-control">
                    <div class="field">
                        <textarea class="input green"
                                  :class="{'invalid' : errors && errors.description}"
                                  id="description"
                                  rows="4"
                                  v-model="formData.description"></textarea>
                    </div>
                    <div class="error" v-if="errors && errors.description && errors.description[0]">{{errors.description[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Dodaj novo sredstvo
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
            formData: {
                name: null,
                manufacturer: null,
                operation_type: null,
                measure_unit: null,
                description: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        createFamilyFarm(){
            this.loading = true;
            axios.post("/admin/create-supply", this.formData).then((response) => {
                this.$root.$emit('getAppSupplies', () => {
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
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
