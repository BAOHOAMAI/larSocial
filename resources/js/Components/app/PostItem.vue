<script setup>
    import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
    import PostUserHeader from "@/Components/app/PostUserHeader.vue";
    import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
    import CommentList from "@/Components/app/CommentList.vue";
    import PostAttachment from "@/Components/app/PostAttachment.vue";
    import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
    import { router } from '@inertiajs/vue3'
    import axiosClient from "@/axiosClient.js";
    import {computed, ref, watch} from 'vue'
    import {ChatBubbleLeftRightIcon, HandThumbUpIcon} from '@heroicons/vue/24/outline'

    const props = defineProps ({
        post : Object, 
    })
    const emit = defineEmits(['editClick' , 'openAttachment']);
    function openEditModal() {
        emit('editClick' , props.post);
    }

    function deletePost() {
        router.delete(route('post.delete' , props.post), {
            preserveScroll: true,
            onBefore: () => confirm('Are you sure you want to delete this post?'),
        })
    }

    function openAttachment(ind) {
        emit('openAttachment' , props.post.attachments , ind)
    }

    function sendReaction () {
        axiosClient.post(route('post.reaction', props.post), {
        reaction: 'like'
    })
        .then(({data}) => {
            props.post.current_user_reaction = data.current_user_has_reaction
            props.post.nums_of_reaction = data.num_of_reactions;
        })
    }

</script>

<template>
    <div class="bg-white border rounded-xl p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <PostUserHeader :post="post"/>
            <div class="flex items-center gap-2">
                <EditDeleteDropdown :user="post.user" :post="post"
                                    @edit="openEditModal"
                                    @delete="deletePost"
                />
            </div>
        </div>
        <div class="mb-3">
            <ReadMoreReadLess :content="props.post.body"/>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-5" >
            <PostAttachment 
            :attachments="post.attachments"
            @attachmentClick="openAttachment"
            />
        </div>
        <Disclosure v-slot="{ open }">
            <!--            Like & Comment buttons-->
            <div class="flex gap-2">
                <button
                    @click="sendReaction"
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center  rounded-lg py-2 px-4 flex-1"
                    :class="[
                    post.current_user_reaction ?
                     'bg-sky-100 dark:bg-sky-900 hover:bg-sky-200 dark:hover:bg-sky-950' :
                     'bg-gray-100 dark:bg-slate-800 dark:hover:bg-slate-800 '
                ]"
                >
                    <HandThumbUpIcon class="w-5 h-5"/>
                    <span class="mr-2">{{ post.nums_of_reaction }}</span>
                    {{ props.post.current_user_reaction ? 'Unlike' : 'Like' }}
                </button>
                <DisclosureButton
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center bg-gray-100 dark:bg-slate-900 dark:hover:bg-slate-800 rounded-lg hover:bg-gray-200  py-2 px-4 flex-1"
                >
                    <ChatBubbleLeftRightIcon class="w-5 h-5"/>
                    <span class="mr-2">{{ post.nums_of_comment }}</span>
                    Comment
                </DisclosureButton>
            </div>

            <DisclosurePanel class="comment-list mt-3 max-h-[400px] overflow-auto">
                <CommentList :post="post" :data ="{ comments : post.comments}"/>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>
