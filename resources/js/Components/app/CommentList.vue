<script setup>
import {ChatBubbleLeftEllipsisIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import IndigoButton from "@/Components/app/IndigoButton.vue";
import InputTextarea from "@/Components/InputTextarea.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import {usePage, Link} from "@inertiajs/vue3";
import { ref } from "vue";
import axiosClient from "@/axiosClient.js";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";

const authUser = usePage().props.auth.user;

const newCommentText = ref('');
const editingComment = ref(null);

const props = defineProps({
    post: Object,
   
})

function resetModal () {
    newCommentText.value = '';
    editingComment.value = null;

}
function createComment () {
    axiosClient.post(route('post.comment.create', props.post), {
            comment: newCommentText.value,
        })  
        .then(({data}) => {
            resetModal()
            props.post.comments.unshift(data)
            props.post.nums_of_comment++;
        
        })
}

function startCommentEdit (comment) {
    editingComment.value = comment;
}

function updateComment () {
    axiosClient.post(route('comment.update', { comment: editingComment.value }), {
            comment: editingComment.value.comment,
        })  
        .then(({data}) => {
            resetModal()
        })
}

function deleteComment (comment) {
    axiosClient.delete(route('comment.delete', { comment: comment.id }))
        .then(({data}) => {
            props.post.comments = props.post.comments.filter(c =>  c.id !== comment.id)
            props.post.nums_of_comment--;
            resetModal()
        })
}

function sendCommentReaction (comment) {
    axiosClient.post(route('comment.reaction', comment.id), {
        reaction: 'like'
    })
        .then(({data}) => {
            comment.current_user_reaction = data.current_user_has_reaction
            comment.nums_of_reaction = data.num_of_reactions;
        })
}
</script>

<template>
    <div v-if="authUser" class="flex gap-2 mb-3">
        <Link :href="route('profile', authUser.username)">
            <img :src="authUser.avatar_url"
                 class="rounded-full w-[40px] border hover:border-blue-500 h-[40px]"/>
        </Link>
        <div class="flex flex-1">
            <InputTextarea v-model="newCommentText" placeholder="Enter your comment here" rows="1"
                           class="w-full max-h-[160px] resize-none rounded-r-none">
            </InputTextarea>
            <IndigoButton @click="createComment" class="rounded-l-none w-[100px] ">
                 Submit
            </IndigoButton>
        </div>
    </div>
    <div>
        <div v-for="comment of props.post.comments" :key="comment.id" class="mb-4">
            <div class="flex justify-between gap-2">
                <div class="flex gap-2">
                    <a href="javascript:void(0)">
                        <img :src="comment.user.avatar_url"
                             class="rounded-full w-[40px] border hover:border-blue-500 h-[40px]"/>
                    </a>
                    <div>
                        <h4 class="font-bold">
                            <a href="javascript:void(0)" class="hover:underline">
                                {{ comment.user.name }}
                            </a>
                        </h4>
                        <small class="text-xs text-gray-400">{{ comment.updated_at }}</small>
                    </div>
                </div>
                <EditDeleteDropdown :user="comment.user"
                                    :post="post"
                                    :comment="comment"
                                    @edit="startCommentEdit(comment)"
                                    @delete="deleteComment(comment)"/>
            </div>
            <div class="pl-12">
                <div v-if="editingComment && editingComment.id === comment.id">
                    <InputTextarea v-model="editingComment.comment" placeholder="Enter your comment here"
                                   rows="1" class="w-full max-h-[160px] resize-none"></InputTextarea>

                    <div class="flex gap-2 justify-end">
                        <button @click="editingComment = null" class="rounded-r-none text-indigo-500">Cancel
                        </button>
                        <IndigoButton @click="updateComment" class="w-[100px]">Update
                        </IndigoButton>
                    </div>
                </div>
                <ReadMoreReadLess v-else :content="comment.comment" content-class="text-sm flex flex-1"/>
                <Disclosure>
                    <div class="mt-1 flex gap-2">
                        <button @click="sendCommentReaction(comment)"
                                class="flex items-center text-xs text-indigo-500 py-0.5 px-1  rounded-lg"
                                :class="[
                                            comment.current_user_reaction ?
                                             'bg-indigo-50 hover:bg-indigo-100' :
                                             'hover:bg-indigo-50'
                                        ]">
                            <HandThumbUpIcon class="w-3 h-3 mr-1"/>
                            <span class="mr-2">{{ comment.nums_of_reaction }}</span>
                            {{ comment.current_user_reaction ? 'unlike' : 'like' }}
                        </button>
                        <DisclosureButton
                            class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100 rounded-lg">
                            <ChatBubbleLeftEllipsisIcon class="w-3 h-3 mr-1"/>
                            <span class="mr-2">{{ comment.num_of_comments }}</span>
                            comments
                        </DisclosureButton>
                    </div>
                    <DisclosurePanel class="mt-3">
                        <CommentList :post="post"
                                     :data="{comments: comment.comments}"
                                     :parent-comment="comment"
                                     @comment-create="onCommentCreate"
                                     @comment-delete="onCommentDelete"/>
                    </DisclosurePanel>
                </Disclosure>
            </div>
        </div>
        <!-- <div v-if="!data.comments.length" class="py-4 text-center dark:text-gray-100">
            There are no comments.
        </div> -->
    </div>
</template>

<style scoped>

</style>