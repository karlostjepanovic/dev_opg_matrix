<template>
    <div class="container">
        <div class="wrap green">
            <div class="form-section row">
                <div class="form-control">
                    <label>Naziv:</label>
                </div>
                <div class="form-control w-5">
                    <div class="field">{{matrix.name}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Katastarska čestica:</label>
                </div>
                <div class="form-control w-5">
                    <div class="field">{{matrix.cadastralParcel.full_name}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Kultura:</label>
                </div>
                <div class="form-control w-5">
                    <div class="field">{{matrix.culture.name}}</div>
                </div>
            </div>
            <div class="form-section row">
                <div class="form-control">
                    <label>Način praćenja:</label>
                </div>
                <div class="form-control w-5">
                    <div class="field">
                        <template v-if="matrix.trackingType === 'p'">površina</template>
                        <template v-if="matrix.trackingType === 'k'">količina</template>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrap yellow">
            <div class="title-wrap">
                <div class="icon">
                    <svg style="width:22px;height:24px" viewBox="0 2 24 24">
                        <path fill="currentColor" d="M12,17C10.89,17 10,16.1 10,15C10,13.89 10.89,13 12,13A2,2 0 0,1 14,15A2,2 0 0,1 12,17M18,20V10H6V20H18M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10C4,8.89 4.89,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                    </svg>
                </div>
                <div class="title">Zaključavanje / otključavanje matrice</div>
            </div>
            <div class="message-wrap">Zaključavanjem matrice onemogućuju se svi daljnji unosi, uređivanje i brisanje podataka za ovu matricu.</div>
            <div class="message-wrap">Matrica je trenutno <b>{{matrix.locked ? 'zaključana' : 'otključana'}}</b>.</div>
            <div class="action">
                <button type="button" class="yellow ghost" @click="lockMatrix">{{matrix.locked ? 'Otključaj' : 'Zaključaj'}}</button>
            </div>
        </div>
        <div class="wrap red" v-if="!matrix.locked">
            <div class="title-wrap">
                <div class="icon">
                    <svg style="width:22px;height:24px" viewBox="0 2 24 24">
                        <path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z" />
                    </svg>
                </div>
                <div class="title">Brisanje matrice</div>
            </div>
            <div class="message-wrap">Brisanjem matrice nepovratno će se obrisat svi zapisi i podatci koji su prethodno evidentirani za ovu matricu.</div>
            <div class="action">
                <button type="button" class="red ghost" @click="deleteMatrix">Obriši</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Index",
    methods: {
        lockMatrix(){
            this.$modals.push({
                box: require("./Lock").default,
                props: { },
            });
        },
        deleteMatrix(){
            this.$modals.push({
                box: require("./Delete").default,
                props: { },
            });
        },
    },
    computed: {
        matrix: function () {
            return this.$matrix;
        },
        moment: function () {
            return this.$moment;
        },
    },
}
</script>

<style scoped>
.container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.container > * {
    width: calc(50% + 50px);
}

.form-section {
    justify-content: flex-end;
}

.form-control {
    min-height: 30px;
}

.wrap {
    border-radius: 4px;
    border: 1px solid;
    padding: 20px;
    margin-top: 20px;
}

.wrap.green {
    border-color: var(--green);
    background: var(--light-green);
    color: var(--dark-green);
}

.wrap.yellow {
    border-color: var(--yellow);
    background: var(--light-yellow);
    color: var(--dark-yellow);
}

.wrap.red {
    border-color: var(--red);
    background: var(--light-red);
    color: var(--dark-red);
}

.title-wrap {
    display: flex;
    align-items: flex-start;
}

.title-wrap .title {
    font-weight: bold;
    font-size: 120%;
    text-transform: uppercase;
}

.message-wrap {
    margin: 10px 0;
    padding: 5px;
}

.action {
    text-align: right;
}
</style>
