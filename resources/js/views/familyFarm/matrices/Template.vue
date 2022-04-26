<template>
    <div class="view">
        <nav>
            <div class="container">
                <div class="title">{{matrix.name}}</div>
                <div class="extra">
                    <div class="label">
                        <strong>Kultura:</strong>
                        {{matrix.culture.name}}
                    </div>
                    <div class="label">
                        <strong>Katastarska čestica:</strong>
                        {{matrix.cadastralParcel.full_name}}
                    </div>
                </div>
            </div>
        </nav>
        <div class="content">
            <ul class="navigation">
                <li>
                    <router-link :to="{name: 'operations'}"
                                 class="navigation-tab"
                                 :class="{'active' : this.$route.name === 'operations'}">
                        Operacije
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'notes'}"
                                 class="navigation-tab"
                                 :class="{'active' : this.$route.name === 'notes'}">
                        Bilješke
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: ''}"
                                 class="navigation-tab"
                                 :class="{'active' : this.$route.name === 'reports'}">
                        Izvještaji
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'settings'}"
                                 class="navigation-tab"
                                 :class="{'active' : this.$route.name === 'settings'}">
                        Postavke
                    </router-link>
                </li>
            </ul>
            <div class="content-wrap">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Template",
    computed: {
        loggedUser: function () {
            return this.$loggedUser;
        },
        familyFarm: function () {
            return this.$familyFarm;
        },
        matrix: function () {
            return this.$matrix;
        },
    },
}
</script>

<style scoped>
.view {
    color: var(--black);
    height: calc(100vH - 60px);
    overflow: auto;
}

nav {
    display: flex;
    align-items: center;
    padding: 0 20px;
    position: sticky;
    z-index: 40;
    top: 0;
    height: 76px;
    background: #fff;
}

nav .container {
    width: 100%;
    display: flex;
    align-items: center;
}

nav .title {
    font-size: 135%;
    font-weight: bold;
    text-transform: uppercase;
}

nav .extra {
    border-left: 1px solid var(--gray);
    margin-left: 20px;
    padding-left: 20px;
}

nav .extra .label {
    display: block;
    font-size: 90%;
    color: var(--gray);
}

.content {
    position: relative;
    padding: 5px 20px 20px 20px;
    min-height: calc(100% - (60px + 76px));
}


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
