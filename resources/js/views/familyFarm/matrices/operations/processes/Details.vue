<template>
    <modal title="Detalji procesa" ref="modal" size="l">
        <loading-overlay v-show="loading"></loading-overlay>
        <div class="process-details">
            <div class="left">
                <div class="form-section row">
                    <div class="form-control">
                        <label for="date">Datum:</label>
                    </div>
                    <div class="form-control w-6">
                        <div class="field">{{moment(process.date).format('DD.MM.YYYY.')}}</div>
                    </div>
                </div>
                <div class="form-section row">
                    <div class="form-control">
                        <label for="duration">Trajanje:</label>
                    </div>
                    <div class="form-control w-6">
                        <div class="field">
                            {{process.duration}}
                            <span>h</span>
                        </div>
                    </div>
                </div>
                <div class="form-section row">
                    <div class="form-control">
                        <label for="tracking_value">Obrađena {{matrix.trackingType === 'p' ? 'površina' : 'količina'}}:</label>
                    </div>
                    <div class="form-control w-6">
                        <div class="field">
                            {{process.tracking_value}}
                            <span>
                                <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                                <template v-if="matrix.trackingType === 'k'">jd.</template>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="form-section row">
                    <div class="form-control">
                        <label for="weather">Meteorološko vrijeme:</label>
                    </div>
                    <div class="form-control w-6">
                        <div class="field">{{process.weather}}</div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-control">
                        <label for="note">Bilješka/komentar/napomena:</label>
                    </div>
                    <div class="form-control">
                        <div class="field">{{process.note}}</div>
                    </div>
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Sredstvo</th>
                    <th>Proizvođač</th>
                    <th width="200px">Utrošena količina</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="process_amount in process.process_amounts" class="hover">
                    <td>{{process_amount.amount.family_farm_supply.supply.name}}</td>
                    <td>{{process_amount.amount.family_farm_supply.supply.manufacturer}}</td>
                    <td>{{process_amount.used_amount+' '+process_amount.amount.family_farm_supply.supply.measure_unit}}</td>
                </tr>
            </tbody>
        </table>
    </modal>
</template>

<script>
export default {
    name: "Details",
    props: ['matrix', 'process'],
    data(){
        return {
            loading: false,
            message: null,
            errors: null
        }
    },
    created() {
        if(this.matrix === null || this.process === null){
            return this.$root.$emit('error');
        }
        this.loading = false;
    },
    computed: {
        moment: function () {
            return this.$moment;
        },
    }
}
</script>

<style scoped>
label {
    font-weight: bold;
}

.process-details {
    display: flex;
    margin-bottom: 20px;
    padding: 0 5px;
}

.process-details .left {
    flex: 0 0 40%;
}

.process-details .right {
    flex: 1;
    margin-left: 20px;
}
</style>
