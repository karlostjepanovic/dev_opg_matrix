<template>
    <main-template title="Upravljanje sustavom" v-if="isMounted">
        <ul class="navigation">
            <li>
                <router-link :to="{name: 'appFamilyFarms'}"
                             class="navigation-tab"
                             :class="{'active' : this.$route.name === 'appFamilyFarms'}">
                    OPG-ovi
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'appUsers'}"
                             class="navigation-tab"
                             :class="{'active' : this.$route.name === 'appUsers'}">
                    Korisnici
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'appCultures'}"
                             class="navigation-tab"
                             :class="{'active' : this.$route.name === 'appCultures'}">
                    Kulture
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'appSupplies'}"
                             class="navigation-tab"
                             :class="{'active' : this.$route.name === 'appSupplies'}">
                    Sredstva
                </router-link>
            </li>
        </ul>
        <div class="content-wrap">
            <router-view></router-view>
        </div>
    </main-template>
</template>

<script>
export default {
    name: "Template",
    data(){
        return {
            isMounted: false
        }
    },
    mounted() {
        this.$loggedUser.refresh().then((response) => {
            if (response.adminRole !== 1) {
                this.$root.$emit('error');
            } else {
                this.isMounted = true;
            }
        }).catch(() => {
            this.$root.$emit('error');
        });
    },
}
</script>

<style scoped>
.navigation {
    display: flex;
    flex-wrap: wrap;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
    border-bottom: 1px solid var(--light-gray);
    user-select: none;
}

.navigation-tab {
    display: block;
    padding: 0 20px;
    color: var(--green);
    text-decoration: none;
    margin-bottom: -1px;
    background: 0 0;
    border: 1px solid transparent;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    line-height: 40px;
}

.navigation-tab:hover {
    text-decoration-line: underline;
}

.navigation-tab.active {
    color: var(--dark-green);
    background-color: white;
    font-weight: bold;
    border-color: var(--light-gray) var(--light-gray) white;
}

.content-wrap {
    border: 1px solid;
    border-color: white var(--light-gray) var(--light-gray);
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    padding: 20px;
}
</style>
