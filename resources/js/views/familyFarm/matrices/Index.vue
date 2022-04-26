<template>
    <main-template title="Odabir matrice" :visible="!loading">
        <template v-slot:header>
            <button type="button" class="green" @click="createMatrix">
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                Kreiraj matricu
            </button>
        </template>
        <template v-if="matrices.length">
            <div class="grid-group">
                <div class="item green" v-for="matrix in matrices" @click="setMatrix(matrix.id)">
                    <div class="matrix-title">
                        <span>{{matrix.name}}</span>
                        <span class="lock-icon" v-if="matrix.locked">
                            <svg style="width:18px;height:18px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12,17C10.89,17 10,16.1 10,15C10,13.89 10.89,13 12,13A2,2 0 0,1 14,15A2,2 0 0,1 12,17M18,20V10H6V20H18M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10C4,8.89 4.89,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                            </svg>
                        </span>
                    </div>
                    <div class="txt-small">{{matrix.culture.name}}</div>
                    <div class="txt-gray txt-small self-bottom">{{matrix.cadastral_parcel.full_name}}</div>
                </div>
            </div>
        </template>
        <div class="txt-bold" v-else>Ne postoji ni jedna matrica koji možete odabrati</div>
    </main-template>
</template>

<script>
export default {
    name: "Index",
    data(){
        return {
            matrices: [],
            loading: true
        }
    },
    methods: {
        getMatrices(){
            return new Promise((resolve) => {
                this.$root.$emit('getAvailableMatrices', () => {
                    this.matrices = this.$availableMatrices;
                    resolve();
                });
            });
        },
        createMatrix(){
            this.$modals.push({
                box: require("./Create").default,
                props: { },
            });
        },
        setMatrix(id){
            this.loading = true;
            this.$root.$emit('setMatrix', id);
        }
    },
    created() {
        this.getMatrices().then(() => {
            this.loading = false;
        }).catch(() => {
            return this.$root.$emit('error');
        });
    },
    mounted() {
        this.$root.$off("getMatrices");
        this.$root.$on('getMatrices', resolve => {
            this.getMatrices().then(() => {
                return resolve();
            }).catch(() => {
                return this.$root.$emit('error');
            });
        });
    }
}
</script>

<style scoped>
.matrix-title {
    font-weight: bold;
    font-size: 110%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.lock-icon {
    color: var(--red);
}
</style>
