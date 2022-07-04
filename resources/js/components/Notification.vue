<template>
    <div>
        <p class="text-center font-weight-bolder mt-3"
           :class="`text-${resType}`">
            {{ resMsg }}
        </p>
    </div>
</template>

<script>
export default {
    name: "Notification",
    props: {
        resMsg: String,
        resType: String
    },
}
</script>

<style scoped>

</style>
