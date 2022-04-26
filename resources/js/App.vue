<template>
    <div id="app"
         :class="{'fs-large' : loggedUser.fontSize === 'l', 'fs-normal' : loggedUser.fontSize === 'n', 'fs-small' : loggedUser.fontSize === 's',}">
        <loading-overlay v-show="loading"></loading-overlay>
        <Header v-if="loggedUser.id"></Header>
        <router-view v-if="loggedUser.id && !loading"></router-view>
        <template v-for="(modal, i) in modals" v-show="!loading">
            <component :is="modal.box" v-bind="modal.props" :key="i"></component>
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
            this.$loading = true;
            // TODO: dodati da se obrišu svi popup prozori
            setTimeout(() => {
                //return window.location.href = "/";
                //this.$router.push({name: 'index'}).catch(() => {});
            }, 1000);
        }
    },
    mounted(){
        this.$loggedUser.refresh().then(async (response) => {
            await this.$familyFarm.refresh().then(async (response) => {
                await this.$matrix.refresh().then(async (response) => {
                    if (response.id === null) {
                        return window.location.href = '/';
                    }
                }).catch(() => {
                    return window.location.href = '/';
                });
            }).catch(() => {
                return window.location.href = '/';
            });
        }).catch(() => {
            return this.$loggedUser.logout();
        });

        // JEDNOKRATNA LOZINKA
        this.$root.$off('verifyOTP');
        this.$root.$on('verifyOTP', (resolve) => {
            this.$modals.push({
                box: require("./components/OTP").default,
                props: { resolve },
            });
        });

        // GREŠKA
        this.$root.$off('error');
        this.$root.$on('error', () => {
            this.error();
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
