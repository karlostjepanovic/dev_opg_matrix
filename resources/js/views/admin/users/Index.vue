<template>
    <div class="container">
        <loading-overlay v-show="loading"></loading-overlay>
        <template v-if="!loading">
            <div class="section">
                <button type="button" class="green" @click="createUser">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                    Kreiraj korisnika
                </button>
            </div>
            <table v-if="users.length">
                <thead>
                    <tr>
                        <th>Korisnik</th>
                        <th>Adresa e-pošte</th>
                        <th>Telefon</th>
                        <th>OIB</th>
                        <th>Administrator<br>sustava</th>
                        <th>Token</th>
                        <th>Posljednja prijava</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="hover">
                        <td>
                            <div class="txt-bold">{{ user.firstname+' '+user.lastname }}</div>
                            <div class="txt-small">{{ user.username }}</div>
                        </td>
                        <td>{{ user.email ? user.email : '---' }}</td>
                        <td>{{ user.phone ? user.phone : '---' }}</td>
                        <td>{{ user.oib }}</td>
                        <td>
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-green" v-if="user.admin_role">
                                <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                            </svg>
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-red" v-else>
                                <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                            </svg>
                        </td>
                        <td>
                            <template v-if="user.otp_token">
                                <div v-bind:class="{'txt-bold' : user.last_otp}">{{ user.otp_token }}</div>
                                <div class="txt-small" v-if="user.last_otp">({{ moment(user.last_otp).format('DD.MM.YYYY., HH:mm:ss') }})</div>
                            </template>
                            <template v-else>---</template>
                        </td>
                        <td>{{ user.last_login ? moment(user.last_login).format('DD.MM.YYYY., HH:mm:ss') : '---'}}</td>
                        <td>
                            <context>
                                <div class="item" @click="setToken(user)" v-if="user.otp_token === null">Dodijeli token</div>
                                <div class="item" @click="removeToken(user)" v-else>Ukloni token</div>
                                <div class="item" @click="resetPassword(user)">Resetiraj lozinku</div>
                                <div class="item" @click="editUser(user)">Uredi</div>
                                <div class="item txt-red" @click="deleteUser(user)">Obriši</div>
                            </context>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="txt-bold" v-else>Trenutno ne postoji ni jedan korisnik za prikaz!</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            users: [],
            loading: true
        }
    },
    methods: {
        getAppUsers() {
            return new Promise((resolve, reject) => {
                axios.post("/admin/get-users").then((response) => {
                    this.users = response.data;
                    resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        createUser(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        setToken(user){
            this.$modals.push({
                box: require("./SetToken").default,
                props: { user },
            });
        },
        removeToken(user){
            this.$modals.push({
                box: require("./RemoveToken").default,
                props: { user },
            });
        },
        resetPassword(user){
            this.$modals.push({
                box: require("./ResetPassword").default,
                props: { user },
            });
        },
        editUser(user){
            this.$modals.push({
                box: require("./Edit").default,
                props: { user },
            });
        },
        deleteUser(user){
            this.$modals.push({
                box: require("./Delete").default,
                props: { user },
            });
        },
    },
    created() {
        this.getAppUsers().then(() => {
            this.loading = false;
        }).catch(() => {
            this.$root.$emit('error');
        });
    },
    computed: {
        moment: function () {
            return this.$moment;
        },
    },
    mounted() {
        this.$root.$on('getAppUsers', (resolve) => {
            this.getAppUsers().then(() => {
                resolve();
            }).catch(() => {
                this.$root.$emit('error');
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
</style>
