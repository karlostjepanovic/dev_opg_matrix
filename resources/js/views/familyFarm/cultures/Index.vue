<template>
    <main-template title="Kulture" :visible="!loading">
        <div class="center">
            <form method="post" @submit.prevent="addCulture">
                <loading-overlay v-show="submitting"></loading-overlay>
                <div class="message" v-if="message">{{message}}</div>
                <div class="form-section row">
                    <div class="form-control w-f">
                        <div class="field">
                            <autocomplete
                                :items="appCultures"
                                :extraClass="{'invalid' : errors && errors.culture}"
                                v-model="formData.culture"/>
                        </div>
                        <div class="error" v-if="errors && errors.culture && errors.culture[0]">{{errors.culture[0]}}</div>
                    </div>
                    <button
                        type="submit"
                        class="green space-left"
                        :disabled="submitting">
                        <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        <table v-if="cultures.length">
            <thead>
                <tr>
                    <th>Kultura</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="culture in cultures" :key="culture.id" class="hover">
                    <td>{{ culture.name }}</td>
                    <td>
                        <context>
                            <div class="item txt-red" @click="removeCulture(culture)">Ukloni</div>
                        </context>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="txt-bold" v-else>Trenutno ne postoji ni jedna kultura za prikaz!</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            cultures: [],
            appCultures: [],
            formData: {
                culture: null,
            },
            submitting: false,
            message: null,
            errors: null,
            loading: true
        }
    },
    methods: {
        getCultures() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/get-cultures").then((response) => {
                    this.cultures = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        addCulture(){
            this.submitting = true;
            axios.post("/family-farm/add-culture", this.formData).then((response) => {
                this.getCultures().then(() => {
                    this.$toast.success(response.data.success);
                    this.message = null;
                    this.errors = null;
                    this.formData.culture = null;
                    this.submitting = false;
                }).catch(() => {
                    this.$root.$emit('error');
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.submitting = false;
            });
        },
        removeCulture(culture){
            this.$modals.push({
                box: require("./Remove").default,
                props: { culture },
            });
        },
    },
    created() {
        axios.post("/admin/get-available-cultures").then((response) => {
            response.data.forEach(culture => {
                this.appCultures.push(culture.name);
            });
            this.getCultures().then(() => {
                this.loading = false;
            }).catch(() => {
                this.$root.$emit('error');
            });
        });
    },
    computed: {
        moment: function () {
            return this.$moment;
        },
    },
    mounted() {
        this.$root.$off("getFamilyFarmCultures");
        this.$root.$on('getFamilyFarmCultures', resolve => {
            this.getCultures().then(() => {
                return resolve();
            }).catch(() => {
                return this.$root.$emit('error');
            });
        });
    }
}
</script>

<style scoped>
.center {
    margin-bottom: 20px;
}

.center form {
    min-width: 400px;
    width: calc(30vW + 10% - 50px);
    margin: 0 auto;
}
</style>
