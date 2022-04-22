<template>
    <div class="container" v-if="!loading">
        <div class="actions" v-if="!matrix.locked">
            <button type="button" class="green" @click="createOperation">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                Dodaj operaciju
            </button>
        </div>
        <table v-if="operations.length">
            <tbody>
                <template v-for="operation in operations">
                    <tr class="operation-header">
                        <td>
                            {{operation.ordinal_number+'. '+operation.operation_type}}
                            <br>
                            {{operation.unit_price}}
                        </td>
                        <td class="top" v-if="!matrix.locked">
                            <context>
                                <div class="item" @click="editOperation(operation)">Uredi</div>
                                <div class="item txt-red" @click="deleteOperation(operation)">Obriši</div>
                            </context>
                        </td>
                    </tr>
                    <tr class="hover">
                        <td :colspan="!matrix.locked ? '2' : '1'">
                            Ne postoji ni jedan proces za prikaz!
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="txt-bold" v-else>Ne postoji ni jedna operacija za prikaz!</div>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            operations: [],
            loading: true
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
        editOperation(operation){
            this.$modals.push({
                box: require("./Edit").default,
                props: { operation },
            });
        },
        deleteOperation(operation){
            this.$modals.push({
                box: require("./Delete").default,
                props: { operation },
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
.actions {
    margin-bottom: 20px;
}

.operation-header {
    background: var(--green);
    color: white;
}
</style>
