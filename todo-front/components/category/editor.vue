<template>
  <div class="container">
    <table class="todoTable">
      <thead>
        <tr>
          <th class="tableHeader">ID</th>
          <th class="tableHeader">カテゴリ名</th>
          <th class="tableHeader"></th>
          <th class="tableHeader__space"></th>
          <th class="tableHeader"></th>
        </tr>
      </thead>
      <tbody>
        <tr class="category-table__row" v-for="category in categories" :key="category.id">
          <td class="tableData">
            <div class="itemId">
              {{ category.id }}
            </div>
          </td>
          <td class="tableData">
            <div class="itemText">{{ category.name }}</div>
          </td>
          <td class="updateButton__container">
            <button class="updateButton" type="button" @click="updateCategory(category)">変更
            </button>
          </td>
          <td></td>
          <td class="deleteButton__container">
            <button class="deleteButton" type="button" @click="deleteCategory(category.id)">削除
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, defineExpose } from 'vue';
import type { Category } from '~/types/category';
import { fetchWithAuth } from "@/utils/api";

const runtimeConfig = useRuntimeConfig();
const API_URL = runtimeConfig.public.apiUrl;

const categories = ref<Category[]>([]);

const refreshData = async () => {
  try {
    const data = await fetchWithAuth(`${API_URL}/api/category`);
    categories.value = data.data.map((datum: { id: number, name: string }) => ({
      id: datum.id,
      name: datum.name,
    }));
  } catch (e) {
    console.log("error!!", e);
  }
};

const updateCategory = async (category: Category) => {
  let newName = prompt("変更後のカテゴリ名を入力してください", category.name);
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
    await fetchWithAuth(`${API_URL}/api/category/${category.id}`, 'PUT', {
      id: category.id,
      name: newName
    });

    await refreshData();
    alert("カテゴリを更新しました！");
  } catch (error) {
    console.error("更新エラー", error);
  }
};

const deleteCategory = async (categoryId: number) => {
  const result = window.confirm('このカテゴリを削除してもよろしいですか？');
  if (!result) {
    return;
  }

  try {
    await fetchWithAuth(`${API_URL}/api/category/${categoryId}`, 'DELETE');

    await refreshData();
    alert("カテゴリを削除しました！");
  } catch (error) {
    console.error("削除エラー", error);
  }
}

// メソッドを外部（親コンポーネント）に公開
defineExpose({
  refreshData
});

onMounted(async () => {
  await refreshData();
});
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

.itemId {
  width: 100%;
  height: 30px;
  background: transparent;
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 2px;
  text-align: center;
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