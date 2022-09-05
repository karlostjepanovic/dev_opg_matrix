<template>
    <div class="container">
        <loading-overlay v-if="loading"></loading-overlay>
        <template v-else>
            <div class="actions" v-if="!matrix.locked">
                <button type="button" class="green" @click="createOperation">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                    Dodaj operaciju
                </button>
            </div>
            <template v-if="operations.length">
                <div v-for="(operation, operationIndex) in operations" class="operation-wrap">
                    <div class="operation-header">
                        <div class="wrap">
                            <div class="info">
                                <div class="txt-bold">{{operation.ordinal_number+'. '+operation.operation_type}}</div>
                                <div class="txt-small">
                                    {{operation.unit_price}}
                                    <template v-if="matrix.trackingType === 'p'">HRK / m<sup>2</sup></template>
                                    <template v-if="matrix.trackingType === 'k'">HRK / jd.</template>
                                </div>
                            </div>
                            <div class="info">
                                <div class="txt-bold">{{matrix.trackingType === 'p' ? 'Površina' : 'Količina'}}</div>
                                <div class="txt-small">
                                    {{operation.tracking}}
                                    <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                                    <template v-if="matrix.trackingType === 'k'">jd.</template>
                                </div>
                            </div>
                            <div class="info">
                                <div class="txt-bold">Status</div>
                                <div class="txt-small">
                                    <template v-if="operation.ended">završeno</template>
                                    <span v-else class="txt-red">nije završeno</span>
                                </div>
                            </div>
                            <div class="progress txt-small">
                                <div class="percentage">
                                    <span class="txt-bold">Obrađeno: </span>
                                    {{calculatePercentage(operation.total_tracking, operation.tracking)}} %
                                </div>
                                <progress :value="operation.total_tracking" :max="operation.tracking"></progress>
                            </div>
                        </div>
                        <div class="options" v-if="!matrix.locked">
                            <context>
                                <router-link :to="{name: 'createProcess', params: {id: operation.id}}" class="item" v-if="!operation.ended">Dodaj proces</router-link>
                                <div class="item"
                                     @click="moveOperation(operation, 'up')"
                                     v-if="operationIndex > 0">
                                    Pomakni operaciju gore
                                </div>
                                <div class="item"
                                        @click="moveOperation(operation, 'down')"
                                        v-if="operationIndex < operations.length - 1">
                                    Pomakni operaciju dolje
                                </div>
                                <div class="item" @click="endOperation(operation)">{{operation.ended ? 'Otvori' : 'Završi'}} operaciju</div>
                                <div class="item" @click="editOperation(operation)">Uredi</div>
                                <div class="item txt-red" @click="deleteOperation(operation)">Obriši</div>
                            </context>
                        </div>
                    </div>
                    <div class="operation-body">
                        <template v-if="operation.processes.length > 0">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="10%">Datum</th>
                                        <th width="10%">Trajanje</th>
                                        <th width="15%">Obrađena {{matrix.trackingType === 'p' ? 'površina' : 'količina'}}</th>
                                        <th>Korištena sredstva</th>
                                        <th width="15%">Upisao</th>
                                        <th v-if="!matrix.locked"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="process in operation.processes" class="hover" :key="process.id">
                                        <td class="top">{{moment(process.date).format('DD.MM.YYYY.')}}</td>
                                        <td class="top">{{ process.duration }} h</td>
                                        <td class="top">
                                            {{ process.tracking_value }}
                                            <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                                            <template v-if="matrix.trackingType === 'k'">jd.</template>
                                        </td>
                                        <td class="top">
                                            <template v-for="(process_amount, i) in process.process_amounts">
                                                {{process_amount.amount.family_farm_supply.supply.name}}
                                                <template v-if="i + 1 < process.process_amounts.length">, </template>
                                            </template>
                                        </td>
                                        <td class="top txt-small">
                                            {{process.user.firstname + ' ' + process.user.lastname}}
                                            <br>
                                            ({{moment(process.updated_at).format('DD.MM.YYYY., H:mm:ss')}})
                                        </td>
                                        <td class="top">
                                            <context>
                                                <div class="item" @click="showDetails(matrix, process)">Detalji</div>
                                                <div class="item txt-red" @click="deleteProcess(process)" v-if="!matrix.locked">Obriši</div>
                                            </context>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <div class="txt-bold" v-else>Ne postoji ni jedan proces za prikaz!</div>
                    </div>
                </div>
            </template>
            <div class="txt-bold" v-else>Ne postoji ni jedna operacija za prikaz!</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            operations: [],
            loading: true,
        }
    },
    methods: {
        getOperations() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/matrix/get-operations").then((response) => {
                    this.operations = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createOperation(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        moveOperation(operation, direction){
            this.loading = true;
            axios.post("/family-farm/matrix/move-operation/"+operation.id+"/"+direction).then((response) => {
                this.$toast.success(response.data.success);
                this.getOperations().then(() => {
                    this.loading = false;
                }).catch(() => {
                    return this.$root.$emit('error');
                });
            }).catch((errors) => {
                this.$toast.error(errors.response.data.message);
                this.loading = false;
            });
        },
        editOperation(matrixOperation){
            this.$modals.push({
                box: require("./Edit").default,
                props: { matrixOperation },
            });
        },
        endOperation(matrixOperation){
            this.$modals.push({
                box: require("./End").default,
                props: { matrixOperation },
            });
        },
        deleteOperation(matrixOperation){
            this.$modals.push({
                box: require("./Delete").default,
                props: { matrixOperation },
            });
        },
        showDetails(matrix, process){
            this.$modals.push({
                box: require("./processes/Details").default,
                props: { matrix, process },
            });
        },
        deleteProcess(process){
            this.$modals.push({
                box: require("./processes/Delete").default,
                props: { process },
            });
        },
        calculatePercentage(a, b){
            return Math.round((a*100)/b)
        }
    },
    computed: {
        matrix: function () {
            return this.$matrix;
        },
        moment: function () {
            return this.$moment;
        },
    },
    created() {
        this.getOperations().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    mounted() {
        this.$root.$off("getOperations");
        this.$root.$on('getOperations', resolve => {
            this.getOperations().then(() => {
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

.actions,
.operation-wrap:not(:last-child) {
    margin-bottom: 20px;
}

.operation-header {
    background: var(--light-green);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    border: 1px solid var(--light-gray);
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.wrap {
    display: flex;
    align-items: flex-start;
    padding: 10px;
}

.info {
    color: var(--dark-green);
}

.info > *:first-child {
    text-transform: uppercase;
}

.wrap > *:not(:first-child) {
    margin-left: 20px;
    padding-left: 20px;
    border-left: 1px solid var(--gray);
}

.operation-body {
    border: 1px solid var(--light-gray);
    border-top: none;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    padding: 10px;
}

.options {
    padding: 10px;
}

progress {
    -webkit-appearance: none;
    appearance: none;
    height: 8px;
}
progress::-webkit-progress-value {
    -webkit-appearance: none;
    background: var(--green);
    border-radius: 10px;
}

progress::-webkit-progress-bar {
    background: var(--light-gray);
    border-radius: 10px;
}

.amount-container {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    border: 1px dashed var(--green);
    padding: 5px;
    border-radius: 4px;
    font-size: 90%;
}

.amount-container:not(:last-child) {
    margin-bottom: 5px;
}

.amount-container .used-amount {
    width: 50px;
    text-align: right;
}
</style>
