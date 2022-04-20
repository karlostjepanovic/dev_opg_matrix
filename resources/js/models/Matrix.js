export class Matrix {
    id = null;
    name = null;
    culture = {};
    cadastralParcel = {};
    area = null;
    constructor() {
        this.id = null;
        this.name = null;
        this.culture = {};
        this.cadastralParcel = {};
        this.area = null;
    }
    refresh(){
        return new Promise(async (resolve, reject) => {
            await axios.post("/matrix").then((response) => {
                this.id = response.data.id;
                this.name = response.data.name;
                this.culture = response.data.culture;
                this.cadastralParcel = response.data.cadastral_parcel;
                this.area = response.data.area;
            }).catch(() => {
                reject();
            });
            resolve(this);
        });
    }
    set(id){
        return new Promise(async (resolve, reject) => {
            await axios.post("/set-matrix/"+id).then((response) => {
                this.id = response.data.id;
                this.name = response.data.name;
                this.culture = response.data.culture;
                this.cadastralParcel = response.data.cadastral_parcel;
                this.area = response.data.area;
                resolve(this);
            }).catch(() => {
                reject();
            });
            resolve(this);
        });
    }
    getMatrices(){
        return new Promise(async (resolve, reject) => {
            axios.post("/get-available-matrices").then((response) => {
                return resolve(response.data);
            }).catch(() => {
                reject();
            });
        });
    }
}
