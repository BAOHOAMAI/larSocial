<script setup>
    import PostItem from "@/Components/app/PostItem.vue";
    import {computed, ref, watch} from 'vue'
    import PostModal from "@/Components/app/PostModal.vue";
    import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";

    defineProps ( {
        posts: {
            type: Object
        }
    });
    
    const showEditModal = ref(false)
    const showAttachmentModal = ref(false)
    const attachmentPreview = ref({});
    const editPost = ref({})

    function openEditModal (post) {
        editPost.value = post;
        showEditModal.value = true;
    }

    function openAttachmentModal (attachment , ind) {
        attachmentPreview.value = {
            attachment ,
            ind
        };
        showAttachmentModal.value = true;
    }


</script>

<template>
    <div class="h-full overflow-auto">
        <PostItem 
        v-for="post of posts"
        :post= "post"
        @editClick="openEditModal"
        @openAttachment="openAttachmentModal"
        />
        <PostModal :post="editPost" v-model="showEditModal"/>
        <AttachmentPreviewModal
        v-model="showAttachmentModal"
        v-model:index="attachmentPreview.ind"
        :attachment="attachmentPreview.attachment || []"
        />
    </div>
</template>
