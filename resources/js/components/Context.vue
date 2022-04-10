<template>
    <div class="context">
        <div class="icon" @click="toggleMenu">
            <svg style="width:14px;height:14px;pointer-events: none" viewBox="0 2 20 20">
                <path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
            </svg>
        </div>
        <div class="menu">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "Context",
    methods: {
        toggleMenu(e){
            const item = e.target.closest('.context');
            item.classList.toggle('active');
            window.addEventListener('click', function(e){
                e.stopPropagation();
                if (!item.contains(e.target)){
                    return item.classList.remove('active');
                }
            });
        },
    }
}
</script>

<style scoped>
.context {
    margin-left: 5px;
    width: 14px;
    height: 14px;
    position: relative;
    display: inline-block;
    float: right;
    user-select: none;
    pointer-events: none;
}

.context .icon {
    position: relative;
    right: 8px;
    top: -4px;
    width: 24px;
    height: 24px;
    border: 1px solid #d4dade;
    border-radius: 4px;
    background: #DCE4E7;
    display: flex;
    align-items: center;
    color: #8992A2;
    padding: 3px;
    cursor: pointer;
    user-select: unset;
    pointer-events: all;
}

.context:hover .icon,
.context.active .icon {
    background: #8992A2;
    color: #fff;
}

.context .menu {
    position: absolute;
    right: -5px;
    top: 20px;
    background: #fff;
    z-index: 1;
    box-shadow: 0 2px 5px 0 rgb(0 0 0 / 40%);
    border-radius: 4px;
    overflow: hidden;
    pointer-events: none;
    opacity: 0;
}

.context.active .menu {
    cursor: pointer;
    user-select: unset;
    pointer-events: all;
    opacity: 1;
}

.context .menu > * {
    display: block;
    min-width: 170px;
    background: #fff;
    padding: 10px 22px 10px 12px;
    text-decoration: none;
    color: var(--black);
    user-select: none;
}

.context .menu > *:not(:last-child) {
    border-bottom: 1px solid #e5e5e5;
}

.context .menu > *:hover {
    background: #f6f7fa;
}
</style>
