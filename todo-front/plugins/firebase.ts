import { initializeApp, getApps, getApp } from 'firebase/app';
import { getAuth } from 'firebase/auth';

const firebaseConfig = {
    apiKey: "AIzaSyDgvHdDQj8H5OJbcJcN5V8r-NgvYAg--5I",
    authDomain: "todospa-ae72e.firebaseapp.com",
    projectId: "todospa-ae72e",
    storageBucket: "todospa-ae72e.firebasestorage.app",
    messagingSenderId: "453868303112",
    appId: "1:453868303112:web:bce79eac0bef9d0b9872bd"
};

// Firebase アプリの初期化（重複を防ぐ）
const app = !getApps().length ? initializeApp(firebaseConfig) : getApp();
const auth = getAuth(app);
export{auth};

export default defineNuxtPlugin(() => {  
  return {
    provide: {
      auth,
    },
  };
});
