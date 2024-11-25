<script setup>
import {defineProps} from 'vue';

const props = defineProps({
    users:{
        required:true,
        type: Array,
    }
})

</script>

<template>

    <div class="flex justify-center my-6">
        <div class="w-1/2">
            <h2 class="my-7">Contacts</h2>
            <div v-for="user in props.users" class="my-4">
                <a :href="route('main.chat', { buddy: user.id })">{{ user.email }}</a>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
