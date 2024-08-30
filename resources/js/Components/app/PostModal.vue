<script setup>
import {computed, ref, watch} from 'vue'
import {XMarkIcon, PaperClipIcon, BookmarkIcon, ArrowUturnLeftIcon} from '@heroicons/vue/24/solid'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import BaseModal from "@/Components/app/BaseModal.vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'; 
import { isImage } from '@/helper';

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    modelValue: Boolean
})

const updateForm = useForm ({
    id : null , 
    body : '' ,
    attachments : [],
    deleted_id :[],
     _method: 'POST'
})

const extensions = usePage().props.auth.extensions
const attachmentErrors = ref([])
const formErrors = ref({});
const attachmentFiles = ref([]);

const computedAttachments = computed(() => {
    return [...attachmentFiles.value, ...(props.post.attachments || [])]
})

const showExtensionsText = computed(() => {
    for (let myFile of attachmentFiles.value) {
        const file = myFile.file
        let parts = file.name.split('.')
        let ext = parts.pop().toLowerCase()
        if (!extensions.includes(ext)) {
            return true
        }
    }

    return false;
})

const editor = ClassicEditor;

const editorConfig = {
    mediaEmbed: {
        removeProviders: ['dailymotion', 'spotify', 'youtube', 'vimeo', 'instagram', 'twitter', 'googleMaps', 'flickr', 'facebook']
    },
    toolbar: ['bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'heading', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
};


function closeModal () {
    show.value = false ;
    resetModal();
}

function resetModal() {
    updateForm.reset()
    formErrors.value = {};
    attachmentFiles.value = []
    attachmentErrors.value = [];
    if (props.post.attachments) {
        props.post.attachments.forEach(file => file.deleted = false)
    }
}

function submit () {
    
    updateForm.attachments = attachmentFiles.value.map(myFile => myFile.file)

    if (updateForm.id) {
        updateForm._method = 'PUT'
        updateForm.post(route('post.update', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },  
            onError: (errors) => {
                processErrors(errors);
            }, 
        })
    } else {
        updateForm.post(route('post'), {
            onSuccess: () => {
                closeModal();
            },  
            onError: (errors) => {
                processErrors(errors);
            }, 
        })
    }
}

function processErrors(errors) {
    formErrors.value = errors
    for (const key in errors) {
        if (key.includes('.')) {
            const [, index] = key.split('.')
            attachmentErrors.value[index] = errors[key]
        } 
    }
}

async function onAttachmentChoose ($event) {
   for (const file of $event.target.files) {

        const myFile = {
            file ,
            url : await readFile(file),
            deleted : false ,
        }
        attachmentFiles.value.push(myFile);
   }
    $event.target.value = null;
}


function readFile (file) {
    
    return new Promise((res, rej) => {
        if (isImage(file)) {
            const reader = new FileReader();
            reader.onload = () => {
                res(reader.result)
            }
            reader.onerror = rej
            reader.readAsDataURL(file)
        } else {
            res(null)
        }
    })
}

function removeFile(myFile) {
    if (myFile.file) {
        attachmentFiles.value = attachmentFiles.value.filter(f => f !== myFile);
    } else {
        myFile.deleted = true
        updateForm.deleted_id.push(myFile.id)
    }
}

function undoDelete(myFile) {
    myFile.deleted = false
    updateForm.deleted_id = updateForm.deleted_id.filter(f => f !== myFile.id)
}

watch(() => props.post, () => {
    updateForm.id = props.post.id
    updateForm.body = props.post.body || ''
})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

const emit = defineEmits(['update:modelValue', 'hide'])

</script>

<template>
    <BaseModal :title=" post.id ? 'Update Post' : 'Create Post'"
    v-model="show"
    @hide="closeModal">
    <div class="p-4">
            <PostUserHeader :post="post" :show-time="false" class="mb-4 dark:text-gray-100"/>
            <ckeditor :editor="editor" :config="editorConfig" v-model="updateForm.body" ></ckeditor>

            <!-- <div v-if="formErrors.group_id"
                 class="bg-red-400 py-2 px-3 rounded text-white mb-3">
                {{ formErrors.group_id }}
            </div> -->
           
            <div class="relative group">

                <button
                    @click="getAIContent"
                    :disabled="aiButtonLoading"
                    class="absolute right-1 top-12 w-8 h-8 p-1 rounded bg-indigo-500 hover:bg-indigo-600 text-white flex justify-center items-center transition-all opacity-0  group-hover:opacity-100 disabled:cursor-not-allowed disabled:bg-indigo-400 disabled:hover:bg-indigo-400">
                    <svg v-if="aiButtonLoading" class="animate-spin h-4 w-4 text-white"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                    </svg>

                </button>
            </div>

            <div v-if="showExtensionsText"
                 class="border-l-4 border-amber-500 py-2 px-3 bg-amber-100 mt-3 text-gray-800">
                Files must be one of the following extensions <br>
                <small>{{ extensions.join(', ') }}</small>
            </div>
            <div v-if="formErrors.attachments"
                 class="border-l-4 border-red-500 py-2 px-3 bg-red-100 mt-3 text-gray-800">
                Files must be one of the following extensions <br>
                <small>{{ formErrors.attachments }}</small>
            </div>
            <div class="grid gap-3 my-3" :class="[
                                        computedAttachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
                                    ]">
                <div v-for="(myFile, ind) of computedAttachments">
                    <div
                        class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                        :class="attachmentErrors[ind] ? 'border-red-500' : ''"
                        >

                        <div v-if="myFile.deleted"
                             class="absolute z-10 left-0 bottom-0 right-0 py-2 px-3 text-sm bg-black text-white flex justify-between items-center">
                            To be deleted

                            <ArrowUturnLeftIcon @click="undoDelete(myFile)"
                                                class="w-4 h-4 cursor-pointer"/>
                        </div>

                        <button
                            @click="removeFile(myFile)"
                            class="absolute z-20 right-3 top-3 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
                            <XMarkIcon class="h-5 w-5"/>
                        </button>

                        <img v-if="isImage(myFile.file || myFile)"
                             :src="myFile.url"
                             class="object-contain aspect-square"
                             :class="myFile.deleted ? 'opacity-50' : ''"/>
                        <div v-else class="flex flex-col justify-center items-center px-3"
                             :class="myFile.deleted ? 'opacity-50' : ''">
                            <PaperClipIcon class="w-10 h-10 mb-3"/>

                            <small class="text-center">
                                {{ (myFile.file || myFile).name }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="flex gap-2 py-3 px-4">
            <button
                type="button"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full relative"
            >
                <PaperClipIcon class="w-4 h-4 mr-2"/>
                Attach Files
                <input @click.stop @change="onAttachmentChoose" type="file" multiple
                       class="absolute left-0 top-0 right-0 bottom-0 opacity-0">
            </button>
            <button
                type="button"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
                @click="submit"
            >
                <BookmarkIcon class="w-4 h-4 mr-2"/>
                Submit
            </button>
        </div>
    </BaseModal>
</template>
