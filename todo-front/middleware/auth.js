import { auth } from '~/plugins/firebase'; 
import { useRouter } from "#app";

export default defineNuxtRouteMiddleware(() => {
  return new Promise((resolve) => {
    const router = useRouter();

    auth.onAuthStateChanged((user) => {
      if (!user) {
        router.push("/login"); // 未ログインならログインページへ
      }
      resolve();
    });
  });
});



