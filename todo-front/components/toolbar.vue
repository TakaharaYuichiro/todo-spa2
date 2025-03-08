<template>
  <div class="toolbar">
    <div class="currentUserInfo">{{ message }}</div>
    <button class="logoutButton" @click="logout">ログアウト</button>
  </div>
</template>

<script setup lang="ts">
import { getAuth } from 'firebase/auth';
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = async () => {
  try {
    await getAuth().signOut();
    router.replace('/login');
  } catch (error) {
    console.log("エラー", error);
  }
};

defineProps<{ message: string }>();
</script>

<style scoped>
.toolbar {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 2px 20px;
}

.currentUserInfo {
  margin-right: 10px;
  color: #333;
}

.logoutButton {
  color: #333;
  background-color: transparent;
  border: none;
  text-decoration: underline;
}

.logoutButton:hover {
  color: #999;
}
</style>
