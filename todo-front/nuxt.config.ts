// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },

  plugins: [
    {src: '@/plugins/vee-validate.js'},
    {src: '~/plugins/firebase.ts' },
  ],

  runtimeConfig: {
    // apiKey: '',
    apiKey: process.env.FIREBASE_API_KEY, // サーバーサイド専用のキー（公開しない）
    public: {
        apiUrl: '',
        FIREBASE_API_KEY: process.env.NUXT_PUBLIC_FIREBASE_API_KEY,
        FIREBASE_AUTH_DOMAIN: process.env.NUXT_PUBLIC_FIREBASE_AUTH_DOMAIN,
        FIREBASE_PROJECT_ID: process.env.NUXT_PUBLIC_FIREBASE_PROJECT_ID,
        FIREBASE_STORAGE_BUCKET: process.env.NUXT_PUBLIC_FIREBASE_STORAGE_BUCKET,
        FIREBASE_MESSAGING_SENDER_ID: process.env.NUXT_PUBLIC_FIREBASE_MESSAGING_SENDER_ID,
        FIREBASE_APP_ID: process.env.NUXT_PUBLIC_FIREBASE_APP_ID,
    },
  },
})
