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
                    <div class="txt-bold">{{matrix.name}}</div>
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

</style>
