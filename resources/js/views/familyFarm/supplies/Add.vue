<template>
    <modal title="Dodavanje sredstva" ref="modal" size="m">
        <form method="post" @submit.prevent="addCulture">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="supply" class="required">Sredstvo:</label>
                </div>
                <div class="form-control w-8">
                    <div class="field">
                        <select class="input green"
                                :class="{'invalid' : errors && errors.supply}"
                                id="supply"
                                v-model="formData.supply">
                            <option :value="null">--- odaberite sredstvo ---</option>
                            <option v-for="supply in appSupplies" :key="supply.id" :value="supply.id">{{supply.name+' ('+supply.manufacturer+')'}}</option>
                        </select>
                    </div>
                    <div class="error" v-if="errors && errors.supply && errors.supply[0]">{{errors.supply[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Spremi sredstvo
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Add",
    data(){
        return {
            loading: true,
            appSupplies: [],
            formData: {
                supply: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        addCulture(){
            this.loading = true;
            axios.post("/family-farm/add-supply", this.formData).then((response) => {
                this.$root.$emit('getFamilyFarmSupplies', () => {
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
        axios.post("/admin/get-available-supplies").then((response) => {
            this.appSupplies = response.data;
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    }
}
</script>

<style scoped>

</style>
