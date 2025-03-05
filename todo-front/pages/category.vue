<template>    
  <div class="main-container">
    <CategoryRegistration @trigger-registration="eventRegistrationComponent"/>
    <CategoryEditor ref="editorRef"/>
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
