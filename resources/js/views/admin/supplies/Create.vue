<template>
    <div class="container">
        <div class="action">
            <router-link :to="{name: 'appSupplies'}" class="button green smooth">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,7V11H5.83L9.41,7.41L8,6L2,12L8,18L9.41,16.58L5.83,13H21V7H19Z" />
                </svg>
                Odustani
            </router-link>
        </div>
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
            <div class="form-section">
                <div class="form-control">
                    <label for="culture">Kulture:</label>
                </div>
                <div class="form-control w-3">
                    <div class="field">
                        <select class="input green"
                                id="culture"
                                @change="chooseCulture"
                                v-model="culture">
                            <option :value="null">--- odaberite kulturu ---</option>
                            <option v-for="culture in appCultures"
                                    :value="culture.id"
                                    v-if="formData.cultures.filter(obj => {return obj.culture[0].id === culture.id;}).length === 0">
                                {{culture.name}}
                            </option>
                        </select>
                    </div>
                </div>
                <table class="fixed" v-if="formData.cultures.length > 0">
                    <thead>
                        <tr>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(culture, i) in formData.cultures" :key="i" class="hover">
                            <td>{{culture.culture[0].name}}</td>
                            <td class="txt-right">
                                <button type="button" class="button red" @click="removeCulture(i)">
                                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                    </svg>
                                    Ukloni
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    </div>
</template>

<script>
export default {
    name: "Create",
    data(){
        return {
            loading: true,
            appCultures: [],
            culture: null,
            formData: {
                name: null,
                manufacturer: null,
                operation_type: null,
                measure_unit: null,
                description: null,
                cultures: []
            },
            message: null,
            errors: null
        }
    },
    methods: {
        chooseCulture(){
            let obj = this.culture;
            obj = this.appCultures.filter(user => {
                return user.id === obj;
            }) || false;
            if(obj !== false){
                this.formData.cultures.push({
                    culture: obj,
                });
            }
            this.culture = null;
        },
        removeCulture(i){
            this.formData.cultures.splice(i, 1);
        },
        createFamilyFarm(){
            this.loading = true;
            axios.post("/admin/create-supply", this.formData).then((response) => {
                this.$toast.success(response.data.success);
                this.$router.push({name: 'appSupplies'}).catch(() => {});
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
            });
        }
    },
    created(){
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
.action {
    text-align: right;
    margin-bottom: 20px;
}

form {
    padding: unset;
}
</style>
