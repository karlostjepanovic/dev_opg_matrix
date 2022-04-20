<template>
    <div class="autocomplete">
        <input
            class="green"
            v-bind:class="extraClass"
            type="text"
            :value="value"
            v-on:input="updateValue($event.target.value)"
            @keydown.down.prevent="onArrowDown"
            @keydown.up.prevent="onArrowUp"
            @keydown.enter="onEnter($event)"/>
        <ul
            id="autocomplete-results"
            v-if="results.length > 0"
            class="autocomplete-results">
            <li
                v-for="(result, i) in results"
                :key="i"
                @click="setResult(result)"
                class="autocomplete-result"
                :class="{ 'is-active': i === arrowCounter }">
                {{ result }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'Autocomplete',
    props: {
        items: {
            type: Array,
            required: false,
            default: () => [],
        },
        extraClass: {
            required: false,
            default: ''
        },
        value: {
            type: String,
            default: ''
        },
    },
    data() {
        return {
            isOpen: false,
            results: [],
            search: '',
            arrowCounter: -1,
        };
    },
    watch: {
        items: function (value, oldValue) {
            if (value.length !== oldValue.length) {
                this.results = value;
            }
        },
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside)
    },
    destroyed() {
        document.removeEventListener('click', this.handleClickOutside)
    },
    methods: {
        updateValue (value) {
            this.$emit('input', value);
            this.search = value;
            if(value.length >= 1){
                this.filterResults();
            }
            if(value.length === 0){
                this.results = [];
            }
        },
        setResult(value){
            this.$emit('input', value);
            this.search = value;
            this.results = [];
        },
        filterResults() {
            this.results = this.items.filter((item) => {
                return item.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
            });
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.arrowCounter = -1;
                this.results = [];
            }
        },
        onArrowDown() {
            if (this.arrowCounter < this.results.length) {
                this.arrowCounter = this.arrowCounter + 1;
            }
        },
        onArrowUp() {
            if (this.arrowCounter > 0) {
                this.arrowCounter = this.arrowCounter - 1;
            }
        },
        onEnter(e) {
            this.search = this.results[this.arrowCounter];
            if(this.search){
                e.preventDefault();
                this.arrowCounter = -1;
                this.setResult(this.search);
            }
        },
    },
};
</script>

<style scoped>
.autocomplete {
    position: relative;
}

.autocomplete input {
    width: 100%;
}

.autocomplete-results {
    position: absolute;
    background: white;
    padding: 0;
    margin: 0;
    border: 1px solid var(--light-gray);
    max-height: 200px;
    overflow: auto;
    width: 100%;
    box-shadow: 0 0 10px 0 rgb(0 0 0 / 30%);
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
}

.autocomplete-result {
    padding: 10px 12px;
    line-height: 14px;
    color: var(--black);
    cursor: pointer;
    background: #fff;
    text-align: left;
    user-select: none;
}

.autocomplete-result:not(:last-child) {
    border-bottom: 1px solid var(--light-gray);
}

.autocomplete-result.is-active,
.autocomplete-result:hover {
    background: var(--green);
    color: white;
}

.autocomplete-results::-webkit-scrollbar {
    width: 12px;
    height: 18px;
}

.autocomplete-results::-webkit-scrollbar-track {
    background-color: transparent;
}

.autocomplete-results::-webkit-scrollbar-thumb {
    border: 3px solid transparent;
    background-clip: padding-box;
    -webkit-border-radius: 10px;
    background-color: #d3d3d3;
    -webkit-box-shadow: inset -1px -1px 0 rgb(0 0 0 / 5%), inset 1px 1px 0 rgb(0 0 0 / 5%);
}
</style>
