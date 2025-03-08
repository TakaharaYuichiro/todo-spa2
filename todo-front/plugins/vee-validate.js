// import Vue from 'vue';
// import { ValidationProvider, ValidationObserver, extend, localize } from 'vee-validate';
// import * as originalRules from 'vee-validate/dist/rules';
// import ja from 'vee-validate/dist/locale/ja.json';

// // 全てのルールをインポート
// let rule;
// for (rule in originalRules) {
//     extend(rule, {
//         ...originalRules[rule],
//     });
// }

// // 日本語に設定
// localize('ja', ja);

// Vue.component('ValidationProvider', ValidationProvider);
// Vue.component('ValidationObserver', ValidationObserver);





import { defineNuxtPlugin } from '#app'
import { defineRule, configure } from 'vee-validate'
import { required, email } from '@vee-validate/rules'
import { localize } from '@vee-validate/i18n'
import ja from '@vee-validate/i18n/dist/locale/ja.json'

export default defineNuxtPlugin((nuxtApp) => {
  // ルールを定義
  defineRule('required', required)
  defineRule('email', email)

  // 日本語のバリデーションメッセージを設定
  configure({
    generateMessage: localize({
      ja
    }),
  })
})


