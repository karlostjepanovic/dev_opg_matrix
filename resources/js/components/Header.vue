<template>
    <header v-if="loggedUser.id">
        <div class="breadcrumb">
            <router-link :to="{name: 'index'}" class="app-name"><strong>OPG</strong>matrix</router-link>
            <!--<div class="menu" @click="toggleDropdown" v-if="school.id">
                <div class="item">
                    <div class="label">ŠKOLA</div>
                    <div class="control">
                        <div class="name">{{school.name}}</div>
                        <div class="arrow" v-if="availableSchools.length > 0">
                            <svg style="width:18px;height:18px" viewBox="0 -2 24 24">
                                <path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="dropdown" v-if="availableSchools.length > 0">
                    <ul class="items">
                        <li v-for="s in availableSchools" @click="setSchool(s.id)" v-bind:class="{'selected': s.id === school.id }">
                            <span>
                                <span>{{s.name}}</span>
                                <span class="label">{{s.location}}</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="menu" @click="toggleDropdown" v-if="availableSchoolYears.length > 0">
                <div class="item">
                    <div class="label">ŠKOLSKA GODINA</div>
                    <div class="control">
                        <div class="name">{{schoolYear.id ? schoolYear.fullName : '-'}}</div>
                        <div class="arrow">
                            <svg style="width:18px;height:18px" viewBox="0 -2 24 24">
                                <path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <ul class="items">
                        <li v-for="sy in availableSchoolYears" @click="setSchoolYear(sy.id)" v-bind:class="{'selected': sy.id === schoolYear.id }">
                            <span>{{sy.full_name}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="menu" @click="toggleDropdown" v-if="schoolClass.id">
                <div class="item">
                    <div class="label">RAZREDNI ODJEL</div>
                    <div class="control">
                        <div class="name">{{schoolClass.fullName}}</div>
                        <div class="arrow">
                            <svg style="width:18px;height:18px" viewBox="0 -2 24 24">
                                <path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <ul class="items">
                        <li v-for="sc in availableSchoolClasses" @click="setSchoolClass(sc.id)" v-bind:class="{'selected': sc.id === schoolClass.id }">
                            <span>
                                <span>{{sc.full_name}}</span>
                                <span class="label">{{sc.master.full_name}}</span>
                                <span class="label" v-if="sc.sub_master !== null">{{sc.sub_master.full_name}}</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>-->
        </div>
        <div class="user-menu" @click="toggleDropdown">
            <div class="container">
                <div class="control">
                    <!--<div class="user">{{loggedUser.firstname.substr(0, 1)+'. '+loggedUser.lastname}}</div>-->
                    <div class="user">{{loggedUser.fullName}}</div>
                    <div class="role" v-if="loggedUser.adminRole">ADMINISTRATOR</div>
                    <div class="role" v-else>KORISNIK</div>
                </div>
                <div class="arrow">
                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                    </svg>
                </div>
            </div>
            <div class="dropdown user-dropdown">
                <div class="header">
                    <div class="user">
                        <div class="txt-bold">{{loggedUser.fullName}}</div>
                        <div class="txt-small">{{loggedUser.username}}</div>
                    </div>
                    <div class="logout">
                        <button type="button"
                                class="green"
                                @click="logout">
                            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13" />
                            </svg>
                        </button>
                    </div>
                </div>
                <ul class="items">
                    <li v-if="loggedUser.adminRole">
                        <router-link :to="{name: 'appUsers'}">
                            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
                            </svg>
                            <span>Upravljanje sustavom</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{name: 'setFamilyFarm'}">
                            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
                            </svg>
                            <span>Odabir OPG-a</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{}">
                            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                            </svg>
                            <span>Osobni podatci</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{}">
                            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                            </svg>
                            <span>Promjena lozinke</span>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: "Header",
    methods: {
        toggleDropdown(e){
            const item = e.target;
            item.classList.toggle('active');
            window.addEventListener('click', function(e){
                if (e.target !== item){
                    return item.classList.remove('active');
                }
            });
        },

        /* ODJAVA  */
        logout(){
            this.$loading = true;
            this.$loggedUser.logout();
        },

        /* OPG */
        async getFamilyFarms() {
            await this.$familyFarm.getFamilyFarms().then((response) => {
                this.$availableFamilyFarms = response;
            });
        },
    },
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
        familyFarm: function () {
            return this.$familyFarm;
        },
    },
    async created() {
        await this.getFamilyFarms();
    },
}
</script>

<style scoped>
header {
    position: sticky;
    z-index: 50;
    top: 0;
    height: 60px;
    width: 100%;
    display: flex;
    justify-content: space-between;
    background: #F6F6F6;
    border-bottom: 1px solid #e5e5e5;
    user-select: none;
}

.breadcrumb {
    display: flex;
    height: 100%;
}

.app-name {
    width: 220px;
    text-decoration: none;
    padding: 0 20px;
    height: 100%;
    font-size: 160%;
    position: relative;
    display: flex;
    align-items: center;
    background: #fff;
    cursor: pointer;
    color: var(--green);
}

.app-name::after {
    z-index: 1;
    position: absolute;
    top: 0;
    right: -20px;
    display: block;
    border-left: 20px solid #fff;
    border-top: 30px solid transparent;
    border-bottom: 30px solid transparent;
    width: 0;
    height: 0;
    content: " ";
}

.breadcrumb .menu {
    min-width: 200px;
    padding: 0 20px 0 45px;
    height: 100%;
    position: relative;
    display: flex;
    align-items: center;
    background: var(--green);
    cursor: pointer;
    border-right: 1px solid #fff;
}

.breadcrumb .menu::before {
    position: absolute;
    top: 0;
    left: 0;
    display: block;
    border-left: 20px solid #fff;
    border-top: 30px solid transparent;
    border-bottom: 30px solid transparent;
    width: 0;
    height: 0;
    content: " ";
}

.breadcrumb .menu::after {
    position: absolute;
    z-index: 1;
    top: 0;
    right: -20px;
    display: block;
    border-left: 20px solid var(--green);
    border-top: 30px solid transparent;
    border-bottom: 30px solid transparent;
    width: 0;
    height: 0;
    content: " ";
}

.breadcrumb .menu:hover,
.breadcrumb .menu.active {
    background: var(--dark-green);
}

.breadcrumb .menu:hover::after,
.breadcrumb .menu.active::after {
    border-left-color: var(--dark-green);
}

.item {
    color: #fff;
    width: 100%;
    pointer-events: none;
}

.item .label {
    margin-top: 1%;
    font-size: 80%;
    font-weight: bold;
}

.item .control {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.item .control .name {
    margin-right: 10px;
}

.dropdown {
    margin-left: -180px;
    position: absolute;
    z-index: 1;
    top: calc(100% - 10px);
    right: 10px;
    box-shadow: 0 4px 10px 0 rgb(0 0 0 / 40%);
    background: #fff;
    border-radius: 4px;
    pointer-events: none;
    opacity: 0;
}

.user-dropdown {
    width: 300px;
    cursor: default;
}

.dropdown:not(.user-dropdown):before {
    content: "";
    border-bottom: 8px solid #fff;
    border-right: 8px solid transparent;
    border-left: 8px solid transparent;
    position: absolute;
    top: -8px;
    right: 10px;
}

.dropdown.user-dropdown:before {
    content: "";
    border-bottom: 8px solid var(--green);
    border-right: 8px solid transparent;
    border-left: 8px solid transparent;
    position: absolute;
    top: -8px;
    right: 10px;
}

.user-dropdown .header {
    background: var(--green);
    color: white;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.user-dropdown .header .user {
    font-size: 110%;
    margin-right: 30%;
}

.user-dropdown .header button {
    font-size: 110%;
    padding: 0;
    width: 30px;
    line-height: 30px;
    min-height: 30px;
}

.user-dropdown .header button svg {
    margin: unset!important;
}

*.active .dropdown {
    pointer-events: all;
    opacity: 1;
}

.dropdown .items {
    max-height: calc(100vh - 100px);
    overflow: auto;
    padding-left: 0;
    list-style: none;
    margin: 0;
    border-radius: 4px;
}

.dropdown .items li {
    display: block;
    max-width: 320px;
    min-width: 150px;
    background: #fff;
}

.dropdown .items li.selected {
    font-weight: bold;
}

.dropdown .items li:not(:last-child) {
    border-bottom: 1px solid #e5e5e5;
}

.dropdown .items li:hover {
    background: #f6f7fa;
}

.dropdown .items li > * {
    padding: 10px 35px 10px 15px;
    display: flex;
    flex: 1;
    align-items: center;
    text-decoration: none;
    color: var(--black);
}

.dropdown .items li svg {
    margin-right: 10px;
    color: var(--dark-green);
}

span.label {
    display: block;
    font-size: 85%;
    color: var(--dark-green);
}

.user-menu {
    height: 100%;
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 10px;
}

.user-menu .container {
    display: flex;
    text-align: right;
    align-items: flex-start;
    padding-right: 10px;
    pointer-events: none;
}

.user-menu .control {
    margin-right: 10px;
}

.user-menu .control .user {
    font-size: 110%;
}

.user-menu .control .role {
    font-size: 75%;
    font-weight: bold;
    color: var(--dark-green);
}
</style>
