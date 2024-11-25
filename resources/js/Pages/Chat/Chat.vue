<script setup>
import {defineProps, ref, onMounted} from 'vue';

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
        type: Array,
    }
});
let sendMessage = function(){
    return 0;
}
onMounted(()=>{
    console.log(props.messages);
});
</script>

<template>
    <div class="flex justify-center my-6">
        <div class="w-1/2 bg-white shadow-lg rounded-lg">
            <h2 class="my-7 text-center">Chat</h2>
            <div class="p-4 h-96 overflow-y-auto" style="border-bottom: 1px solid #e5e7eb;">
                <div v-if="props.messages" v-for="(message, index) in props.messages" :key="index" class="mb-2 flex flex-col">
                    <div :class="message.sender_id === props.user.id ? 'ml-auto text-right' : 'mr-auto text-left'">
                        <div class="font-bold text-sm">
                            {{ message.sender_id === props.user.id ? props.user.name : props.buddy.name }}
                        </div>
                        <span :class="message.sender_id === props.user.id ? 'bg-blue-500 text-white p-2 rounded-lg' : 'bg-gray-300 text-black p-2 rounded-lg'">
                            {{ message.content }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center p-4">
                <input v-model="messageText" type="text" class="border w-3/4 p-2 rounded" placeholder="Enter your message" />
                <button @click="sendMessage()" class="bg-blue-500 text-white p-2 rounded">Send</button>
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
