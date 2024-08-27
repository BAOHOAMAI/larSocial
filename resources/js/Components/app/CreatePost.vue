<script setup>
    import { ref } from "vue"; 
    import InputTextarea from "@/Components/InputTextarea.vue";
    import PostModal from "@/Components/app/PostModal.vue";
    import { useForm , usePage } from '@inertiajs/vue3';


    const authUser = usePage().props.auth.user;

    const newPostForm = useForm({
        id : null,
        body : '',
        user : authUser
    })

    const postCreating = ref(false)

</script>

<template>
    <div class="p-4 bg-white rounded-lg border mb-3">
        <InputTextarea 
        v-model="newPostForm.body"
        @click ="postCreating = true"
        class="mb-3 w-full"
        rows="1"
        placeholder="Click here to create new post"
        /> 
        <!-- <div v-if="postCreating" class="flex gap-2 justify-between">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative">Attach Files
                <input type="file" class="left-0 top-0 right-0 bottom-0 opacity-0 absolute">
            </button>
            <button type="submit" @click="submitForm" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
        </div> -->
        <PostModal v-if="postCreating"  :post="newPostForm"  v-model="postCreating"/>

    </div>
</template>
