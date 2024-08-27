<script setup>
    import GroupItem from "@/Components/app/GroupItem.vue";
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
            <div v-if="false" class="text-gray-400 flex text-center p-3">You are not joined to anny groups</div>
        <div v-else>
            <GroupItem image="https://picsum.photos/200"
                title="Laravel Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
            <GroupItem image="https://picsum.photos/200"
                title="Vuejs Developers"
                description="Lorem ipsum dolor sit amet consectetur adipisicing elit." 
            />
        </div>
    </div>
</template>
