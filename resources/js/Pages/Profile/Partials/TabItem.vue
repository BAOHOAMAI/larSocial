<script setup>
    defineProps({
        text : String , 
        select : Boolean
    });
</script>

<template>
     <button
        :class="[
            ' px-3 py-2.5 outline-none text-sm',
            '',
            select
            ? 'bg-white text-blue-500 border-b-2 border-blue-500'
            : 'text-gray-700',
        ]"
        >
        {{ text }}
    </button>
</template>