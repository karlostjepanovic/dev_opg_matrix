<template>
    <div id="modal">
        <div class="modal-container" :class="size" ref="modal">
            <div class="modal-header">
                <div class="modal-title">{{title}}</div>
                <div class="modal-close" @click="close">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                </div>
            </div>
            <div class="modal-body-wrap">
                <div class="modal-body">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
    props: ['title', 'size'],
    methods: {
        close(){
            let index = this.$modals.findIndex(f => f.id === this.$vnode.key);
            this.$modals.splice(index, 1);
        }
    },
}
</script>

<style scoped>
#modal {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
}

#modal:before {
    position: absolute;
    content: "";
    background: #000;
    opacity: 0.5;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}

.modal-container {
    position: relative;
    z-index: 1;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
    min-width: 400px;
    background: white;
    border-radius: 5px;
}

.modal-container.s { width: 430px; }
.modal-container.m { width: 640px; }
.modal-container.l { width: 850px; }

.modal-header {
    position: relative;
    line-height: 40px;
    height: 40px;
    text-transform: uppercase;
    background: white;
    overflow: hidden;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    user-select: none;
    border-bottom: 1px solid var(--green);
}

.modal-title {
    font-size: 100%;
    color: var(--dark-green);
    font-weight: bold;
    margin-left: 10px;
}

.modal-close {
    color: var(--green);
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    margin: 5px;
}

.modal-close:hover {
    background: var(--light-green);
    color: var(--dark-green);
}

.modal-body-wrap {
    background: white;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    padding: 0 7px 7px 7px;
    overflow: hidden;
}

.modal-body {
    padding: 14px 7px 7px 7px;
    max-height: calc(100vH - 100px);
    overflow: auto;
}

.modal-body::-webkit-scrollbar {
    width: 12px;
    height: 18px;
}

.modal-body::-webkit-scrollbar-track {
    background-color: transparent;
}

.modal-body::-webkit-scrollbar-thumb {
    border: 3px solid transparent;
    background-clip: padding-box;
    -webkit-border-radius: 10px;
    background-color: #d3d3d3;
    -webkit-box-shadow: inset -1px -1px 0 rgb(0 0 0 / 5%), inset 1px 1px 0 rgb(0 0 0 / 5%);
}
</style>
