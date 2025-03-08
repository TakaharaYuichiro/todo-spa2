<template>
  <div class="main-container">
    <CategoryRegistration @trigger-registration="eventRegistrationComponent" />
    <CategoryEditor  class="listContainer" ref="editorRef" />
  </div>
</template>

<script setup lang="ts">
import editorComponent from '~/components/category/editor.vue';

// ページアクセス時に認証チェック
definePageMeta({
  middleware: "auth",
});

const eventRegistrationComponent = () => {
  triggerEditComponentMethod();
};

const editorRef = ref<InstanceType<typeof editorComponent> | null>(null);

const triggerEditComponentMethod = () => {
  if (editorRef.value) {
    editorRef.value.refreshData();
  }
};
</script>

<style scoped>
.listContainer {
  width: 80%;
  margin: 10px auto;
  padding: 10px 10px;
  background: #f8f8f8;
}
</style>
