<template>
    <div class="container">
        <div class="form-wrap">
            <form method="post" @submit.prevent="createProcess">
                <loading-overlay v-show="loading"></loading-overlay>
                <div class="message" v-if="message">{{message}}</div>
                <div class="process-details">
                    <div class="left">
                        <div class="form-section row">
                            <div class="form-control">
                                <label for="date" class="required">Datum:</label>
                            </div>
                            <div class="form-control w-6">
                                <div class="field">
                                    <datepicker
                                        :setClass="errors && errors.date ? 'invalid' : 'green'"
                                        id="date"
                                        v-model="formData.date"></datepicker>
                                </div>
                                <div class="error" v-if="errors && errors.date && errors.date[0]">{{errors.date[0]}}</div>
                            </div>
                        </div>
                        <div class="form-section row">
                            <div class="form-control">
                                <label for="duration" class="required">Trajanje:</label>
                            </div>
                            <div class="form-control w-6">
                                <div class="field">
                                    <input type="text"
                                           class="green w-6"
                                           :class="{'invalid' : errors && errors.duration}"
                                           id="duration"
                                           v-model="formData.duration">
                                    <span>h</span>
                                </div>
                                <div class="error" v-if="errors && errors.duration && errors.duration[0]">{{errors.duration[0]}}</div>
                            </div>
                        </div>
                        <div class="form-section row">
                            <div class="form-control">
                                <label for="tracking_value" class="required">Obra??ena {{matrix.trackingType === 'p' ? 'povr??ina' : 'koli??ina'}}:</label>
                            </div>
                            <div class="form-control w-6">
                                <div class="field">
                                    <input type="text"
                                           class="green w-6"
                                           :class="{'invalid' : errors && errors.tracking_value}"
                                           id="tracking_value"
                                           v-model="formData.tracking_value">
                                    <span>
                                        <template v-if="matrix.trackingType === 'p'">m<sup>2</sup></template>
                                        <template v-if="matrix.trackingType === 'k'">jd.</template>
                                    </span>
                                </div>
                                <div class="error" v-if="errors && errors.tracking_value && errors.tracking_value[0]">{{errors.tracking_value[0]}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="form-section row">
                            <div class="form-control">
                                <label for="weather">Meteorolo??ko vrijeme:</label>
                            </div>
                            <div class="form-control w-6">
                                <div class="field">
                                    <input list="weather-list"
                                           class="input green"
                                           :class="{'invalid' : errors && errors.weather}"
                                           id="weather"
                                           v-model="formData.weather">
                                    <datalist id="weather-list">
                                        <option>vedro, danju sun??ano</option>
                                        <option>malo obla??no, danju sun??ano</option>
                                        <option>umjereno obla??no</option>
                                        <option>prete??no obla??no</option>
                                        <option>obla??no, ali svijetlo</option>
                                        <option>obla??no</option>
                                        <option>magla, umjereno do prete??no obla??no</option>
                                        <option>magla, nebo vedro</option>
                                        <option>magla, malo do umjereno obla??no</option>
                                        <option>maglovito</option>
                                        <option>obla??no i maglovito</option>
                                        <option>promjenljivo obla??no uz malu koli??inu ki??e</option>
                                        <option>promjenljivo obla??no uz umjerenu koli??inu ki??e</option>
                                        <option>promjenljivo obla??no uz znatnu koli??inu ki??e</option>
                                        <option>promjenljivo obla??no uz mogu??u grmljavinu</option>
                                        <option>promjenljivo obla??no uz malu koli??inu ki??e te mogu??u grmljavinu</option>
                                        <option>promjenljivo obla??no uz umjerenu koli??inu ki??e te mogu??u grmljavinu</option>
                                        <option>promjenljivo obla??no uz znatnu koli??inu ki??e te mogu??u grmljavinu</option>
                                        <option>promjenljivo obla??no uz malu koli??inu ki??e i snijega</option>
                                        <option>promjenljivo obla??no uz umjerenu koli??inu ki??e i snijega</option>
                                        <option>promjenljivo obla??no uz znatnu koli??inu ki??e i snijega</option>
                                        <option>promjenljivo obla??no uz uz malu koli??inu snijega</option>
                                        <option>promjenljivo obla??no uz snijeg</option>
                                        <option>promjenljivo obla??no uz znatnu koli??inu snijega</option>
                                        <option>promjenljivo obla??no uz snijeg te mogu??u grmljavinu</option>
                                        <option>obla??no uz malu koli??inu ki??e</option>
                                        <option>obla??no uz umjerenu koli??inu ki??e</option>
                                        <option>obla??no uz znatnu koli??inu ki??e</option>
                                        <option>obla??no uz mogu??u grmljavinu</option>
                                        <option>obla??no uz malu koli??inu ki??e te mogu??u grmljavinu</option>
                                        <option>obla??no uz umjerenu koli??inu ki??e te mogu??u grmljavinu</option>
                                        <option>obla??no uz znatnu koli??inu ki??e te mogu??u grmljavinu</option>
                                        <option>obla??no uz malu koli??inu ki??e i snijega</option>
                                        <option>obla??no uz umjerenu koli??inu ki??e i snijega</option>
                                        <option>obla??no uz znatnu koli??inu ki??e i snijega</option>
                                        <option>obla??no uz malu koli??inu snijega</option>
                                        <option>obla??no uz umjerenu koli??inu snijega</option>
                                        <option>obla??no uz znatnu koli??inu snijega</option>
                                        <option>magla, promjenjivo obla??no, uz ki??u</option>
                                        <option>magla, promjenjivo obla??no, uz snijeg</option>
                                        <option>obla??no i maglovito uz mogu??i snijeg</option>
                                        <option>obla??no i maglovito uz mogu??u ki??u</option>
                                    </datalist>
                                </div>
                                <div class="error" v-if="errors && errors.weather && errors.weather[0]">{{errors.weather[0]}}</div>
                            </div>
                        </div>
                        <div class="form-section">
                            <div class="form-control">
                                <label for="note">Bilje??ka/komentar/napomena:</label>
                            </div>
                            <div class="form-control">
                                <div class="field">
                                <textarea class="input green"
                                          :class="{'invalid' : errors && errors.note}"
                                          id="note"
                                          rows="4"
                                          v-model="formData.note"></textarea>
                                </div>
                                <div class="error" v-if="errors && errors.note && errors.note[0]">{{errors.note[0]}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="process-amounts">
                    <div class="subtitle">Kori??tena sredstva</div>
                    <template v-if="formData.amounts.length > 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>Broj ra??una</th>
                                    <th>Sredstvo</th>
                                    <th>Dostupna koli??ina</th>
                                    <th width="200px">Utro??ena koli??ina</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(amount, i) in formData.amounts" class="hover">
                                    <td>{{amount.bill_number}}</td>
                                    <td>
                                        <div class="txt-bold">{{amount.supply.name}}</div>
                                        <div class="txt-small">{{amount.supply.manufacturer}}</div>
                                    </td>
                                    <td>{{amount.available_amounts+' '+amount.supply.measure_unit}}</td>
                                    <td>
                                        <div class="form-section">
                                            <input type="text"
                                                   class="green w-5"
                                                   :class="{'invalid' : errors && errors['amounts.'+i+'.used_amount']}"
                                                   v-model="amount.used_amount">
                                            <span>{{amount.supply.measure_unit}}</span>
                                            <div class="error" v-if="errors && errors['amounts.0.used_amount'] && errors['amounts.'+i+'.used_amount']">{{errors['amounts.'+i+'.used_amount'][0]}}</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template v-else>
                        <div class="txt-bold">Ne postoje ni sredstva ni zalihe koje mo??ete koristiti za ovu operaciju!</div>
                        <p class="mb">Provjerite sredstva i stanje zaliha u OPG-u klikom na sljede??i gumb:</p>
                        <router-link :to="{name: 'showSupplies'}" class="button green smooth">Sredstva i zalihe</router-link>
                    </template>
                    <div class="error" v-if="errors && errors.amounts && errors.amounts[0]">Niste upotrijebili ni jedno sredstvo.</div>
                </div>
                <div class="buttons-wrap">
                    <button
                        type="submit"
                        class="green"
                        :disabled="loading">
                        Spremi novi proces
                    </button>
                    <router-link :to="{name: 'operations'}" class="button green smooth">Odustani</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "Create",
    data(){
        return {
            operation: null,
            formData: {
                date: this.$moment(new Date()).format('DD.MM.YYYY.'),
                duration: null,
                tracking_value: null,
                weather: null,
                note: null,
                amounts: []
            },
            message: null,
            errors: null,
            loading: true,
        }
    },
    methods: {
        createProcess(){
            this.loading = true;
            axios.post("/family-farm/matrix/operation/"+this.$route.params.id+"/create-process", this.formData).then((response) => {
                this.$toast.success(response.data.success);
                this.$router.push({name: 'operations'}).catch(() => {});
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
            });
        }
    },
    created() {
        axios.post("/family-farm/matrix/operation/"+this.$route.params.id+"/show").then((response) => {
            if(!response.data){
                return this.$root.$emit('error');
            }else{
                this.operation = response.data;
                axios.post("/family-farm/matrix/operation/"+this.$route.params.id+"/get-available-supplies").then((response) => {
                    if(!response.data){
                        return this.$root.$emit('error');
                    }else{
                        console.log(response.data);
                        response.data.forEach(supply => {
                            supply.amounts.forEach(amount => {
                                if(amount.available_amounts){
                                    this.formData.amounts.push({
                                        id: amount.id,
                                        bill_number: amount.bill_number,
                                        available_amounts: amount.available_amounts,
                                        supply: supply.supply,
                                        used_amount: null
                                    });
                                }
                            });
                        });
                        this.loading = false;
                    }
                }).catch(() => {
                    return this.$root.$emit('error');
                });
            }
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    computed: {
        moment: function () {
            return this.$moment;
        },
        matrix: function () {
            return this.$matrix;
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
.action {
    text-align: right;
    margin-bottom: 20px;
}

form {
    padding: unset;
}

.process-details {
    display: flex;
}

.process-details .left {
    flex: 0 0 40%;
}

.process-details .right {
    flex: 1;
    margin-left: 20px;
}

.process-amounts {
    margin-top: 20px;
}

.mb {
    margin-bottom: 10px;
}

.buttons-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}
</style>
