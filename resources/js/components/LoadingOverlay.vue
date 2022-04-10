<template>
    <div class="loading-overlay">
        <div class="loader"></div>
    </div>
</template>

<script>
export default {
    name: "FormLoading"
}
</script>

<style scoped>
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 1;
    background: #fff;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: visible;
    padding: 20px 0;
}

.loader {
    border: 6px solid var(--light-gray);
    border-radius: 50%;
    border-top: 6px solid var(--green);
    width: 40px;
    height: 40px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
