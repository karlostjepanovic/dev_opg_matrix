<template>
    <modal title="Dodavanje operacije" ref="modal" size="m">
        <form method="post" @submit.prevent="createMatrix">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="operation_type" class="required">Vrsta operacije:</label>
                </div>
                <div class="form-control w-6">
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
                    <label for="unit_price" class="required">Jedinična cijena za tržište:</label>
                </div>
                <div class="form-control w-6">
                    <div class="field">
                        <input type="text"
                               class="green w-3"
                               :class="{'invalid' : errors && errors.unit_price}"
                               id="unit_price"
                               v-model="formData.unit_price">
                        <span>
                            <template v-if="matrix.trackingType === 'p'">HRK / m<sup>2</sup></template>
                            <template v-if="matrix.trackingType === 'k'">HRK / jd.</template>
                        </span>
                    </div>
                    <div class="error" v-if="errors && errors.unit_price && errors.unit_price[0]">{{errors.unit_price[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="tracking" class="required">{{matrix.trackingType === 'p' ? 'Površina' : 'Količina'}} za praćenje:</label>
                </div>
                <div class="form-control w-6">
                    <div class="field">
                        <input type="text"
                               class="green w-3"
                               :class="{'invalid' : errors && errors.tracking}"
                               id="tracking"
                               v-model="formData.tracking">
                        <span>
                            <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                            <template v-if="matrix.trackingType === 'k'">jd.</template>
                        </span>
                    </div>
                    <div class="error" v-if="errors && errors.tracking && errors.tracking[0]">{{errors.tracking[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Dodaj operaciju
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
                operation_type: null,
                unit_price: null,
                tracking: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        createMatrix(){
            this.loading = true;
            axios.post("/family-farm/matrix/create-operation", this.formData).then((response) => {
                this.$root.$emit('getOperations', () => {
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
    computed: {
        matrix: function () {
            return this.$matrix;
        },
    },
    created(){
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
