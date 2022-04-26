<template>
    <modal title="Uređivanje zalihe" ref="modal" size="m">
        <form method="post" @submit.prevent="editCadastralParcel">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabranu zalihu!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Sredstvo:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <div>{{supply.supply.name}}</div>
                        <div class="txt-small">{{supply.supply.manufacturer}}</div>
                    </div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="bill_number" class="required">Broj računa:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.bill_number}"
                               id="bill_number"
                               v-model="formData.bill_number">
                    </div>
                    <div class="error" v-if="errors && errors.bill_number && errors.bill_number[0]">{{errors.bill_number[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="amount" class="required">Količina:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green w-3"
                               :class="{'invalid' : errors && errors.amount}"
                               id="amount"
                               v-model="formData.amount">
                        <span>{{this.supply.supply.measure_unit}}</span>
                    </div>
                    <div class="error" v-if="errors && errors.amount && errors.amount[0]">{{errors.amount[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="unit_price" class="required">Jedinična cijena:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green w-3"
                               :class="{'invalid' : errors && errors.unit_price}"
                               id="unit_price"
                               v-model="formData.unit_price">
                        <span>HRK / {{this.supply.supply.measure_unit}}</span>
                    </div>
                    <div class="error" v-if="errors && errors.unit_price && errors.unit_price[0]">{{errors.unit_price[0]}}</div>
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
    props: ['supply', 'amount'],
    data(){
        return {
            loading: true,
            formData: {
                bill_number: null,
                amount: null,
                unit_price: null
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editCadastralParcel(){
            this.loading = true;
            axios.post("/family-farm/supply/edit-amount/"+this.amount.id, this.formData).then((response) => {
                this.$root.$emit('getAmounts', () => {
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
        if(this.amount === null || this.supply === null){
            return this.$root.$emit('error');
        }
        this.formData.bill_number = this.amount.bill_number;
        this.formData.amount = this.amount.amount;
        this.formData.unit_price = this.amount.unit_price;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
