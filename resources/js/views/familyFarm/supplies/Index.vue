<template>
    <main-template title="Sredstva i zalihe" :visible="!loading">
        <template v-slot:header>
            <button type="button" class="green" @click="addSupply">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                Dodaj sredstvo
            </button>
        </template>
        <table v-if="supplies.length">
            <thead>
            <tr>
                <th>Sredstvo</th>
                <th width="30%">Opis</th>
                <th>Proces</th>
                <th>Raspoloživa<br>zaliha</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="supply in supplies" :key="supply.id" class="hover">
                <td class="top">
                    <div class="txt-bold">{{ supply.supply.name }}</div>
                    <div class="txt-small">{{supply.supply.manufacturer}}</div>
                </td>
                <td class="top"><pre class="txt-small">{{supply.supply.description}}</pre></td>
                <td class="top">{{supply.supply.operation_type}}</td>
                <td class="top">{{supply.available_amounts+' '+supply.supply.measure_unit}}</td>
                <td class="top">
                    <context>
                        <router-link class="item" :to="{name: 'showSupplyAmounts', params: {id: supply.id}}">Pregledaj zalihe</router-link>
                        <div class="item txt-red" @click="removeSupply(supply)">Ukloni</div>
                    </context>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="txt-bold" v-else>Trenutno ne postoji ni jedno sredstvo za prikaz!</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            supplies: [],
            loading: true
        }
    },
    methods: {
        getSupplies() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/get-supplies").then((response) => {
                    this.supplies = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        addSupply(){
            this.$modals.push({
                box: require("./Add").default,
                props: { },
            });
        },
        removeSupply(supply){
            this.$modals.push({
                box: require("./Remove").default,
                props: { supply },
            });
        },
    },
    created() {
        this.getSupplies().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    mounted() {
        this.$root.$off("getFamilyFarmSupplies");
        this.$root.$on('getFamilyFarmSupplies', resolve => {
            this.getSupplies().then(() => {
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
