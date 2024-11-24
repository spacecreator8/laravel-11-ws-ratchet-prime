<script setup>
import {defineProps, ref} from 'vue';

let messageText = ref('');
const props = defineProps({
    user:{
        required:true,
        type: Object,
    },
    message:{
        required:false,
        type: Array,
    }
});
let sendMessage = function(){
    return 0;
}
</script>

<template>
    <div class="flex justify-center my-6">
        <div class="w-1/2 bg-white shadow-lg rounded-lg">
            <h2 class="my-7 text-center">Chat</h2>
            <div class="p-4 h-96 overflow-y-auto" style="border-bottom: 1px solid #e5e7eb;">

                <div v-if="props.messages" v-for="(message, index) in props.messages" :key="index" class="mb-2">
                    <span class="font-bold">{{ message.sender }}:</span>
                    <span>{{ message.text }}</span>
                </div>
            </div>
            <div class="p-4 flex items-center">
                <input
                    type="text"
                    v-model="messageText"
                    placeholder="Type a message..."
                    class="flex-grow border border-gray-300 rounded-l-md p-2"
                />
                <button
                    @click="sendMessage"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-r-md">
                    Send
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
