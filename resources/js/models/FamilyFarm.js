export class FamilyFarm {
    id = null;
    name = null;
    address = null;
    place = null;
    oib = null;
    email = null;
    phone = null;
    website = null;
    active = null;
    owner = {};
    refresh(){
        return new Promise(async (resolve, reject) => {
            await axios.post("/family-farm").then((response) => {
                this.id = response.data.id;
                this.name = response.data.name;
                this.address = response.data.address;
                this.place = response.data.place;
                this.oib = response.data.oib;
                this.email = response.data.email;
                this.phone = response.data.phone;
                this.website = response.data.website;
                this.active = response.data.active;
                this.owner = response.data.owner;
                resolve(this);
            }).catch(() => {
                reject();
            });
        });
    }
    set(id) {
        return new Promise(async (resolve, reject) => {
            await axios.post("/set-family-farm/"+id).then((response) => {
                this.id = response.data.id;
                this.name = response.data.name;
                this.address = response.data.address;
                this.place = response.data.place;
                this.oib = response.data.oib;
                this.email = response.data.email;
                this.phone = response.data.phone;
                this.website = response.data.website;
                this.active = response.data.active;
                this.owner = response.data.owner;
                resolve(this);
            }).catch(() => {
                reject();
            });
        });
    }
    getFamilyFarms(){
        return new Promise(async (resolve, reject) => {
            axios.post("/get-available-family-farms").then((response) => {
                return resolve(response.data);
            }).catch(() => {
                reject();
            });
        });
    }
}
