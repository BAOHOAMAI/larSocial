<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import TabItem from '@/Pages/Profile/Partials/TabItem.vue'
import Edit from '@/Pages/Profile/Edit.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useForm } from '@inertiajs/vue3';
import { XMarkIcon, CheckCircleIcon, CameraIcon } from '@heroicons/vue/24/solid'

const authUser = usePage().props.auth.user;
const coverImageSrc = ref('')
const avatarImageSrc = ref('')
const showNotification = ref(true);
const isMyProfile = computed(() => authUser && authUser.id === props.user.id )

const props = defineProps({
    errors: null,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user : {
      type : Object,
    },
    success: {
      type : String
    }
});

const imagesForm = useForm({
    avatar: null,
    cover: null,
})


function onCoverChange(event) {

    imagesForm.cover = event.target.files[0]

    if (imagesForm.cover) {
        const reader = new FileReader()
        reader.onload = () => {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.cover)
    }   
}



function onAvatarChange(event) {

  imagesForm.avatar = event.target.files[0]

  if (imagesForm.avatar) {
      const reader = new FileReader()
      reader.onload = () => {
          avatarImageSrc.value = reader.result;
      }
      reader.readAsDataURL(imagesForm.avatar)
  }
}


function resetCoverImage() {
    imagesForm.cover = null;
    coverImageSrc.value = null
}

function resetAvatarImage() {
    imagesForm.avatar = null;
    avatarImageSrc.value = null
}

function submitCoverImage() {
    imagesForm.post(route('profile.updateImages'), {
        preserveScroll: true,
        onSuccess: (user) => {
            showNotification.value = true
            resetCoverImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    })
}

function submitAvatarImage() {
    imagesForm.post(route('profile.updateImages'), {
        preserveScroll: true,
        onSuccess: (user) => {
            showNotification.value = true
            resetAvatarImage()
            setTimeout(() => {
                showNotification.value = false
            }, 3000)
        },
    })
}

</script>

<template>
    <AuthenticatedLayout>
      <div class="w-[800px] mx-auto h-full overflow-auto">
        <div
            v-show="showNotification && success"
            class="my-2 font-medium text-white bg-emerald-500 py-3 px-2"
        >
            {{ success }}
        </div>
        <div
            v-if="errors.cover"
            class="my-2 font-medium text-white bg-red-500 py-3 px-2"
        >
            {{ errors.cover }}
        </div>
        <div class="relative bg-white group">
            <img :src="coverImageSrc || user.cover_url || '/image/default_cover.jpg' " alt="" class="w-full h-[200px] object-cover">
            <div class=" absolute top-2 right-2">
              <button
                v-if="!coverImageSrc"
                class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100 rounded-[7px]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="w-3 h-3 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                </svg>
                Update Cover Image
                <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                @change="onCoverChange"/>
            </button>
              <div v-else class="flex gap-2 bg-white p-2 opacity-0 group-hover:opacity-100">
                <button
                    @click="resetCoverImage"
                    class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center">
                    <XMarkIcon class="h-3 w-3 mr-2"/>
                    Cancel
                </button>
                <button
                    @click="submitCoverImage"
                    class="bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-xs flex items-center">
                    <CheckCircleIcon class="h-3 w-3 mr-2"/>
                    Submit
                </button>
            </div>
            </div>
            <div class="flex pb-[10px]">
              <div
                class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                <img :src="avatarImageSrc || user.avatar_url || '/image/default_cover.jpg'"
                      class="w-full h-full object-cover rounded-full">
                <button
                    v-if="!avatarImageSrc"
                    class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
                    <CameraIcon class="w-8 h-8"/>

                    <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                        @change="onAvatarChange"/>
                </button>
                <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
                    <button
                        @click="resetAvatarImage"
                        class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                        <XMarkIcon class="h-5 w-5"/>
                    </button>
                    <button
                        @click="submitAvatarImage"
                        class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                        <CheckCircleIcon class="h-5 w-5"/>
                    </button>
                </div>
              </div>
              <div class="flex-1 p-4 flex justify-between items-center ">
                <h2 class="font-bold text-lg">{{ user.name }}</h2>
                <PrimaryButton v-if="isMyProfile"> 
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2 mb-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>

                  Edit Profile </PrimaryButton>
              </div>
            </div>
        </div>
        <div class="border-t">
            <TabGroup>
                <TabList class="pl-[20px] bg-white flex">
                <Tab v-slot="{ selected }" as="template" v-if="isMyProfile"> 
                  <TabItem text="About" :select="selected"/>
                </Tab>
                <Tab v-slot="{ selected }" as="template">
                  <TabItem text="Posts" :select="selected"/>
                </Tab>
                <Tab v-slot="{ selected }" as="template">
                  <TabItem text="Followers" :select="selected"/>
                </Tab>
                <Tab v-slot="{ selected } " as="template">
                  <TabItem text="Followings" :select="selected"/>
                </Tab>
                </TabList>
        
                <TabPanels class="mt-2">
                  <TabPanel
                      v-if="isMyProfile"
                  >
                    <Edit :must-verify-email="mustVerifyEmail" :status="status" />
                  </TabPanel>
                  <TabPanel
                      class="bg-white p-3 shadow"
                  >
                  Post
                  </TabPanel>
                  <TabPanel
                      class="bg-white p-3 shadow"
                  >
                  Followers
                  </TabPanel>
                  <TabPanel
                      class="bg-white p-3 shadow"
                  >
                  Followings
                  </TabPanel>
                  <TabPanel
                      class="bg-white p-3 shadow"
                  >
                  </TabPanel>
                </TabPanels>
            </TabGroup>
        </div>
      </div>
    </AuthenticatedLayout>
</template>