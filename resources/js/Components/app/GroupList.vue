
<script setup>
    import GroupListItem from "@/Components/app/GroupListItem.vue";
    import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
    
</script>

<template>

    <div class="px-3 bg-white border rounded-lg py-4 overflow-hidden lg:h-[480px] ">
        <div class="lg:hidden block ">
            <Disclosure v-slot="{ open }">
                <DisclosureButton
                    class="w-full" 
                >
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold">My Groups</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 transition-all" 
                            :class="open ? 'rotate-90 transform' : ''"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </DisclosureButton>
                <DisclosurePanel>
                    <GroupListItem/>
                </DisclosurePanel>
            </Disclosure>
        </div>
        <div class="h-full overflow-hidden hidden lg:flex flex-col">
            <h2 class="text-2xl font-bold">My Groups</h2>
            <GroupListItem/>
        </div>
    </div>

</template>