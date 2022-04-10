import Vue from "vue";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from './App.vue';
import VueRouter from 'vue-router';
import { routes } from './routes';
import Moment from "moment/moment";
import {LoggedUser} from "./models/LoggedUser";
import {FamilyFarm} from "./models/FamilyFarm";

require('./bootstrap');

Vue.use(VueRouter);
const router = new VueRouter({
    mode: 'history',
    routes: routes
});
Moment.locale('hr');
Vue.prototype.$moment = Moment;

const options = {
    position: "bottom-right",
    timeout: 10000,
    closeOnClick: false,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: false,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: false,
    rtl: false
};
Vue.use(Toast, options);


let globalData = new Vue({
    data: {
        $modals: [],
        $loading: true,
        $loggedUser: new LoggedUser(),
        $familyFarm: new FamilyFarm(),
        $availableFamilyFarms: [],
        /*$matrix: new Matrix(),
        $availableMatrices: [],*/
    }
});
Vue.mixin({
    computed: {
        $modals: {
            get: function () { return globalData.$data.$modals },
            set: function (modal) { globalData.$data.$modals.push(modal); }
        },
        $loading: {
            get: function () { return globalData.$data.$loading },
            set: function (status) { globalData.$data.$loading = status; }
        },
        $loggedUser: {
            get: function () { return globalData.$data.$loggedUser },
            set: function () { globalData.$data.$loggedUser = new LoggedUser(); }
        },
        $familyFarm: {
            get: function () { return globalData.$data.$familyFarm },
            set: function () { globalData.$data.$familyFarm = new FamilyFarm(); }
        },
        $availableFamilyFarms: {
            get: function () { return globalData.$data.$availableFamilyFarms },
            set: function (familyFarms) { globalData.$data.$availableFamilyFarms = familyFarms; }
        },
    }
});

Vue.component('main-template', require('./components/MainTemplate').default);
Vue.component('otp', require('./components/OTP').default);
Vue.component('modal', require('./components/Modal').default);
Vue.component('context', require('./components/Context').default);
Vue.component('multiselect', require('./components/MultiSelect').default);
Vue.component('autocomplete', require('./components/Autocomplete').default);
Vue.component('loading-overlay', require('./components/LoadingOverlay').default);
const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});
