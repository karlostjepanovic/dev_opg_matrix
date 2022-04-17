<template>
    <modal title="Uređivanje OPG-a" ref="modal" size="m">
        <form method="post" @submit.prevent="editFamilyFarm">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete podatke za odabrani OPG!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="name" class="required">Naziv:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.name}"
                               id="name"
                               v-model="formData.name">
                    </div>
                    <div class="error" v-if="errors && errors.name && errors.name[0]">{{errors.name[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="oib" class="required">OIB:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.oib}"
                               id="oib"
                               v-model="formData.oib">
                    </div>
                    <div class="error" v-if="errors && errors.oib && errors.oib[0]">{{errors.oib[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="owner" class="required">Vlasnik:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <select class="input green"
                                :class="{'invalid' : errors && errors.owner}"
                                id="owner"
                                v-model="formData.owner">
                            <option :value="null">--- odaberite vlasnika ---</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{user.full_name}}</option>
                        </select>
                    </div>
                    <div class="error" v-if="errors && errors.owner && errors.owner[0]">{{errors.owner[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="address" class="required">Adresa:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.address}"
                               id="address"
                               v-model="formData.address">
                    </div>
                    <div class="error" v-if="errors && errors.address && errors.address[0]">{{errors.address[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="place" class="required">Mjesto:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.place}"
                               id="place"
                               v-model="formData.place">
                    </div>
                    <div class="error" v-if="errors && errors.place && errors.place[0]">{{errors.place[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="phone">Telefon:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.phone}"
                               id="phone"
                               v-model="formData.phone">
                    </div>
                    <div class="error" v-if="errors && errors.phone && errors.phone[0]">{{errors.phone[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="email">Adresa e-pošte:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.email}"
                               id="email"
                               v-model="formData.email">
                    </div>
                    <div class="error" v-if="errors && errors.email && errors.email[0]">{{errors.email[0]}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="website">Mrežno sjedište:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <input type="text"
                               class="green"
                               :class="{'invalid' : errors && errors.website}"
                               id="website"
                               v-model="formData.website">
                    </div>
                    <div class="error" v-if="errors && errors.website && errors.website[0]">{{errors.website[0]}}</div>
                </div>
            </div>

            <div class="form-section row">
                <div class="form-control">
                    <label for="active">Status aktivnosti:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <div class="form-control">
                            <label class="toggle-switch green"
                                   :class="{'invalid' : errors && errors.active}">
                                <input type="checkbox"
                                       id="active"
                                       v-model="formData.active">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="error" v-if="errors && errors.active && errors.active[0]">{{errors.active[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Spremi promjene
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Edit",
    props: ['familyFarm'],
    data(){
        return {
            loading: true,
            users: [],
            formData: {
                name: null,
                address: null,
                place: null,
                oib: null,
                owner: null,
                phone: null,
                email: null,
                website: null,
                active: false,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        editFamilyFarm(){
            this.loading = true;
            axios.post("/admin/edit-family-farm/"+this.familyFarm.id, this.formData).then((response) => {
                this.$root.$emit('getAppFamilyFarms', () => {
                    this.$toast.success(response.data.success);
                    this.$refs.modal.close();
                });
            }).catch((errors) => {
                this.message = errors.response.data.message;
                this.errors = errors.response.data.errors;
                this.loading = false;
            });
        }
    },
    created(){
        if(this.familyFarm === null){
            return this.$root.$emit('error');
        }
        this.formData.name = this.familyFarm.name;
        this.formData.address = this.familyFarm.address;
        this.formData.place = this.familyFarm.place;
        this.formData.oib = this.familyFarm.oib;
        this.formData.owner = this.familyFarm.owner.id;
        this.formData.phone = this.familyFarm.phone;
        this.formData.email = this.familyFarm.email;
        this.formData.website = this.familyFarm.website;
        this.formData.active = this.familyFarm.active;

        axios.post("/admin/get-users").then((response) => {
            this.users = response.data;
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    }
}
</script>

<style scoped>

</style>
