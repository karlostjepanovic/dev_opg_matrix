export class LoggedUser {
    id = null;
    fullName = null;
    firstname = null;
    lastname = null;
    oib = null;
    email = null;
    username = null;
    adminRole = false;
    familyFarm = {
        adminRole: false,
    };
    fontSize = 'n';
    refresh(){
        return new Promise(async (resolve, reject) => {
            await axios.post("/logged-user").then((response) => {
                this.id = response.data.id;
                this.fullName = response.data.full_name;
                this.firstname = response.data.firstname;
                this.lastname = response.data.lastname;
                this.username = response.data.username;
                this.oib = response.data.oib;
                this.email = response.data.email;
                this.adminRole = response.data.admin_role;
                this.fontSize = response.data.font_size;
                /*this.school.adminRole = response.data.school_admin_role;
                this.school.associateRole = response.data.school_associate_role;*/
            }).catch(() => {
                reject();
            });
            await resolve(this);
        });
    }
    logout(){
        this.id = null;
        this.fullName = null;
        this.firstname = null;
        this.lastname = null;
        this.oib = null;
        this.email = null;
        this.username = null;
        this.adminRole = false;
        this.familyFarm = {
            adminRole: false,
        };
        this.fontSize = 'n';
        axios.post("/logout").then(() => {
            return window.location.reload();
        });
    }
}
