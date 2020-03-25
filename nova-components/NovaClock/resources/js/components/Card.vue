<template>
    <card class="flex flex-col items-center justify-center">
        <Clock :blink="card.blink" :displaySeconds="card.displaySeconds"/>
    </card>
</template>

<script>
import Clock from 'vue-digital-clock';

export default {
    props: [
        'card',

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],
    components: {
      Clock,
    },

    mounted() {
        //
    },
}
</script>
