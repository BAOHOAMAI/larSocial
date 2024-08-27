<script setup>
    defineProps ({
        image : String, 
        name : String,
        username : String,
        follower : String,
        following : String,
        post : String,
    })
</script>

<template>
    <div class="bg-white rounded-xl mb-3 flex justify-center border">
        <div class=" bg-gray-100 rounded-xl mx-5 my-5">
            <div class="px-4 py-4">
                <div class="flex px-2 py-2 ">
                    <img :src="image" alt="" class="w-[50px] rounded-full ml-5 h-[50px]">
                    <div class="ml-4 mt-1">   
                        <h2 class="font-bold">{{ name }}</h2>
                        <h4 class="text-sm text-gray-500 mb-1">{{ username }}</h4>
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="text-center w-[100px]">
                        <h2 class="font-bold">{{ follower }}</h2>
                        <h4 class="text-gray-500">Follower</h4>
                    </div>
                    <div class="text-center w-[100px]">
                        <h2 class="font-bold">{{ following }}</h2>
                        <h4 class="text-gray-500">Following</h4>
                    </div>
                    <div class="text-center w-[100px]">
                        <h2 class="font-bold">{{ post }}</h2>
                        <h4 class="text-gray-500">Posts</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
