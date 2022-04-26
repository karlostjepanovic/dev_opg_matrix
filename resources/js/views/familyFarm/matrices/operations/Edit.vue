<template>
    <modal title="Uređivnje operacije" ref="modal" size="m">
        <form method="post" @submit.prevent="createMatrix">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabranu operaciju!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Operacija:</label>
                </div>
                <div class="form-control w-6">
                    <div class="field">{{matrixOperation.ordinal_number+'. '+matrixOperation.operation_type}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="unit_price" class="required">Jedinična cijena za prodaju:</label>
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
                    Spremi promjene
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Edit",
    props: ['matrixOperation'],
    data(){
        return {
            loading: true,
            formData: {
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
            axios.post("/family-farm/matrix/edit-operation/"+this.matrixOperation.id, this.formData).then((response) => {
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
    created() {
        if(this.matrixOperation === null){
            return this.$root.$emit('error');
        }
        this.formData.unit_price = this.matrixOperation.unit_price;
        this.formData.tracking = this.matrixOperation.tracking;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
