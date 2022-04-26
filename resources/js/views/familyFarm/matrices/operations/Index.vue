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
                            <div class="progress">
                                <div class="percentage">30 %</div>
                                <progress value="30" max="100"></progress>
                            </div>
                        </div>
                        <div class="options" v-if="!matrix.locked">
                            <context>
                                <router-link :to="{}" class="item">Dodaj proces</router-link>
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
                                <div class="item" @click="editOperation(operation)">Uredi</div>
                                <div class="item txt-red" @click="deleteOperation(operation)">Obriši</div>
                            </context>
                        </div>
                    </div>
                    <div class="operation-body">
                        <div class="txt-bold">Ne postoji ni jedan proces za prikaz!</div>
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
        deleteOperation(matrixOperation){
            this.$modals.push({
                box: require("./Delete").default,
                props: { matrixOperation },
            });
        },
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
</style>
