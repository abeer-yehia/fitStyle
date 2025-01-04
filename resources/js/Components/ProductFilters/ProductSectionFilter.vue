<script setup>
import { ref, watch } from "vue";

const emits = defineEmits(["setSection"]);

const props = defineProps({
  sections: Array,
  section: String,
});

const selectedSection = ref(props.section);

const selectSection = (sectionSlug) => {
  if (!selectedSection.value.includes(sectionSlug)) {
    selectedSection.value === ""
      ? (selectedSection.value = sectionSlug)
      : (selectedSection.value += "," + sectionSlug);
  } else {
    // the url and laravel expect a string (of one or more categories separated by a comma)
    // To remove a section already present in the list,
    // I first turn the string into an array, then remove the section, and finally turn it back into a string
    selectedSection.value = !Array.isArray(selectedSection.value)
      ? selectedSection.value.split(",")
      : selectedSection.value;

    let index = selectedSection.value.indexOf(sectionSlug);

    selectedSection.value.splice(index, 1);

    selectedSection.value = selectedSection.value.join(",");
  }
};

watch(
  () => props.section,
  () => {
    selectedSection.value = props.section;
  }
);

watch(
  () => selectedSection.value,
  () => {
    emits("setSection", selectedSection.value);
  }
);
</script>

<template>
  <section class="p-6 pb-0">
    <span class="block font-medium text-xs uppercase mb-4">Section</span>

    <ul class="">
      <li
        v-for="section in sections"
        :key="section.id"
        @click="selectSection(section.slug)"
        class="text-xs text-zinc-500 flex items-start space-x-2 cursor-pointer px-2 py-1 rounded"
        :class="{
          'font-medium text-zinc-900 bg-zinc-100':
            selectedSection && selectedSection.split(',').includes(section.slug),
        }"
      >
        <div
          class="w-4 h-4 flex justify-center items-center cursor-pointer rounded flex-none"
          :class="[
            selectedSection && selectedSection.split(',').includes(section.slug)
      ? 'bg-c-green-600'
      : 'bg-zinc-300',
          ]"
        >
          <!-- Check svg -->
          <svg
            v-if="selectedSection && selectedSection.split(',').includes(section.slug)"
            class="w-3 h-3 text-white"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            ></path>
          </svg>
        </div>
        <div class="flex items-center justify-between w-full">
          <span class="block">{{ section.name }}</span>
          <span
            class="text-xxs block font-semibold bg-zinc-200 px-1.5 py-0.5 text-zinc-500 rounded"
          >
            {{ section.products_count }}
          </span>
        </div>
      </li>
    </ul>
  </section>
</template>
