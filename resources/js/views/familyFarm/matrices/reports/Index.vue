<template>
    <div class="container">
        <loading-overlay v-show="loading"></loading-overlay>
        <template v-if="!loading">
            <div class="subtitle">Ostvareni prihodi</div>
            <div class="message green">U tablici su prikazani svi ostvareni prihodi za operacije koju su označene kao završene.</div>
            <template v-if="incomes.length > 0">
                <table>
                    <thead>
                        <tr>
                            <th>Operacija</th>
                            <th>Cijena</th>
                            <th>Početna količina</th>
                            <th>Obrađena količina</th>
                            <th>Ostvareno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="income in incomes" :key="income.id" class="hover">
                            <td>{{income.ordinal_number+'. '+income.operation_type}}</td>
                            <td>
                                {{income.unit_price}}
                                <template v-if="matrix.trackingType === 'p'">HRK / m<sup>2</sup></template>
                                <template v-if="matrix.trackingType === 'k'">HRK / jd.</template>
                            </td>
                            <td>
                                {{income.tracking}}
                                <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                                <template v-if="matrix.trackingType === 'k'">jd.</template>
                            </td>
                            <td>
                                {{income.total_tracking}}
                                <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                                <template v-if="matrix.trackingType === 'k'">jd.</template>
                            </td>
                            <td>
                                {{Math.round(income.unit_price * income.total_tracking)}} HRK
                            </td>
                        </tr>
                        <tr>
                            <td class="txt-right txt-bold" colspan="4">UKUPNO:</td>
                            <td>{{totalIncom}} HRK</td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <div class="txt-bold" v-else>Trenutno ne postoje podatci za prikaz!</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            incomes: [],
            totalIncom: 0,
            loading: true
        }
    },
    created() {
        axios.post("/family-farm/matrix/reports/get-income").then((response) => {
            this.incomes = response.data;
            response.data.forEach(income => {
               this.totalIncom += Math.round(income.unit_price * income.total_tracking);
            });
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    computed: {
        matrix: function () {
            return this.$matrix;
        },
    }
}
</script>

<style scoped>
.container {
    position: relative;
    min-height: 80px;
}
</style>
