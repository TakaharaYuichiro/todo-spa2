<template>
  <div class="todo-view__search__container">
    <table class="search-table">
      <tbody>
        <tr>
          <th class="search-table__header">名前</th>
          <th class="search-table__header">ToDo(キーワード)</th>
          <th class="search-table__header">カテゴリ</th>
          <th class="search-table__header-button" rowspan="2">
            <div class="search-table__button">
              <button class="search-table__button-submit" type="button" @click="emitEvent()">
                検索
              </button>
            </div>
          </th>
        </tr>

        <tr>
          <td>
            <select v-model="searchUserId" class="search-table__item__select" placeholder="Select User">
              <option value="0">{{ "全て" }}</option>
              <option v-for="user in users" v-bind:value="user.id">
                {{ user.name }}
              </option>
            </select>
          </td>
          <td>
            <input v-model="searchKeyword" class="search-table__item__input" type="text" name="キーワード" />
          </td>
          <td>
            <select v-model="searchCategoryId" class="search-table__item__select" placeholder="Select Category">
              <option value="0">{{ "全て" }}</option>
              <option v-for="category in categories" v-bind:value="category.id">
                {{ category.name }}
              </option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import type { Category } from '~/types/category';
import type { User } from '~/types/user';

const searchKeyword = ref('');
const searchCategoryId = ref(0);
const searchUserId = ref(0);

const props = defineProps<{
  categories: Category[] | null;
  users: User[] | null;
}>();
const categories = computed(() => props.categories ?? []);
const users = computed(() => props.users ?? []);

const emit = defineEmits<{ (event: 'trigger-search', userId: number, categoryId: number, keyword: string): void; }>();
const emitEvent = () => emit('trigger-search', searchUserId.value, searchCategoryId.value, searchKeyword.value);
</script>

<style scoped>
.todo-view__search__container {
  width: 100%;
  display: flex;
  justify-content: end;
  margin: 5px 0 30px 0;
}

.search-table {
  width: 500px;
}

.search-table__header {
  font-size: xx-small;
  color: #888;
  text-align: left;
  vertical-align: bottom;
}

.search-table__item__input {
  width: 95%;
  height: 20px;
}

.search-table__item__select {
  width: 95%;
  height: 25px;
}

.search-table__header-button {
  vertical-align: bottom;
}

.search-table__button {
  width: 95%;
  height: 35px;
}

.search-table__button-submit {
  padding: 1px 10px;
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 3px;
  background: #000;
  color: #fff;
  cursor: pointer;
  white-space: nowrap;
}

.search-table__button-submit:disabled {
  background: #aaa;
  pointer-events: none;
}

.search-table__button-submit:hover {
  background: #666;
}
</style>