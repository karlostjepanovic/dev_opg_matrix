<template>
    <main-template title="Odabir OPG-a" v-if="!loading" :visible="!loading">
        <template v-if="availableFamilyFarms.length">
            <div class="grid-group">
                <div class="item green" v-for="familyFarm in availableFamilyFarms" @click="setFamilyFarm(familyFarm.id)">
                    <div class="txt-bold">{{familyFarm.name}}</div>
                    <div class="txt-small">{{familyFarm.address}}</div>
                    <div class="txt-gray txt-small self-bottom">{{familyFarm.owner.full_name}}</div>
                </div>
            </div>
        </template>
        <div class="txt-bold" v-else>Ne postoji ni jedan OPG koji možete odabrati</div>
    </main-template>
</template>

<script>
export default {
    name: "SetFamilyFarm",
    data(){
        return {
            loading: true
        }
    },
    methods: {
        setFamilyFarm(id){
            this.loading = true;
            this.$root.$emit('setFamilyFarm', id);
        }
    },
    created() {
        this.$root.$emit('getAvailableFamilyFarms', () => {
            if (this.$availableFamilyFarms.length === 1) {
                this.setFamilyFarm(this.$availableFamilyFarms[0].id);
            } else {
                this.loading = false;
            }
        });
    },
    computed: {
        availableFamilyFarms: function () {
            return this.$availableFamilyFarms;
        },
    }
}
</script>

<style scoped>

</style>
