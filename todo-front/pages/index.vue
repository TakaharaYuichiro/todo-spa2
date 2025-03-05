<template>
  <div class="container">
    <Toolbar :message="message"/>

    <HomeRegistration 
      @trigger-registration="eventRegistrationComponent" 
      :categories="categories" 
      :currentUserInfo="currentUserInfo"/>

    <div class="listContainer">
      <HomeSearch 
        @trigger-search="eventSearchComponent" 
        :categories="categories"
        :users="users"/>
      <HomeEditor 
        @trigger-editor="eventRegistrationComponent" 
        ref="editorRef" 
        :todos="todos" 
        :categories="categories"
        :users="users"
        :currentUserInfo="currentUserInfo"/>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { auth } from '~/plugins/firebase'; 
  import { onAuthStateChanged } from "firebase/auth";
  import { ref, onMounted, onUnmounted } from 'vue';
  import axios from 'axios';
  import type { Category } from '~/types/category';
  import type { Todo } from '~/types/todo';
  import type { User } from '~/types/user';
  import editorComponent from '~/components/home/editor.vue';
  import { useRouter } from 'vue-router';
  import { fetchWithAuth } from "@/utils/api";

  const runtimeConfig = useRuntimeConfig();
  const API_URL = runtimeConfig.public.apiUrl;
  const router = useRouter();

  // ページアクセス時に認証チェック
  definePageMeta({
    middleware: "auth",
  });
  const message = ref('アカウント確認中');  
  const currentUserInfo = ref<User>({id:0, name:''});

  const eventRegistrationComponent = async() => {
    try {
      await readCategories();
      await readTodos();
    } catch (e){
        console.log("error!!", e);
    }
  };

  const editorRef = ref<InstanceType<typeof editorComponent> | null>(null);

  const eventSearchComponent = async (searchUserId:number, searchCategoryId:number, searchKeyword:string) => {
    try {
      const data = await fetchWithAuth(`${API_URL}/api/todo`);
      const allTodos: { id: number, userId:number, categoryId: number, content: string }[]
          = data.data.map((datum: any) => ({ id: datum.id, userId: datum. user_id, categoryId: datum.category_id, content: datum.content }))
      
      todos.value = allTodos.filter(todo=>
          todo.content.includes(searchKeyword) &&
          (searchCategoryId <= 0 || todo.categoryId === searchCategoryId) &&
          (searchUserId <= 0 || todo.userId === searchUserId)
      );
    } catch (error) {
        console.error("エラー", error);
    }
  };

  const todos = ref<Todo[]>([]);
  const readTodos = async() => {
    try {
      const resp = await fetchWithAuth(`${API_URL}/api/todo`);
      todos.value = resp.data.map((datum: {id:number, user_id:number, category_id:number, content:string} ) => ({
        id: datum.id,
        userId: datum.user_id, 
        categoryId: datum.category_id, 
        content: datum.content
      }));
    } catch (err) {
      console.log('読み込み失敗', err);
    }
  };

  const categories = ref<Category[]>([]);
  const readCategories = async() => {
    try {
      const resp = await fetchWithAuth(`${API_URL}/api/category`);
      categories.value = resp.data.map((datum: {id:number, name:string} ) => ({
        id: datum.id,
        name: datum.name,
      }));
    } catch (err) {
      console.log('読み込み失敗', err);
    }
  };

  const users = ref<User[]>([]);
  const readUsers = async() => {
    try {
      const resp = await fetchWithAuth(`${API_URL}/api/users`);
      users.value  = resp.map((datum: {id:number, name:string} ) => ({
        id: datum.id,
        name: datum.name,
      }));
    } catch (err) {
      console.log('読み込み失敗', err);
    }
  };

  let unsubscribe:any = null; // リスナーのクリーンアップ用

  onMounted(async() => {
    // Firebase認証のリスナーをセット
    unsubscribe = onAuthStateChanged(auth, async (currentUser) => {
      if (currentUser) {
        try {
          const idToken = await currentUser.getIdToken();
          const userInfo = await axios.post(`${API_URL}/api/username`, { idToken });
          message.value = `${userInfo.data.name}さんがログイン中です`;
          currentUserInfo.value = { id: userInfo.data.id, name: userInfo.data.name };

        } catch (error) {
        
          message.value = "ログイン情報の取得に失敗しました";
          currentUserInfo.value = {id:0, name:''};

          alert("ログイン情報の取得に失敗しました");

          try {
            await auth.signOut();
            router.replace('/login');
          } catch (error) {
            console.log("エラー", error);
          }
        }
      } else {
        // ログアウト状態
        message.value = "ログインしていません";
        currentUserInfo.value = {id:0, name:''};
      }
    });

    await readUsers();   
    await readCategories();
    await readTodos();
  });

  // コンポーネントが破棄される時にリスナーを解除
  onUnmounted(() => {
    if (unsubscribe) {
      unsubscribe();
    }
  });
</script>

<style scoped>
  .listContainer{
    width: 80%;
    margin: 10px auto;
    padding: 10px 10px;
    background: #f8f8f8;
  }
</style>