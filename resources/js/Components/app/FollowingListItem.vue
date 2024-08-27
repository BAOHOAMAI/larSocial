<script setup>
    import FollowingItem from "@/Components/app/FollowingItem.vue";
    import TextInput from "@/Components/TextInput.vue";
    import { ref } from "vue"; 

    const searchKeyword = ref('');

</script>

<template>
          <TextInput :model-value="searchKeyword" 
                placeholder="Search Keyword"
                class="w-[100%] mt-3"
        />
        <div class=" lg:flex-1 h-[300px] overflow-auto py-2 mt-3">
                <div v-if="false" class="text-gray-400  text-center p-3">You don't have friends yet</div>
                <div v-else>
                <FollowingItem image="https://picsum.photos/200"
                    title="Elon Musk"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
                <FollowingItem image="https://picsum.photos/200"
                    title="Bao Long"
                />
            </div>
        </div>
</template>
