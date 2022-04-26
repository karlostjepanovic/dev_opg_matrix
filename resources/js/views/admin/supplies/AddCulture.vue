<template>
    <modal title="Dodavanje kulture" ref="modal" size="m">
        <form method="post" @submit.prevent="addCulture">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Sredstvo:</label>
                </div>
                <div class="form-control w-8">
                    <div class="field">{{supply.name}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="culture" class="required">Kultura:</label>
                </div>
                <div class="form-control w-8">
                    <div class="field">
                        <select class="input green"
                                :class="{'invalid' : errors && errors.culture}"
                                id="culture"
                                v-model="formData.culture">
                            <option :value="null">--- odaberite kulturu ---</option>
                            <option v-for="culture in appCultures" :key="culture.id" :value="culture.id">{{culture.name}}</option>
                        </select>
                    </div>
                    <div class="error" v-if="errors && errors.culture && errors.culture[0]">{{errors.culture[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Spremi kulturu
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "AddCulture",
    props: ['supply'],
    data(){
        return {
            loading: true,
            appCultures: [],
            formData: {
                culture: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        addCulture(){
            this.loading = true;
            axios.post("/admin/supply/"+this.supply.id+"/add-culture", this.formData).then((response) => {
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
        axios.post("/admin/get-available-cultures").then((response) => {
            this.appCultures = response.data;
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    }
}
</script>

<style scoped>

</style>
