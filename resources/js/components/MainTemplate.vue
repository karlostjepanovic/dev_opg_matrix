<template>
    <div class="view">
        <nav v-if="title">
            <div class="container">
                <div class="title">{{title}}</div>
                <div class="extra"><slot name="header"></slot></div>
            </div>
        </nav>
        <div v-bind:class="[title ? 'content' : 'content-wrap']">
            <slot v-if="visible"></slot>
            <!--<loading-overlay v-else></loading-overlay>-->
        </div>
    </div>
</template>

<script>
export default {
    name: "MainTemplate",
    props: {
        title: {
            type: String,
            required: false,
            default: '',
        },
        visible: {
            type: Boolean,
            required: false,
            default: true
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
    justify-content: space-between;
}

nav .title {
    font-size: 135%;
    font-weight: bold;
    text-transform: uppercase;
}

nav .extra {
    display: flex;
    gap: 10px;
}

.content {
    position: relative;
    padding: 5px 20px 20px 20px;
    min-height: calc(100% - (60px + 76px));
}

.content-wrap {
    position: relative;
    padding: 20px;
    min-height: calc(100% - (60px + 76px));
}
</style>
