<template>
    <modal title="Uređivanje bilješke" ref="modal" size="m">
        <form method="post" @submit.prevent="createFamilyFarm">
            <loading-overlay v-show="loading"></loading-overlay>
            <div class="editing"><strong>PAŽNJA:</strong> uređujete odabranu bilješku!</div>
            <div class="message" v-if="message">{{message}}</div>
            <div class="form-section row">
                <div class="form-control">
                    <label for="date" class="required">Datum:</label>
                </div>
                <div class="form-control w-7">
                    <div class="field">
                        <datepicker
                            :setClass="errors && errors.date ? 'invalid' : 'green'"
                            id="grade_date"
                            v-model="formData.date"></datepicker>
                    </div>
                    <div class="error" v-if="errors && errors.date && errors.date[0]">{{errors.date[0]}}</div>
                </div>
            </div>
            <div class="form-section">
                <div class="form-control">
                    <label for="note" class="required">Bilješka:</label>
                </div>
                <div class="form-control">
                    <div class="field">
                        <textarea class="input green"
                                  :class="{'invalid' : errors && errors.note}"
                                  id="note"
                                  rows="8"
                                  v-model="formData.note"></textarea>
                    </div>
                    <div class="error" v-if="errors && errors.note && errors.note[0]">{{errors.note[0]}}</div>
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
    props: ['note'],
    data(){
        return {
            loading: true,
            formData: {
                date: null,
                note: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        createFamilyFarm(){
            this.loading = true;
            axios.post("/family-farm/matrix/edit-note/"+this.note.id, this.formData).then((response) => {
                this.$root.$emit('getNotes', () => {
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
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
    },
    created() {
        if(this.note === null){
            return this.$root.$emit('error');
        }
        this.formData.date = this.$moment(this.note.date).format('DD.MM.YYYY.');
        this.formData.note = this.note.note;
        this.loading = false;
    }
}
</script>

<style scoped>

</style>
