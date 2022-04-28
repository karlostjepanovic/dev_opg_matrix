<template>
    <main-template title="Pregled zaliha" :visible="!loading">
        <template v-slot:header>
            <button type="button" class="green" @click="createAmount()">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                Dodaj zalihu
            </button>
            <router-link :to="{name: 'showSupplies'}" class="button green smooth">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,7V11H5.83L9.41,7.41L8,6L2,12L8,18L9.41,16.58L5.83,13H21V7H19Z" />
                </svg>
                Povratak
            </router-link>
        </template>
        <div class="info" v-if="supply.supply">
            <div class="txt-bold">{{supply.supply.name}}</div>
            <div class="txt-small">{{supply.supply.manufacturer}}</div>
        </div>
        <table v-if="supply.amounts.length">
            <thead>
                <tr>
                    <th>Broj računa</th>
                    <th>Količina</th>
                    <th colspan="2">Utrošeno</th>
                    <th>Preostalo</th>
                    <th>Jedinična cijena</th>
                    <th>Ukupna cijena</th>
                    <th width="20%">Posljednja izmjena</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="amount in supply.amounts"
                    :key="amount.id"
                    class="hover"
                    v-bind:class="{'not-available' : calculatePercentage(amount.used_amounts, amount.amount) === 100}">
                    <td>{{ amount.bill_number }}</td>
                    <td>{{ amount.amount + ' ' + supply.supply.measure_unit }}</td>
                    <td class="gray">{{amount.used_amounts}} {{ supply.supply.measure_unit }}</td>
                    <td class="gray">{{calculatePercentage(amount.used_amounts, amount.amount)}} %</td>
                    <td>{{ amount.available_amounts + ' ' + supply.supply.measure_unit }}</td>
                    <td>{{ amount.unit_price }} HRK / {{ supply.supply.measure_unit }}</td>
                    <td>{{ Math.round(amount.unit_price * amount.amount) }} HRK</td>
                    <td class="top txt-small">
                        {{amount.user.firstname + ' ' + amount.user.lastname}}
                        <br>
                        ({{moment(amount.updated_at).format('DD.MM.YYYY., H:mm:ss')}})
                    </td>
                    <td class="top">
                        <context>
                            <div class="item" @click="editAmount(amount)">Uredi</div>
                            <div class="item txt-red" @click="deleteAmount(amount)">Obriši</div>
                        </context>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="txt-bold" v-else>Trenutno ne postoje zalihe za prikaz!</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            supply: {
                amounts: []
            },
            loading: true
        }
    },
    methods: {
        getAmounts() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/supply/"+this.$route.params.id+"/get-amounts").then((response) => {
                    this.supply.amounts = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createAmount(){
            this.$modals.push({
                box: require("./Create").default,
                props: { supply: this.supply },
            });
        },
        editAmount(amount){
            this.$modals.push({
                box: require("./Edit").default,
                props: { supply: this.supply, amount },
            });
        },
        deleteAmount(amount){
            this.$modals.push({
                box: require("./Delete").default,
                props: { supply: this.supply, amount },
            });
        },
        calculatePercentage(a, b){
            return Math.round((a*100)/b)
        }
    },
    created() {
        axios.post("/family-farm/supply/"+this.$route.params.id+"/show").then((response) => {
            if(!response.data){
                return this.$root.$emit('error');
            }else{
                this.supply = response.data;
                this.loading = false;
            }
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    computed: {
        moment: function () {
            return this.$moment;
        },
    },
    mounted() {
        this.$root.$off("getAmounts");
        this.$root.$on('getAmounts', resolve => {
            this.getAmounts().then(() => {
                return resolve();
            }).catch(() => {
                return this.$root.$emit('error');
            });
        });
    }
}
</script>

<style scoped>
.info {
    text-align: center;
    margin-bottom: 20px;
}

*:not(.not-available) > .gray {
    background: #e6eced;
}

.not-available {
    background: repeating-linear-gradient(
        45deg,
        #f6f8f9,
        #f6f8f9 10px,
        #e7edee 10px,
        #e7edee 20px
    )!important;
}
</style>
