<script setup>

import { Head, Link } from '@inertiajs/vue3';
import GroupList from "@/Components/app/GroupList.vue";
import FollowingList from "@/Components/app/FollowingList.vue";
import PostList from "@/Components/app/PostList.vue";
import HomeProfile from "@/Components/app/HomeProfile.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';


defineProps({
    user : {
        type : Object
    },
    posts : {
        type : Object
    }
    
});

</script>

<template>
    <Head title="Welcome" />    
    <AuthenticatedLayout>
        <div class="grid lg:grid-cols-12 gap-3 p-4 h-full">
        <div class="lg:col-span-3 h-full lg:order-1  overflow-hidden">
            <HomeProfile
                 :name= "user.name"
                 :username="'@'+ user.username"
                 follower="20K"
                 following="10K"
                 post="2"
                 :image="user.avatar_url"
            />
            <GroupList/>
        </div>
        <div class="lg:col-span-3 overflow-hidden h-full lg:order-3">
            <FollowingList/>
        </div>
        <div class="lg:col-span-6  overflow-hidden h-full lg:order-2 flex flex-col">
            <CreatePost/>
            <PostList :posts ="posts.data" class="flex-1"/>
        </div>
        </div>
    </AuthenticatedLayout>
</template>
