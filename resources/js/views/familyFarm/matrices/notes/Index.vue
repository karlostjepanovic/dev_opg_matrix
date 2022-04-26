<template>
    <div class="container">
        <loading-overlay v-show="loading"></loading-overlay>
        <template v-if="!loading">
            <div class="actions" v-if="!matrix.locked">
                <button type="button" class="green" @click="createNote">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                    Unos bilješke
                </button>
            </div>
            <table v-if="notes.length">
                <thead>
                    <tr>
                        <th width="100px">Datum</th>
                        <th>Bilješka</th>
                        <th width="20%">Posljednja izmjena</th>
                        <th width="50px" v-if="!matrix.locked"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="note in notes" :key="note.id" class="hover">
                        <td class="top">{{moment(note.date).format('DD.MM.YYYY.')}}</td>
                        <td class="top"><pre>{{ note.note }}</pre></td>
                        <td class="top txt-small">
                            {{note.user.firstname + ' ' + note.user.lastname}}
                            <br>
                            ({{moment(note.updated_at).format('DD.MM.YYYY., H:mm:ss')}})
                        </td>
                        <td class="top" v-if="!matrix.locked">
                            <context>
                                <div class="item" @click="editNote(note)">Uredi</div>
                                <div class="item txt-red" @click="deleteNote(note)">Obriši</div>
                            </context>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="txt-bold" v-else>Ne postoji ni jedna bilješka za prikaz!</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            notes: [],
            loading: true
        }
    },
    methods: {
        getNotes() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/matrix/get-notes").then((response) => {
                    this.notes = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createNote(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        editNote(note){
            this.$modals.push({
                box: require("./Edit").default,
                props: { note },
            });
        },
        deleteNote(note){
            this.$modals.push({
                box: require("./Delete").default,
                props: { note },
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
        this.getNotes().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    mounted() {
        this.$root.$off("getNotes");
        this.$root.$on('getNotes', resolve => {
            this.getNotes().then(() => {
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

.actions {
    margin-bottom: 20px;
}
</style>
