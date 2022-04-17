<template>
    <div class="container">
        <div class="wrap">
            <div class="text">
                <h2>Stranica ne postoji!</h2>
                <p>Nažalost, stranica koju ste tražili više ne postoji ili trenutno nije dostupna...</p>
                <p>Ispričavamo se na neugodnosti!</p>
            </div>
            <a href="/" class="button smooth green">
                Povratak na početnu
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "404"
}
</script>

<style scoped>
.container {
    width: 100%;
    margin-top: calc(20vH - 5%);
    padding-bottom: 20vH;
    display: flex;
    align-items: center;
    justify-content: center;
}

.wrap {
    margin-top: 5vH;
}

.text {
    margin-bottom: 20px;
}
</style>
