import { getAuth} from 'firebase/auth';
import { useRouter } from "#app";

export default defineNuxtRouteMiddleware(() => {
  return new Promise((resolve) => {
    const router = useRouter();

    const auth = getAuth();
    auth.onAuthStateChanged((user) => {
      if (!user) {
        router.push("/login"); // 未ログインならログインページへ
      }
      resolve();
    });
  });
});



