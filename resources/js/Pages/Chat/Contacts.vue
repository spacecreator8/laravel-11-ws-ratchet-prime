<script setup>
import {defineProps} from 'vue';

const props = defineProps({
    users:{
        required:true,
        type: Array,
    },
    user:{
        required: true,
        type: Object
    }
})
let conn = new WebSocket('ws://192.168.1.101:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    console.log(e.data);
};
conn.onclose = function(event){
    console.log("Connection was broken!");
}
let sendRoomData = function(buddyId){
    conn.send(JSON.stringify({
        flag: 'service',
        room: buddyId > props.user.id ? `${props.user.id}:${buddyId}` : `${buddyId}:${props.user.id}`,
        user: props.user.id,
        buddy: buddyId
    }));
}
</script>

<template>

    <div class="flex justify-center my-6">
        <div class="w-1/2">
            <h2 class="my-7">Contacts</h2>
            <div v-for="buddy in props.users" class="my-4">
                <a @click="sendRoomData(buddy.id)" :href="route('main.chat', { buddy: buddy.id })">{{ buddy.email }}</a>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
