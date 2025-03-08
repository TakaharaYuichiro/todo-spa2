<template>
  <div class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-header__title">
          カテゴリ変更
        </div>
        <div>
          <button class="close-button" @click="emitEvent(true)">
            <span class="close-button__icon"></span>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="group">
          <span class="modal-body__text">変更後のカテゴリ: </span>
          <select v-model="categoryId" class="modal-body__select">
            <option value="0">カテゴリなし</option>
            <option v-for="category in categories" v-bind:value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
        <div class="submit-button__container">
          <button class="submit-button" @click="emitEvent(false)">設定</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick, computed } from 'vue';
import type { Category } from '~/types/category';

const categoryId = ref(0);

const props = defineProps<{
  categories: Category[] | null;
  currentSelectedCategoryId: number;
}>();

const categories = computed(() => props.categories ?? []);
const currentSelectedCategoryId = computed(() => props.currentSelectedCategoryId);

// 親ページへのイベント
const emit = defineEmits<{ (event: 'closeModal', isCancel: boolean, categoryId: number): void; }>();
const emitEvent = (isCancel: boolean) => {
  emit('closeModal', isCancel, categoryId.value);
};

onMounted(() => {
  nextTick(() => {
    const index = categories.value.findIndex(item => item.id == currentSelectedCategoryId.value);
    if (index >= 0) categoryId.value = categories.value[index].id;
  });
});
</script>

<style scoped>
.modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.2);
}

.modal-content {
  background-color: #f4f4f4;
  margin: auto;
  width: 550px;
  min-width: 400px;
  box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);
  animation-name: modalopen;
  animation-duration: 0.3s;
  border: solid 2px #8B7969;
  padding: 5px;
  border-radius: 10px;
}

@keyframes modalopen {
  from {
    opacity: 0
  }

  to {
    opacity: 1
  }
}

.modal-header {
  padding: 3px 15px;
  display: flex;
  justify-content: space-between;
}

.modal-header__title {
  padding: 5px;
  font-size: 1.2rem;
}

.close-button {
  border: none;
  background: transparent;
}

.close-button__icon {
  display: block;
  width: 40px;
  height: 40px;
  background: #e9e9e9;
  position: relative;
  border-radius: 100%;
}

.close-button__icon::before,
.close-button__icon::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 3px;
  height: 22px;
  background: #666;
  transform: translate(-50%, -50%) rotate(45deg);
}

.close-button__icon::after {
  transform: translate(-50%, -50%) rotate(-45deg);
}

.close-button__icon:hover {
  background: #ccc;
  color: #FFF;
  cursor: pointer;
}

.modal-body {
  padding: 15px;
  color: #8B7969;
}

.group {
  display: block;
  padding: 5px 10px 15px;
}

.modal-body__text {
  margin-left: 10px;
}

.modal-body__select {
  margin-left: 10px;
  height: 30px;
  width: 300px;
  font-size: 1.2rem;
}

.submit-button__container {
  width: 100%;
  padding: 25px 0 10px;
  display: flex;
  justify-content: center;
}

.submit-button {
  padding: 10px;
  width: 200px;
  height: 40px;
  border: none;
  border-radius: 3px;
  background: #214BE0;
  color: #fff;
  cursor: pointer;
  font-size: 0.9rem;
  white-space: nowrap;
}

.submit-button:hover {
  background: #5770cd;
}

.submit-button:disabled {
  background: #aaa;
}
</style>