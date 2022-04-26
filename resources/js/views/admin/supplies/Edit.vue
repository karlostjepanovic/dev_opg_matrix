<template>
    <modal title="Uređivanje sredstva" ref="modal" size="m">
        <form method="post" @submit.prevent="editSupply">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabrano sredstvo!</div>
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
            <div class="form-section row">
                <div class="form-control">
                    <label for="active">Status aktivnosti:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <div class="form-control">
                            <label class="toggle-switch green"
                                   :class="{'invalid' : errors && errors.active}">
                                <input type="checkbox"
                                       id="active"
                                       v-model="formData.active">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="error" v-if="errors && errors.active && errors.active[0]">{{errors.active[0]}}</div>
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
    props: ['supply'],
    data(){
        return {
            loading: true,
            users: [],
            formData: {
                name: null,
                manufacturer: null,
                operation_type: null,
                measure_unit: null,
                description: null,
                active: false
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editSupply(){
            this.loading = true;
            axios.post("/admin/edit-supply/"+this.supply.id, this.formData).then((response) => {
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
        if(this.supply === null){
            return this.$root.$emit('error');
        }
        this.formData.name = this.supply.name;
        this.formData.manufacturer = this.supply.manufacturer;
        this.formData.operation_type = this.supply.operation_type;
        this.formData.measure_unit = this.supply.measure_unit;
        this.formData.description = this.supply.description;
        this.formData.active = this.supply.active;

        this.loading = false;
    }
}
</script>

<style scoped>

</style>
