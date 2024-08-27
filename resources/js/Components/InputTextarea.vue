<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: false
    },
    autoResize: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue']);
const textarea = ref(null);

watch(() => props.modelValue, (newVal) => {
    textarea.value.value = newVal || ''; 
    setTimeout(() => {
        adjustHeight();
    }, 10);
});

function onInputChange($event) {
    emit('update:modelValue', $event.target.value);
}

function adjustHeight() {
    if (props.autoResize) {
        textarea.value.style.height = 'auto';
        textarea.value.style.height = (textarea.value.scrollHeight + 1) + 'px';
    }
}

onMounted(() => {
    adjustHeight();
});
</script>

<template>
    <textarea 
        @input="onInputChange" 
        ref="textarea"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm resize-none overflow-hidden"
    ></textarea>
</template>
