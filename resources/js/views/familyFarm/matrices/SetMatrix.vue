<template>
    <main-template title="Odabir matrice" :visible="!loading">
        <template v-if="matrices.length">
            <div class="grid-group">
                <div class="item green" v-for="matrix in matrices">
                    <div class="txt-bold">{{matrix.name}}</div>
                    <div class="txt-small">{{matrix.address}}</div>
                    <div class="txt-gray txt-small self-bottom">{{matrix.owner.full_name}}</div>
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
    created() {
        this.loading = false;
    },
}
</script>

<style scoped>

</style>
