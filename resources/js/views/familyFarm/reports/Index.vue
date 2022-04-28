<template>
    <main-template title="Izvještaji" :visible="!loading">
        <div class="section">
            <form method="post" @submit.prevent="getReport">
                <loading-overlay v-show="submitting"></loading-overlay>
                <div class="message" v-if="message">{{message}}</div>
                <div class="form-section row">
                    <div class="form-control w-f">
                        <div class="field">
                            <datepicker
                                :setClass="errors && errors.date ? 'invalid' : 'green'"
                                id="date"
                                v-model="formData.date"></datepicker>
                        </div>
                        <div class="error" v-if="errors && errors.date && errors.date[0]">{{errors.date[0]}}</div>
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
        <template v-if="reports.length > 0">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Matrica</th>
                    <th>Operacija</th>
                    <th>Proces</th>
                    <th>Korištena sredstva</th>
                    <th>Meteorološko vrijeme</th>
                    <th>Bilješka/napomena</th>
                    <th>Vrijeme unosa</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(process, i) in reports" class="hover">
                    <td class="top">{{process.operation.matrix.name}}</td>
                    <td class="top">{{process.operation.ordinal_number+'. '+process.operation.operation_type}}</td>
                    <td class="top">
                        <div class="txt-bold">{{moment(process.date).format('DD.MM.YYYY.')}}</div>
                        <div class="txt-small">
                            <span class="txt-bold">trajanje: </span>
                            {{ process.duration }} h
                        </div>
                        <div class="txt-small">
                            <span class="txt-bold">obrađena {{process.operation.matrix.trackingType === 'p' ? 'površina' : 'količina'}}: </span>
                            {{ process.tracking_value }}
                            <template v-if="process.operation.matrix.trackingType === 'p'">m<sup>2</sup></template>
                            <template v-if="process.operation.matrix.trackingType === 'k'">jd.</template>
                        </div>
                    </td>
                    <td class="top">
                        <template v-for="process_amount in process.process_amounts">
                            <div class="amount-container">
                                <div class="supply">
                                    <div class="txt-bold">{{process_amount.amount.family_farm_supply.supply.name}}</div>
                                    <div>{{process_amount.amount.family_farm_supply.supply.manufacturer}}</div>
                                </div>
                                <div class="used-amount">
                                    {{process_amount.used_amount+' '+process_amount.amount.family_farm_supply.supply.measure_unit}}
                                </div>
                            </div>
                        </template>
                    </td>
                    <td class="top">{{ process.weather }}</td>
                    <td class="top">
                        <pre class="txt-small">{{ process.note }}</pre>
                    </td>
                    <td class="top txt-small">{{moment(process.updated_at).format('DD.MM.YYYY., H:mm:ss')}}</td>
                </tr>
                </tbody>
            </table>
        </template>
        <div class="txt-bold" v-else>Trenutno ne postoje podatci za prikaz!</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            reports: [],
            formData: {
                date: this.$moment(new Date()).format('DD.MM.YYYY.'),
            },
            message: null,
            errors: null,
            loading: true,
            submitting: true
        }
    },
    methods: {
        getReport(){
            this.submitting = true;
            axios.post("/family-farm/reports/generate", this.formData).then((response) => {
                this.reports = response.data;
            }).catch((errors) => {
                this.$toast.error(errors.response.data.message);
                this.errors = errors.response.data.errors;
            }).finally(() => {
                this.loading = false;
                this.submitting = false;
            });
        },
    },
    computed: {
        moment: function () {
            return this.$moment;
        },
    },
    created(){
        this.getReport();
    }
}
</script>

<style scoped>
.section {
    margin-bottom: 20px;
}

.section form {
    min-width: 400px;
    width: calc(25vW - 50px);
    margin: 0 auto;
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
