<script setup>
    import PostItem from "@/Components/app/PostItem.vue";
    import {computed, ref, watch , onMounted } from 'vue'
    import PostModal from "@/Components/app/PostModal.vue";
    import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";
    import { usePage  } from "@inertiajs/vue3";
    import axiosClient from "@/axiosClient.js";
    
    const page = usePage();
    const showEditModal = ref(false)
    const showAttachmentModal = ref(false)
    const attachmentPreview = ref({});
    const editPost = ref({})
    const loadMoreIntersect = ref(null)

    const allPosts = ref({
        data: [],
        next: null
    })

    const props = defineProps ( {
        posts: Array
    });


    watch(() => page.props.posts, () => {
        if (page.props.posts) {
            allPosts.value = {
                data: page.props.posts.data,
                next: page.props.posts.links?.next
            }
        }
    }, {deep: true, immediate: true})

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


    function loadMore() {
        if (!allPosts.value.next) {
            return;
        }

        axiosClient.get(allPosts.value.next)
            .then(({data}) => {
                allPosts.value.data = [...allPosts.value.data, ...data.data]
                allPosts.value.next = data.links.next
            })
    }

    onMounted(() => {
        const observer = new IntersectionObserver(
            (entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
                rootMargin: '-250px 0px 0px 0px'
            })

        observer.observe(loadMoreIntersect.value)
    })
    
</script>

<template>
    <div class="h-full overflow-auto">
        <PostItem 
        v-for="post of allPosts.data"
        :post= "post"
        @editClick="openEditModal"
        @openAttachment="openAttachmentModal"
        />

        <div ref="loadMoreIntersect"></div>

        <PostModal :post="editPost" v-model="showEditModal"/>
        <AttachmentPreviewModal
        v-model="showAttachmentModal"
        v-model:index="attachmentPreview.ind"
        :attachment="attachmentPreview.attachment || []"
        />
    </div>
</template>
