<template>
    <div class="datepicker-wrap">
        <input
            readonly
            type="text"
            :class="this.setClass"
            :value="value"/>
    </div>
</template>

<script>
export default {
    name: "Datepicker",
    props: {
        value: {
            type: String,
            default: null
        },
        setClass: {
            type: String,
            default: 'blue'
        },
    },
    data() {
        return {
            dateValue: this.value
        };
    },
}
</script>

<style scoped>
.datepicker-wrap > input {
    width: 100%;
}

#datepicker {
    position: fixed;
    z-index: 201;
    box-shadow: 0 0 10px #2b3843;
    width: 308px;
    overflow: hidden;
    border-radius: 2px;
    animation: show 100ms linear forwards;
}

#datepicker.hide{
    animation: hide 100ms linear forwards;
}

.datepicker-header {
    position: relative;
    line-height: 36px;
    width: 100%;
    background: var(--black);
    color: white;
    box-shadow: 0 0 5px var(--light-gray);
    text-transform: uppercase;
    height: 36px;
}

.datepicker-header #previous-month,
.datepicker-header #month-name,
.datepicker-header #next-month {
    vertical-align: middle;
}

.datepicker-header #previous-month,
.datepicker-header #next-month {
    cursor: pointer;
    width: 20px;
    height: 18px;
    margin: 8px;
}

.datepicker-header #previous-month {
    float: left;
    text-align: left;
}

.datepicker-header #next-month {
    float: right;
    text-align: right;
}

.datepicker-header #previous-month:active,
.datepicker-header #next-month:active {
    color: var(--blue);
}

.datepicker-header #month-name {
    text-align: center;
    width: calc(100% - 80px);
    display: inline-block;
}

.datepicker-body {
    width: 100%;
    background: white;
    padding: 3px;
}

.datepicker-days div {
    display: inline-block;
    width: 43px;
    text-align: center;
    color: var(--black);
    font-weight: bold;
    padding: 4px 0;
    margin-bottom: 3px;
    text-decoration-line: underline;
}

.datepicker-dates div {
    border: 1px solid transparent;
    display: inline-block;
    width: 43px;
    text-align: center;
    padding: 6px 0;
    border-radius: 2px;
}

.datepicker-dates div.selectable {
    cursor: pointer;
}

.datepicker-dates div.selected {
    border: 1px solid var(--black);
    color: white;
    background: var(--black);
    font-weight: bold;
}

.datepicker-dates div.sunday {
    color: var(--light-gray);
}

.datepicker-dates div.today {
    color: var(--yellow);
    text-decoration-line: underline;
    font-weight: bold;
    border: 1px solid var(--yellow);
}

.datepicker-dates div.selectable:hover {
    border: 1px solid var(--black);
    color: var(--black);
    font-weight: bold;
}

#clear-all-button {
    width: 100%;
    border: 1px solid var(--red);
    background: var(--red);
    color: white;
    padding: 6px;
    font-size: 90%;
    text-align: center;
    border-radius: 2px;
    margin-top: 4px;
    cursor: pointer;
}

#clear-all-button:hover {
    background: var(--dark-red);
}
</style>
