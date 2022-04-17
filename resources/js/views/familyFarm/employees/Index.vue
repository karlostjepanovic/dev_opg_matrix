<template>
    <main-template title="Djelatnici" :visible="!loading">
        <template v-slot:header>
            <button type="button" class="green" @click="search">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                Dodaj djelatnika
            </button>
        </template>
        <table v-if="employees.length">
            <thead>
            <tr>
                <th>Korisnik</th>
                <th>Adresa e-pošte</th>
                <th>Telefon</th>
                <th>OIB</th>
                <th>Administrator<br>OPG-a</th>
                <th>Posljednja prijava</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="employee in employees" :key="employee.id" class="hover">
                <td>
                    <div class="txt-bold">{{ employee.firstname+' '+employee.lastname }}</div>
                    <div class="txt-small">{{ employee.username }}</div>
                </td>
                <td>{{ employee.email ? employee.email : '---' }}</td>
                <td>{{ employee.phone ? employee.phone : '---' }}</td>
                <td>{{ employee.oib }}</td>
                <td>
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-green" v-if="employee.pivot.admin_role">
                        <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                    </svg>
                    <svg style="width:16px;height:16px" viewBox="0 0 24 24" class="txt-red" v-else>
                        <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                </td>
                <td>{{ employee.last_login ? moment(employee.last_login).format('DD.MM.YYYY., HH:mm:ss') : '---'}}</td>
                <td>
                    <context>
                        <div class="item" @click="editEmployee(employee)">Uredi</div>
                        <div class="item txt-red" @click="removeEmployee(employee)">Ukloni</div>
                    </context>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="txt-bold" v-else>Trenutno ne postoji ni jedan korisnik za prikaz!</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            employees: [],
            loading: true
        }
    },
    methods: {
        getEmployees() {
            return new Promise((resolve, reject) => {
                axios.post("/family-farm/get-employees").then((response) => {
                    this.employees = response.data;
                    return resolve();
                }).catch(() => {
                    return reject();
                });
            });
        },
        search(){
            this.$modals.push({
                box: require("./Search").default,
                props: { },
            });
        },
        editEmployee(employee){
            this.$modals.push({
                box: require("./Edit").default,
                props: { employee },
            });
        },
        removeEmployee(employee){
            this.$modals.push({
                box: require("./Remove").default,
                props: { employee },
            });
        },
    },
    created() {
        this.getEmployees().then(() => {
            this.loading = false;
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
        this.$root.$off("getEmployees");
        this.$root.$on('getEmployees', resolve => {
            this.getEmployees().then(() => {
                return resolve();
            }).catch(() => {
                return this.$root.$emit('error');
            });
        });
    }
}
</script>

<style scoped>

</style>
