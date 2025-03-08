<template>
  <div class="container">
    <HomeModal @closeModal="eventModal" v-if="show" :categories="categories"
      :currentSelectedCategoryId="currentSelectedCategoryId" />

    <table class="todoTable">
      <thead>
        <tr>
          <th class="tableHeader">名前</th>
          <th class="tableHeader__space"></th>
          <th class="tableHeader">ToDo</th>
          <th class="tableHeader"></th>
          <th class="tableHeader">カテゴリ</th>
          <th class="tableHeader"></th>
          <th class="tableHeader__space"></th>
          <th class="tableHeader"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="todo in todos" :key="todo.id">
          <td class="tableData">
            <div class="itemText">{{ getUserName(todo.userId) }}</div>
          </td>
          <td></td>
          <td class="tableData">
            <div class="itemText">{{ todo.content }}</div>
          </td>
          <td class="updateButton__container">
            <button class="updateButton" type="button" @click="updateTodo(todo)"
              :disabled="currentUserInfo.id !== todo.userId">変更
            </button>
          </td>
          <td class="tableData">
            <div class="itemText">{{ getCategoryName(todo.categoryId) }}</div>
          </td>

          <td class="updateButton__container">
            <button class="updateButton" type="button"
              @click="show = true; targetTodoId = todo.id; currentSelectedCategoryId = todo.categoryId ? todo.categoryId : 0"
              :disabled="currentUserInfo.id !== todo.userId">変更
            </button>
          </td>
          <td></td>
          <td class="deleteButton__container">
            <button class="deleteButton" type="button" @click="deleteTodo(todo.id)"
              :disabled="currentUserInfo.id !== todo.userId">削除
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import type { Category } from '~/types/category';
import type { Todo } from '~/types/todo';
import type { User } from '~/types/user';
import { fetchWithAuth } from "@/utils/api";

const runtimeConfig = useRuntimeConfig();
const API_URL = runtimeConfig.public.apiUrl;

const show = ref(false);
const targetTodoId = ref(0);
const currentSelectedCategoryId = ref(0);

const props = defineProps<{
  todos: Todo[] | null;
  categories: Category[] | null;
  users: User[] | null;
  currentUserInfo: User;
}>();

const categories = computed(() => props.categories ?? []);
const todos = computed(() => props.todos ?? []);
const users = computed(() => props.users ?? []);
const currentUserInfo = computed(() => props.currentUserInfo);

// 親ページへのイベント
const emits = defineEmits(['trigger-editor']);
const emitEvent = () => emits('trigger-editor');

// カテゴリ変更用のモーダル画面からのデータをイベントで受ける
const eventModal = async (isCancel: boolean, selectedCategoryId: number) => {
  show.value = false;
  if (isCancel) return;

  const targetTodo = todos.value.find(item => item.id == targetTodoId.value);
  try {
    await fetchWithAuth(`${API_URL}/api/todo/${targetTodoId.value}`, 'PUT', {
      id: targetTodoId.value,
      user_id: targetTodo?.userId,
      content: targetTodo?.content,
      category_id: selectedCategoryId == 0 ? null : selectedCategoryId,
    });

    // データ更新を親ページにイベントで伝える
    emitEvent();
    alert("カテゴリを更新しました！");
  } catch (error) {
    console.error("更新エラー", error);
  }
};

const getCategoryName = (targetCategoryId: number) => {
  if (targetCategoryId === null) {
    return "カテゴリなし";
  } else {
    return categories.value.find((category: Category) => category.id == targetCategoryId)?.name;
  }
}

const getUserName = (targetId: number) => {
  return users.value.find((item: User) => item.id == targetId)?.name;
}

const updateTodo = async (todo: Todo) => {
  let newName = prompt("変更後のToDoを入力してください", todo.content);
  if (newName) {
    if (newName.length < 1 || newName.length > 191) {
      alert('カテゴリ名は1文字以上191文字以下としてください');
      return;
    }
  } else {
    alert('カテゴリ名は必須です');
    return;
  }

  try {
    await fetchWithAuth(`${API_URL}/api/todo/${todo.id}`, 'PUT', {
      user_id: todo.userId,
      content: newName,
      category_id: todo.categoryId
    });

    // データ更新を親ページにイベントで伝える
    emitEvent();
    alert("Todoを更新しました！");
  } catch (error) {
    console.error("更新エラー", error);
  }
};

const deleteTodo = async (todoId: number) => {
  const result = window.confirm('このToDoを削除してもよろしいですか？');
  if (!result) {
    return;
  }

  try {
    await fetchWithAuth(`${API_URL}/api/todo/${todoId}`, 'DELETE');

    // データ更新を親ページにイベントで伝える
    emitEvent();
    alert("ToDoを削除しました！");
  } catch (error) {
    console.error("削除エラー", error);
  }
}
</script>

<style scoped>
.container {
  width: 100%;
  margin: 10px 0;
  overflow: scroll;
  height: 300px;
}

.todoTable {
  width: 100%;
}

.tableHeader {
  font-size: small;
  color: #777;
  text-align: center;
  vertical-align: bottom;
  position: sticky;
  top: 0;
  left: 0;
  background: #f8f8f8;
}

.tableHeader__space {
  width: 5px;
}

.tableData {
  text-align: left;
  vertical-align: bottom;
  max-width: 300px;
  overflow: hidden;
  text-overflow: ellipsis;
}

.itemText {
  width: 100%;
  height: 30px;
  background: #fff;
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 2px;
  text-align: left;
  font-size: large;
  font-weight: normal;
  color: #666;
}

.updateButton__container {
  text-align: left;
  vertical-align: bottom;
  width: 0;
}

.updateButton {
  padding: 2px 5px;
  width: 38px;
  height: 24px;
  border: none;
  text-decoration: underline;
  background: transparent;
  color: black;
  cursor: pointer;
  font-size: small;
  white-space: nowrap;
}

.updateButton:hover {
  color: #999;
}

.updateButton:disabled {
  color: #ccc;
  text-decoration: none;
  pointer-events: none;
}

.deleteButton__container {
  text-align: center;
  vertical-align: middle;
  width: 0;
}

.deleteButton {
  padding: 2px 5px;
  width: 50px;
  height: 30px;
  border: none;
  border-radius: 3px;
  background: #000;
  color: #fff;
  cursor: pointer;
  white-space: nowrap;
}

.deleteButton:hover {
  background: #666;
}

.deleteButton:disabled {
  background: #aaa;
  pointer-events: none;
  pointer-events: none;
}

.form__error {
  height: 20px;
  color: #ff0000;
  text-align: left;
  font-size: small;
}
</style>