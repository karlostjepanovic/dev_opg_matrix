<template>
    <div class="multiselect">
        <input
            type="hidden"
            :value="'['+value+']'"
            v-on:input="updateValue($event.target.value)">
        <template v-for="item in items">
            <div class="item" v-if="item.name && item.id" @click="clickOn(item.id, $event)">
                <div class="label">{{item.name}}</div>
                <div class="icon">
                    <div class="cross">
                        <svg style="width:20px;height:14px" viewBox="0 0 20 20">
                            <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                        </svg>
                    </div>
                    <div class="check">
                        <svg style="width:20px;height:14px" viewBox="0 0 20 20">
                            <path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Multiselect",
    props: {
        items: {
            type: Array,
            required: false,
            default: () => [],
        },
        value: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        updateValue (value) {
            this.$emit('input', value);
        },
        clickOn(itemId, event){
            const e = event.target;
            if(e.classList.contains("selected")){
                e.classList.remove("selected");
                let i = this.value.indexOf(itemId);
                if (i !== -1) {
                    this.value.splice(i, 1);
                }
            }else{
                e.classList.add("selected");
                this.value.push(itemId);
            }
        }
    }
}
</script>

<style scoped>
.multiselect {
    display: flex;
    flex-direction: column;
    user-select: none;
}

.item {
    border: 1px solid var(--light-gray);
    padding: 10px 12px;
    line-height: 14px;
    color: var(--gray);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    text-decoration: none;
    background: #F6F6F6;
}

.item:active {
    background: var(--second-blue)!important;
}

.item.selected {
    color: var(--black);
    background: white;
}

.cross {
    color: var(--red);
}

.check {
    color: var(--green);
}

.item:not(.selected) .check,
.item.selected .cross {
    display: none;
}

.item * {
    pointer-events: none;
}

.item *:last-child:not(:only-child) {
    text-align: right;
}

.item:first-of-type {
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.item:last-child {
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
}

.item:not(:last-child) {
    border-bottom: none;
}

.item:hover {
    background: var(--light-blue);
}
</style>
