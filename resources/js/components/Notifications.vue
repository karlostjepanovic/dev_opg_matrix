<template>
    <div class="notifications">
        <template v-for="notification in notifications">
            <div :class="notification.type">{{notification.message}}</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "Notifications",
    data() {
        return {
            notifications: []
        }
    },
    methods: {
        set(type, message){
            this.notifications.push({
                type: type ? type : 'blue',
                message: message
            });
        },
        green(message){
            this.set('green', message);
        },
        red(message){
            this.set('red', message);
        },
        yellow(message){
            this.set('yellow', message);
        },
        blue(message){
            this.set('blue', message);
        }
    }
}
</script>

<style scoped>
.notifications {
    position: fixed;
    right: 0;
    bottom: 0;
    z-index: 1000;
}

.notifications div {
    background: var(--blue);
    padding: 20px 30px;
    border-radius: 4px;
    color: white;
}
</style>
