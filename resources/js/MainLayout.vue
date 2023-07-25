<template>

    <Head :title="title" />

    <main class="main">
        <slot></slot>
    </main>

</template>

<script>

    import { Head } from '@inertiajs/vue3'


    export default {
        props: {
            title: String,
        },

        components: {
            Head
        },

    }
</script>

<style>

</style>
