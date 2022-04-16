<template>
    <div class="container">
        <loading-overlay v-show="loading"></loading-overlay>
        <template v-if="!loading">
            <div class="section">
                <button type="button" class="green" @click="createFamilyFarm">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                    Kreiraj OPG
                </button>
            </div>
            <table v-if="familyFarms.length">
                <thead>
                    <tr>
                        <th>OPG</th>
                        <th>OIB</th>
                        <th>Telefon</th>
                        <th>Adresa e-pošte</th>
                        <th>Mrežno sjedište</th>
                        <th>Status<br>aktivnosti</th>
                        <th>Vlasnik</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="familyFarm in familyFarms" :key="familyFarm.id" class="hover">
                        <td>
                            <div class="txt-bold">{{ familyFarm.name }}</div>
                            <div class="txt-small">{{ familyFarm.address+', '+familyFarm.place }}</div>
                        </td>
                        <td>{{ familyFarm.oib }}</td>
                        <td>{{ familyFarm.phone ? familyFarm.phone : '---' }}</td>
                        <td>{{ familyFarm.email ? familyFarm.email : '---' }}</td>
                        <td>
                            <a :href="'http://'+familyFarm.website"
                               target="_blank"
                               v-if="familyFarm.website">
                                {{ familyFarm.website }}
                            </a>
                            <template v-else>---</template>
                        </td>
                        <td>
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-green" v-if="familyFarm.active">
                                <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                            </svg>
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-red" v-else>
                                <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                            </svg>
                        </td>
                        <td>
                            <div class="txt-bold">{{ familyFarm.owner.firstname+' '+familyFarm.owner.lastname }}</div>
                            <div class="txt-small txt-gray" v-if="familyFarm.owner.phone">{{ familyFarm.owner.phone }}</div>
                            <div class="txt-small txt-gray" v-if="familyFarm.owner.email">{{ familyFarm.owner.email }}</div>
                        </td>
                        <td>
                            <context>
                                <div class="item" @click="editFamilyFarm(familyFarm)">Uredi</div>
                                <div class="item txt-red" @click="deleteFamilyFarm(familyFarm)">Obriši</div>
                            </context>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="txt-bold" v-else>Trenutno ne postoji ni jedan OPG za prikaz!</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            familyFarms: [],
            loading: true
        }
    },
    methods: {
        getAppFamilyFarms() {
            return new Promise((resolve, reject) => {
                axios.post("/admin/get-family-farms").then((response) => {
                    this.familyFarms = response.data;
                    resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createFamilyFarm(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        editFamilyFarm(familyFarm){
            this.$modals.push({
                box: require("./Edit").default,
                props: { familyFarm },
            });
        },
        deleteFamilyFarm(familyFarm){
            this.$modals.push({
                box: require("./Delete").default,
                props: { familyFarm },
            });
        },
    },
    created() {
        this.getAppFamilyFarms().then(() => {
            this.loading = false;
        }).catch(() => {
            this.$root.$emit('error');
        });
    },
    mounted() {
        this.$root.$on('getAppFamilyFarms', (resolve) => {
            this.getAppFamilyFarms().then(() => {
                resolve();
            }).catch(() => {
                this.$root.$emit('error');
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
