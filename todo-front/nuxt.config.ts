// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },

  plugins: [
    {src: '@/plugins/vee-validate.js'},
    {src: '~/plugins/firebase.ts' },
  ],

  runtimeConfig: {
    apiKey: '',
    public: {
        apiUrl: '',
    }
  },
})
