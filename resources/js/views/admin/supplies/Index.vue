<template>
    <div class="container">
        <loading-overlay v-show="loading"></loading-overlay>
        <template v-if="!loading">
            <div class="section">
                <router-link class="button green" :to="{name: 'createSupply'}">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                    Dodaj sredstvo
                </router-link>
            </div>
            <table v-if="supplies.length">
                <thead>
                    <tr>
                        <th>Sredstvo</th>
                        <th>Status</th>
                        <th width="30%">Opis</th>
                        <th>Mjerna jedinica</th>
                        <th width="35%">Kulture</th>
                        <th>Operacija</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="supply in supplies" :key="supply.id" class="hover">
                    <td class="top">
                        <div class="txt-bold">{{ supply.name }}</div>
                        <div class="txt-small">{{supply.manufacturer}}</div>
                    </td>
                    <td class="top">
                        <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-green" v-if="supply.active">
                            <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                        </svg>
                        <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-red" v-else>
                            <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                        </svg>
                    </td>
                    <td class="top"><pre class="txt-small">{{supply.description}}</pre></td>
                    <td class="top">{{supply.measure_unit}}</td>
                    <td class="top">
                        <div class="cultures-container">
                            <template v-for="culture in supply.cultures">
                                <div class="culture-wrap">
                                    <div class="culture-name">{{culture.name}}</div>
                                    <div class="remove" @click="removeCulture(culture)">
                                        <svg style="width:16px;height:16px" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                        </svg>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </td>
                    <td class="top">{{supply.operation_type}}</td>
                    <td class="top">
                        <context>
                            <div class="item" @click="addCulture(supply)">Dodaj kulturu</div>
                            <div class="item" @click="editSupply(supply)">Uredi</div>
                            <div class="item txt-red" @click="deleteSupply(supply)">Obriši</div>
                        </context>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="txt-bold" v-else>Trenutno ne postoji ni jedno sredstvo za prikaz!</div>
        </template>
    </div>
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
        getAppSupplies() {
            return new Promise((resolve, reject) => {
                axios.post("/admin/get-supplies").then((response) => {
                    this.supplies = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        addCulture(supply){
            this.$modals.push({
                box: require("./AddCulture").default,
                props: { supply },
            });
        },
        removeCulture(culture){
            this.$modals.push({
                box: require("./RemoveCulture").default,
                props: { culture },
            });
        },
        editSupply(supply){
            this.$modals.push({
                box: require("./Edit").default,
                props: { supply },
            });
        },
        deleteSupply(supply){
            this.$modals.push({
                box: require("./Delete").default,
                props: { supply },
            });
        },
    },
    created() {
        this.getAppSupplies().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    mounted(){
        this.$root.$off("getAppSupplies");
        this.$root.$on('getAppSupplies', resolve => {
            this.getAppSupplies().then(() => {
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

.cultures-container {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.culture-wrap {
    display: inline-flex;
    align-items: center;
    padding: 3px 3px 3px 10px;
    width: fit-content;
    border-radius: 20px;
    border: 1px dashed var(--green);
    background: white;
    user-select: none;
}

.culture-wrap .culture-name {
    font-size: 80%;
    margin-right: 5px;
}

.culture-wrap .remove {
    color: var(--red);
    cursor: pointer;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.culture-wrap .remove:hover {
    background: var(--light-red);
}
</style>
