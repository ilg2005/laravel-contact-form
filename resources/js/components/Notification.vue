<template>
    <div>
        <p class="text-center font-weight-bolder mt-3"
           :class="`text-${notificationType}`">
            {{ notificationMsg }}
        </p>
    </div>
</template>

<script>
export default {
    name: "Notification",
    props: {
        notificationMsg: String,
        notificationType: String
    },
}
</script>

<style scoped>

</style>
