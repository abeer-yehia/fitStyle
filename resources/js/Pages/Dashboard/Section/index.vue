<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import pickBy from "lodash/pickBy";
import { deleteItem } from '@/utils';

const props = defineProps({
  sections: Array,
});

const selectedSection = ref({});
const filters = ref({
  sortBy: "",
});

const selectSection = (section) => {
  selectedSection.value = section;
};

const resetselectSection = () => {
  selectedSection.value = {};
};

watch(filters.value, (value) => {
  Inertia.get(route("sections.index"), pickBy(value), {
    preserveState: true,
    replace: true,
  });
});

const productImage = (product) => {
  let preview = product.images.find((image) => {
    return image.is_preview;
  });

  return "/images/" + preview.url;
};

const deleteSection = (sectionId, event) => {
  deleteItem(sectionId, 'sections.destroy', event);
};

</script>

<template>
  <DashboardLayout>
    <Head title="Sections" />

    <header class="flex items-center justify-between mb-8">
      <h2 class="font-medium uppercase text-2xl">Sections</h2>

      <Link :href="route('sections.create')" class="text-c-green-500 underline"
        >Add new Section</Link
      >
    </header>

    <table class="w-full">
      <tr class="w-full bg-white border-b">
        <th class="text-left font-medium text-xs px-4 py-3 w-16">ID</th>
        <th class="text-left font-medium text-xs px-4 py-3">Name</th>
        <th class="text-center font-medium text-xs px-4 py-3">Items</th>
        <th></th>
      </tr>
      <tr
        v-for="section in sections"
        :key="section.id"
        class="even:bg-zinc-100 odd:bg-white text-sm border-b"
      >
        <td class="px-4 py-3 text-left">
          <span class="text-xs font-medium">#{{ section.id }}</span>
        </td>
        <td class="px-4 py-3 text-left">
          <span class="text-xs font-medium">{{ section.name }}</span>
        </td>

        <td class="px-4 py-3 text-center">
          <span>{{ section.products.length }}</span>
        </td>
        <td class="flex justify-evenly mr-1 space-x-2">
          <svg
            @click="selectSection(section)"
            class="w-5 h-5 text-zinc-500 hover:text-zinc-700 cursor-pointer"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
            ></path>
          </svg>
          <Link
            :href="route('sections.edit', section.id)"
            class="text-c-green-500 underline"
            >Edit</Link
          >

          <button @click="deleteSection(section.id, $event)" class="text-fuchsia-900 underline">
            Delete
          </button>

        </td>
      </tr>
    </table>

    <div
      class="bg-white w-80 fixed p-4 right-0 top-0 bottom-0 transform transition duration-150 ease-linear z-20"
      :class="{ 'translate-x-full': Object.keys(selectedSection).length === 0 }"
    >
      <header class="mb-6">
        <span class="font-medium block">Section number: {{ selectedSection.id }}</span>
      </header>

      <div class="bg-zinc-100 p-4 mb-4 rounded">
        <span class="block text-sm font-medium mb-4">Section details</span>
        <div
          v-for="(product, index) in selectedSection.products"
          :key="index"
          class="bg-zinc-100 rounded pb-4 flex items-start space-x-3"
        >
          <div class="w-16 h-20 flex-none">
            <img :src="productImage(product)" alt="" class="w-full h-full object-cover" />
          </div>

          <div>
            <span class="block font-medium text-sm">{{ product.name }}</span>
            <span class="block text-zinc-500 text-sm"
              >x{{ product.pivot?.quantity }}</span
            >
            <span class="block text-zinc-500 text-sm"
              >€{{ product.pivot?.unit_price }}</span
            >
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="Object.keys(selectedSection).length > 0"
      @click="resetselectSection()"
      class="bg-black fixed inset-0 opacity-10 z-10"
    ></div>
  </DashboardLayout>
</template>
