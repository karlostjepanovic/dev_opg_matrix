<template>
    <main-template title="Katastarske čestice" :visible="!loading">
        <template v-slot:header v-if="loggedUser.adminRole">
            <button type="button" class="green" @click="createCadastralParcel">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                Dodaj katastarsku česticu
            </button>
        </template>
        <div class="message yellow">
            Obratite se administratoru sustava ukoliko su podatci u tablici neispravni ili nepotpuni!
        </div>
        <table v-if="cadastralParcels.length">
            <thead>
                <tr>
                    <th>Broj</th>
                    <th>Arkôd</th>
                    <th>Površina</th>
                    <th>Katastarska općina</th>
                    <th v-if="loggedUser.adminRole"></th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="cadastralParcel in cadastralParcels" :key="cadastralParcel.id" class="hover">
                <td>{{ cadastralParcel.number }}</td>
                <td>{{ cadastralParcel.arcode }}</td>
                <td>{{ cadastralParcel.area }} m<sup>2</sup></td>
                <td>{{ cadastralParcel.community }}</td>
                <td v-if="loggedUser.adminRole">
                    <context>
                        <div class="item" @click="editCadastralParcel(cadastralParcel)">Uredi</div>
                        <div class="item txt-red" @click="deleteCadastralParcel(cadastralParcel)">Obriši</div>
                    </context>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="txt-bold" v-else>Trenutno ne postoji ni jedna katastarska čestica za prikaz!</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            cadastralParcels: [],
            loading: true
        }
    },
    methods: {
        getCadastralParcels() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/get-cadastral-parcels").then((response) => {
                    this.cadastralParcels = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createCadastralParcel(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        editCadastralParcel(cadastralParcel){
            this.$modals.push({
                box: require("./Edit").default,
                props: { cadastralParcel },
            });
        },
        deleteCadastralParcel(cadastralParcel){
            this.$modals.push({
                box: require("./Delete").default,
                props: { cadastralParcel },
            });
        },
    },
    created() {
        this.getCadastralParcels().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
    },
    mounted() {
        this.$root.$off("getCadastralParcels");
        this.$root.$on('getCadastralParcels', resolve => {
            this.getCadastralParcels().then(() => {
                return resolve();
            }).catch(() => {
                return this.$root.$emit('error');
            });
        });
    }
}
</script>

<style scoped>

</style>
