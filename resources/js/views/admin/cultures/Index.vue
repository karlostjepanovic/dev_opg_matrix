<template>
    <div class="container">
        <loading-overlay v-show="loading"></loading-overlay>
        <template v-if="!loading">
            <div class="section">
                <button type="button" class="green" @click="createCulture">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                    Kreiraj kulturu
                </button>
            </div>
            <table v-if="cultures.length">
                <thead>
                    <tr>
                        <th>Kultura</th>
                        <th width="200px">Status<br>aktivnosti</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="culture in cultures" :key="culture.id" class="hover">
                        <td>{{ culture.name }}</td>
                        <td>
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-green" v-if="culture.active">
                                <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                            </svg>
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-red" v-else>
                                <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                            </svg>
                        </td>
                        <td>
                            <context>
                                <div class="item" @click="editCulture(culture)">Uredi</div>
                                <div class="item txt-red" @click="deleteCulture(culture)">Obriši</div>
                            </context>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="txt-bold" v-else>Trenutno ne postoji ni jedna kultura za prikaz!</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            cultures: [],
            loading: true
        }
    },
    methods: {
        getAppCultures() {
            return new Promise((resolve, reject) => {
                axios.post("/admin/get-cultures").then((response) => {
                    this.cultures = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createCulture(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        editCulture(culture){
            this.$modals.push({
                box: require("./Edit").default,
                props: { culture },
            });
        },
        deleteCulture(culture){
            this.$modals.push({
                box: require("./Delete").default,
                props: { culture },
            });
        },
    },
    created() {
        this.getAppCultures().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    mounted(){
        this.$root.$off("getAppCultures");
        this.$root.$on('getAppCultures', resolve => {
            this.getAppCultures().then(() => {
                return resolve();
            }).catch(() => {
                return this.$root.$emit('error');
            });
        });
    }
}
</script>

<style scoped>
.container {
    position: relative;
    min-height: 80px;
}
</style>
