
<script setup>
import {Link} from '@inertiajs/vue3';
import {ChevronRightIcon} from "@heroicons/vue/24/solid/index.js";

defineProps({
    post: {
        type: Object
    },
})

</script>

<template>
    <div class="flex items-center gap-2 ">
        <a href="javascript:void(0)">
        <img :src="post.user.avatar_url" alt="" class="rounded-full w-[40px] border hover:border-blue-500 h-[40px]">
    </a>
    <div>
        <h4 class="font-bold">
            <a href="javascript:void(0)" class="hover:underline">
                {{ post.user.name }}
            </a>
            <template v-if="post.group">
                >
                <a href="javascript:void(0)" class="hover:underline">{{ post.group.name }}</a>
            </template>
        </h4>
        <small class="text-gray-400">{{ post.created_at }}</small>
    </div>
    </div>
</template>
