<template>    
  <div class="category-table__container">
    <table class="category-table__inner">
      <thead>
          <tr>
            <th class="category-table__header">ID</th>
            <th class="category-table__header">カテゴリ名</th>
            <th class="category-table__header category-table__header__button"></th>
          </tr>
      </thead>
      <tbody>
        <tr class="category-table__row" v-for="category in categories" :key="category.id">
          <td class="category-table__item--col1">{{ category.id }}</td>
          <td class="category-table__item--col2">
            <button  class="category-text" type="button" @click="updateCategory(category)">{{ category.name }}</button>
          </td>
          <td class="category-table__td--button">
            <button 
              class="category-table__button" 
              type="button"
              @click="deleteCategory(category.id)" 
              >削除
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

  const refreshData = async() => {
    try {
      const data = await fetchWithAuth(`${API_URL}/api/category`);
      categories.value = data.data.map((datum: {id:number, name:string} ) => ({
        id: datum.id,
        name: datum.name,
      }));
    } catch (e){
        console.log("error!!", e);
    }
  };

  const updateCategory = async(category: Category) => {
    let newName = prompt("変更後のカテゴリ名を入力してください", category.name);
    if (newName){
      if (newName.length < 1 || newName.length > 191 ) {
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

  const deleteCategory = async(categoryId: number) => {
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
  .category-table__container {
    width: 80%;
    margin: 10px auto;
    padding: 10px 10px;
    overflow:scroll;
    height: 300px;
    background: #f8f8f8;
  }
  .category-table__inner {
    width: 100%;
  }
  .category-table__header {
    font-size: small;
    color: #777;
    text-align: center;
    vertical-align: bottom;
    position: sticky;
    top: 0;
    left: 0;
    background: #f8f8f8;
  }
  .category-table__header__button {
    width: 70px;
  }

  .category-table__item--col1 {
    text-align: center;
    vertical-align: top;
    padding-top: 5px;
  }
  .category-table__item--col2{
    text-align: left;
    vertical-align: top;
  }
  .category-table__td--todo__input {
    width: 95%;
    height: 25px;
    background: #f8f8f8;
    border: 1px solid #ccc;
    padding: 2px;
  }
  .category-text{
    width: 95%;
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

  .category-table__td--button {
    text-align: center;
    vertical-align: top;
  }    
  .category-table__button {
    padding: 2px 5px;
    width: 90%;
    height: 30px;
    border: none;
    border-radius: 3px;
    background: #000;
    color: #fff;
    cursor: pointer;
  }
  .category-table__button:disabled {
    background: #aaa;
    pointer-events: none;
  }
  .category-table__button:hover {
    background: #666;
  }

  .form__error {
    height: 20px;
    color: #ff0000;
    text-align: left;
    font-size: small;
  }
</style>