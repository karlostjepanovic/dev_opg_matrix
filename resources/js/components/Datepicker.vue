<template>
    <div class="datepicker-wrap">
        <input
            readonly
            type="text"
            :class="this.setClass"
            @click="open($event)"
            ref="input"
            :value="value"/>
        <div class="datepicker" ref="datepicker" v-show="visible">
            <div class="header">
                <div class="arrow" @click.prevent="changeMonth('previous')">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
                    </svg>
                </div>
                <div class="title">{{months[monthNumber - 1]+', '+yearNumber+'.'}}</div>
                <div class="arrow" @click.prevent="changeMonth('next')">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                    </svg>
                </div>
            </div>
            <div class="body">
                <div class="days">
                    <div>PON</div>
                    <div>UTO</div>
                    <div>SRI</div>
                    <div>ČET</div>
                    <div>PET</div>
                    <div>SUB</div>
                    <div>NED</div>
                </div>
                <div class="dates" ref="dates">
                    <template v-for="(day, index) in days">
                        <div v-if="day.number" @click="set(day.date)" class="selectable" v-bind:class="{'selected': day.selected, 'today': day.today}">{{day.number}}</div>
                        <div v-else></div>
                    </template>
                </div>
            </div>
        </div>
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
            visible: false,
            dateValue: this.value,
            months: [
                'Siječanj',
                'Veljača',
                'Ožujak',
                'Travanj',
                'Svibanj',
                'Lipanj',
                'Srpanj',
                'Kolovoz',
                'Rujan',
                'Listopad',
                'Studeni',
                'Prosinac'
            ],
            monthNumber: null,
            yearNumber: null,
            previousMonth: null,
            nextMonth: null,
            days: [],
            lastDay: null,
            today: null,
            selected: null,
        };
    },
    methods: {
        open(e){
            this.visible = true;
            this.generate();
            this.update();
            window.addEventListener('click', (e) => {
                const el = this.$refs.datepicker || false;
                if (el && !this.$refs.datepicker.contains(e.target) && e.target !== this.$refs.input){
                    return this.visible = false;
                }
            });
        },
        generate(){
            this.monthNumber = null;
            this.yearNumber = null;
            this.previousMonth = null;
            this.nextMonth = null;
            this.days = [];
            this.lastDay = null;
            this.today = null;
            this.selected = null;
            const datetime = this.dateValue ? new Date(this.formatStringToDate(this.dateValue)) : new Date();
            let prefixDays = new Date(datetime.getFullYear(), datetime.getMonth(), 1).getDay();
            if(prefixDays === 0) { prefixDays = 6; }else{ prefixDays--; }
            this.monthNumber = (datetime.getMonth() + 1);
            this.yearNumber = datetime.getFullYear();
            this.previousMonth = this.addMonths(datetime, -1).toString();
            this.nextMonth = this.addMonths(datetime, 1).toString();
            this.prefixDays = prefixDays;
            this.lastDay = new Date(datetime.getFullYear(), datetime.getMonth()+1, 0).getDate();
            this.today = this.formatDate(new Date);
            this.selected = this.dateValue ? this.formatStringToDate(this.dateValue) : false;
        },
        formatStringToDate(date) {
            const parts = date.split(".");
            return [parts[2], parts[1], parts[0]].join('-');
        },
        formatDate(date) {
            let d = new Date(date);
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            let year = d.getFullYear();
            if (month.length < 2){
                month = '0' + month;
            }
            if (day.length < 2){
                day = '0' + day;
            }
            return [year, month, day].join('-');
        },
        addMonths(date, months) {
            let cd = date.getDate();
            let d = new Date(date);
            d.setMonth(d.getMonth() + months);
            if (d.getDate() !== cd) {
                d.setDate(0);
            }
            return d;
        },
        update(){
            for(let i = 1; i <= this.prefixDays; i++){
                this.days.push({});
            }
            for(let i = 1; i <= this.lastDay; i++){
                let dayId = (this.yearNumber.toString())+"-"+(this.monthNumber < 10 ? "0"+this.monthNumber : this.monthNumber.toString())+"-"+(i < 10 ? "0"+i : i.toString());
                let dayValue = (i < 10 ? "0"+i : i.toString())+"."+(this.monthNumber < 10 ? "0"+this.monthNumber : this.monthNumber.toString())+"."+this.yearNumber+".";
                this.days.push({
                    number: i,
                    date: dayValue,
                    today: this.today === dayId,
                    selected: this.selected === dayId
                });
            }
        },
        changeMonth(value){
            if(value === 'previous'){
                this.dateValue = this.previousMonth;
            }else if(value === 'next'){
                this.dateValue = this.nextMonth;
            }else{
                return this.visible = false;
            }
            this.generate();
            this.update();
        },
        set(date){
            this.dateValue = date;
            this.$emit('input', date);
            this.close();
        },
        close(){
            this.visible = false;
        }
    }
}
</script>

<style>
.datepicker-wrap {
    position: relative;
}

.datepicker-wrap > input {
    width: 100%;
}

.datepicker {
    position: absolute;
    z-index: 5;
    top: -40%;
    left: -10%;
    border-radius: 4px;
    width: 350px;
    overflow: hidden;
    box-shadow: 0 0 30px rgb(0 0 0 / 80%);
    user-select: none;
}

.datepicker .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
    color: var(--dark-green);
    font-weight: bold;
    border-bottom: 1px solid var(--green);
    font-size: 110%;
    text-transform: uppercase;
}

.datepicker .header > * {
    margin: 5px;
}

.datepicker .header .arrow {
    background: white;
    color: var(--dark-green);
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.datepicker .header .arrow:hover {
    background: var(--light-green);
}

.datepicker .body {
    background: white;
    padding: 5px;
}

.datepicker .body .days {
    display: flex;
    align-items: center;
}

.datepicker .days > div {
    text-align: center;
    flex: 1;
    padding: 5px;
    color: var(--green);
    font-weight: bold;
}

.datepicker .body .dates {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
}

.datepicker .dates > div {
    text-align: center;
    width: calc(100%/7);
    line-height: 34px;
    color: var(--gray);
    border-radius: 4px;
}

.datepicker .dates > div.selected {
    border: 1px solid var(--green);
    color: var(--dark-green);
    background: var(--light-green);
    font-weight: bold;
}

.datepicker .dates > div.today {
    color: var(--green);
    text-decoration-line: underline;
    font-weight: bold;
    border: 1px solid var(--green);
}

.datepicker .dates > div.selectable:hover {
    color: var(--green);
    background: #eff0f5;
    text-decoration-line: underline;
    cursor: pointer;
}
</style>
