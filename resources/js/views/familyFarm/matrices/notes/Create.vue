<template>
    <modal title="Unos bilješke" ref="modal" size="m">
        <form method="post" @submit.prevent="createFamilyFarm">
            <loading-overlay v-show="loading"></loading-overlay>
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
                        <div class="note-wrap">
                            <textarea class="input green"
                                      :class="{'invalid' : errors && errors.note}"
                                      id="note"
                                      rows="8"
                                      v-model="formData.note"></textarea>
                            <div class="author">{{loggedUser.fullName}}</div>
                        </div>
                    </div>
                    <div class="error" v-if="errors && errors.note && errors.note[0]">{{errors.note[0]}}</div>
                </div>
            </div>
            <div class="form-section center">
                <button
                    type="submit"
                    class="green"
                    :disabled="loading">
                    Unesi bilješku
                </button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "Create",
    data(){
        return {
            loading: true,
            formData: {
                date: this.$moment(new Date()).format('DD.MM.YYYY.'),
                note: null,
            },
            message: null,
            errors: null
        }
    },
    methods: {
        createFamilyFarm(){
            this.loading = true;
            axios.post("/family-farm/matrix/create-note", this.formData).then((response) => {
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
    created(){
        this.loading = false;
    }
}
</script>

<style scoped>
.note-wrap {
    display: flex;
    flex-direction: column;
}

.note-wrap textarea {
    width: 100%;
    border-bottom-left-radius: unset;
    border-bottom-right-radius: unset;
    position: relative;
    z-index: 1;
}

.note-wrap .author {
    border: 1px solid var(--light-gray);
    border-top: unset;
    background: #f8f8f8;
    font-size: 90%;
    text-align: right;
    padding: 7px 5px;
    color: var(--gray);
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
}
</style>
