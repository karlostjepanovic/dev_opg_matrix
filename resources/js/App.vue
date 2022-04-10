<template>
    <div id="app"
         :class="{'fs-large' : loggedUser.fontSize === 'l', 'fs-normal' : loggedUser.fontSize === 'n', 'fs-small' : loggedUser.fontSize === 's',}">
        <loading-overlay v-show="loading"></loading-overlay>
        <Header v-if="loggedUser.id"></Header>
        <router-view v-if="loggedUser.id && !loading"></router-view>
        <template v-for="modal in modals" v-show="!loading">
            <component :is="modal.box" v-bind="modal.props"></component>
        </template>
    </div>
</template>

<script>
import Header from "./components/Header";
export default {
    name: "App",
    components: {Header},
    methods: {
        error() {
            this.$toast.error("Dogodila se greška!");
            this.$router.push({name: 'index'});
        }
    },
    mounted(){
        this.$loggedUser.refresh().then(async (response) => {
            if (response.id === null) {
                return window.location.href = '/';
            } else {
                this.$loading = false;
            }
        }).catch(() => {
            return this.$loggedUser.logout();
        });
        this.$root.$on('error', () => {
            this.error();
        });
        this.$root.$on('verifyOTP', (resolve) => {
            this.$modals.push({
                box: require("./components/OTP").default,
                props: { resolve },
            });
        });
    },
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
        modals: function () {
            return this.$modals;
        },
        loading: function () {
            return this.$loading;
        },
    },
}
</script>

<style scoped>
</style>
