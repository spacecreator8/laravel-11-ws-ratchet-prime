<script setup>
import {defineProps, ref, onMounted} from 'vue';
import axios from 'axios';
import {router} from '@inertiajs/vue3';
import dayjs from 'dayjs';

let messageText = ref('');

const props = defineProps({
    user:{
        required:true,
        type: Object,
    },
    buddy:{
        required:true,
        type: Object,
    },
    messages:{
        required:false,
        type: Object,
    }
});
let room_id = props.buddy.id > props.user.id ? `${props.user.id}:${props.buddy.id}` : `${props.buddy.id}:${props.user.id}`;
let messageHub =JSON.parse(JSON.stringify(props.messages));

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

let sendMessage = function(){
    router.post(route('main.store'),
        {content: messageText.value ?? 'default string',
            sender: props.user.id,
            recipient: props.buddy.id
        });
    messageHub.push({
        content: messageText.value,
        sender_id: props.user.id,
        recipient_id: props.buddy.id,
    });
    conn.send(
        JSON.stringify({content: messageText.value,
        flag: 'chat',
        sender: props.user.id,
        recipient: props.buddy.id,
        room: props.buddy.id > props.user.id ? `${props.user.id}:${props.buddy.id}` : `${props.buddy.id}:${props.user.id}`,
        value: 'one',
    }));
    messageText.value = '';
}

const formatDate = (dateString) => {
    return dayjs(dateString).format('MMMM D, YYYY h:mm A'); // форматируем дату
};

onMounted(()=>{

});
</script>

<template>
    <div class="flex justify-center my-6">
        <div class="w-1/2 bg-white shadow-lg rounded-lg">
            <h2 class="my-7 text-center">Chat</h2>
            <div class="p-4 h-96 overflow-y-auto" style="border-bottom: 1px solid #e5e7eb;">
                <div v-if="props.messages" v-for="(message, index) in messageHub" :key="index" class="mb-2 flex flex-col">
                    <div :class="message.sender_id === props.user.id ? 'ml-auto text-right' : 'mr-auto text-left'">
                        <div class="font-bold text-sm">
                            {{ message.sender_id === props.user.id ? props.user.name : props.buddy.name }}
                        </div>
                        <span :class="message.sender_id === props.user.id ? 'bg-blue-500 text-white p-2 rounded-lg' : 'bg-gray-300 text-black p-2 rounded-lg'">
                            {{ message.content }}
                        </span>
                        <div class="text-sm">
                            {{ formatDate(message.created_at) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center p-4">
                <input v-model="messageText" type="text" class="border w-3/4 p-2 rounded" placeholder="Enter your message" />
                <button @click.prevent=sendMessage() class="bg-blue-500 text-white p-2 rounded">Send</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
span {
    display: inline-block;
    border-radius: 8px;
    padding: 8px 12px;
    margin: 4px 0;
}
</style>
