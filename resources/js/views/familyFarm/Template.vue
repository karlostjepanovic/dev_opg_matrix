<template>
    <div class="flex-container">
        <div class="sidebar">
            <div class="categories">
                <router-link :to="{name: 'setMatrix'}" :class="{'active' : this.$route.path.includes('matrix')}">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,15.39L8.24,17.66L9.23,13.38L5.91,10.5L10.29,10.13L12,6.09L13.71,10.13L18.09,10.5L14.77,13.38L15.76,17.66M22,9.24L14.81,8.63L12,2L9.19,8.63L2,9.24L7.45,13.97L5.82,21L12,17.27L18.18,21L16.54,13.97L22,9.24Z" />
                    </svg>
                    <div class="label">Matrice</div>
                </router-link>
                <router-link :to="{name: 'showEmployees'}" :class="{'active' : this.$route.path.includes('employees')}" v-if="checkRole()">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z" />
                    </svg>
                    <div class="label">Djelatnici</div>
                </router-link>
                <router-link :to="{name: ''}" :class="{'active' : this.$route.path.includes('cadastral-parcels')}" v-if="loggedUser.adminRole">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M20 2H4C2.9 2 2 2.9 2 4V20C2 21.1 2.9 22 4 22H20C21.1 22 22 21.1 22 20V4C22 2.9 21.1 2 20 2M15.3 4C14.5 5.1 14 6.5 14 8H10C10 6.4 10.8 4.9 12 4H15.3M14 14H10C10 12.5 9.5 11.1 8.7 10H12C13.2 10.9 14 12.4 14 14M4 4H9.3C8.5 5.1 8 6.5 8 8H4V4M4 10H6C7.2 10.9 8 12.3 8 14H4V10M4 20V16H9.3C8.5 17.1 8 18.5 8 20H4M10 20C10 18.4 10.8 16.9 12 16H15.3C14.5 17.1 14 18.5 14 20H10M20 20H16C16 18.4 16.8 16.9 18 16H20V20M20 14H16C16 12.5 15.5 11.1 14.7 10H20V14M20 8H16C16 6.4 16.8 4.9 18 4H20V8Z" />
                    </svg>
                    <div class="label">Katastarske čestice</div>
                </router-link>
                <router-link :to="{name: ''}" :class="{'active' : this.$route.path.includes('cultures')}" v-if="checkRole()">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M2,22V20C2,20 7,18 12,18C17,18 22,20 22,20V22H2M11.3,9.1C10.1,5.2 4,6.1 4,6.1C4,6.1 4.2,13.9 9.9,12.7C9.5,9.8 8,9 8,9C10.8,9 11,12.4 11,12.4V17C11.3,17 11.7,17 12,17C12.3,17 12.7,17 13,17V12.8C13,12.8 13,8.9 16,7.9C16,7.9 14,10.9 14,12.9C21,13.6 21,4 21,4C21,4 12.1,3 11.3,9.1Z" />
                    </svg>
                    <div class="label">Kulture</div>
                </router-link>
                <router-link :to="{name: ''}" :class="{'active' : this.$route.path.includes('supplies')}">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M3,3H21V7H3V3M4,8H20V21H4V8M9.5,11A0.5,0.5 0 0,0 9,11.5V13H15V11.5A0.5,0.5 0 0,0 14.5,11H9.5Z" />
                    </svg>
                    <div class="label">Sredstva i zalihe</div>
                </router-link>
                <router-link :to="{name: ''}" :class="{'active' : this.$route.path.includes('reports')}">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M11,2V22C5.9,21.5 2,17.2 2,12C2,6.8 5.9,2.5 11,2M13,2V11H22C21.5,6.2 17.8,2.5 13,2M13,13V22C17.7,21.5 21.5,17.8 22,13H13Z" />
                    </svg>
                    <div class="label">Izvještaji</div>
                </router-link>
            </div>
        </div>
        <div class="school-class">
            <router-view v-if="isMounted"></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: "Template",
    data(){
        return {
            isMounted: false
        }
    },
    methods: {
        checkRole(){
            return true;
            return this.$loggedUser.adminRole || this.$loggedUser.familyFarm.adminRole || (this.$familyFarm.owner.id === this.$loggedUser.id);
        }
    },
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
        familyFarm: function () {
            return this.$familyFarm;
        },
    },
    mounted() {
        this.$loggedUser.refresh().then((response) => {
            if(response.id === null || response.id === undefined){
                return window.location.href = '/';
            }else{
                this.$familyFarm.refresh().then((response) => {
                    if(response.id === null || response.id === undefined){
                        return window.location.href = '/';
                    }else{
                        this.isMounted = true;
                    }
                }).catch(() => {
                    return window.location.href = '/';
                });
            }
        }).catch(() => {
            return window.location.href = '/';
        });
    },
}
</script>

<style scoped>
.flex-container {
    display: flex;
    align-items: flex-start;
}

.sidebar {
    position: sticky;
    top: 60px;
    left: 0;
    z-index: 45;
    width: calc(150px + 5%);
    height: calc(100vH - 60px);
    background: white;
    padding-top: 10px;
    user-select: none;
}

.categories > a {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 0 15px;
    background: #f0f3f3;
    height: 46px;
    border-top-right-radius: 22px;
    border-bottom-right-radius: 22px;
    text-decoration: none;
    color: var(--gray);
}

.categories > a.active {
    background: var(--light-green);
    color: var(--green);
    font-weight: bold;
}

.categories > a:hover {
    text-decoration-line: underline;
}

.label {
    margin-left: 10px;
}

.school-class {
    flex: 1;
}
</style>
