<template>
  <div class="main-content">
    <div class="form__heading">
      <h2>ログイン</h2>
    </div>

    <form @submit.prevent="login" class="form">
      <div class="form__group">
        <input v-model="email" class="form__input" type="email" name="メールアドレス" placeholder="example@ex.com"
          @blur="metaEmail.touched = true" />
        <div class="form__error" v-if="metaEmail.touched && errorsEmail">{{ errorsEmail }}</div>
      </div>

      <div class="form__group">
        <input v-model="password" class="form__input" type="password" name="パスワード" placeholder="password"
          @blur="metaPassword.touched = true" />
        <div class="form__error" v-if="metaPassword.touched && errorsPassword">{{ errorsPassword }}</div>
      </div>
      <div class="form__button">
        <button class="form__button-submit" type="submit" :disabled="!isFormValid">ログイン</button>
      </div>
    </form>

    <div class="login-register-switching">
      <p>
        <span>アカウントをお持ちでない方はこちらから</span><br>
        <NuxtLink to="/register">会員登録</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { getAuth, signInWithEmailAndPassword } from 'firebase/auth';
import { useField, useForm } from 'vee-validate';
import * as yup from 'yup';
import { useRouter } from 'vue-router';
import { computed } from 'vue';

const router = useRouter();

interface FormValues {
  email: string;
  password: string;
}
const schema = yup.object({
  email: yup.string().email('有効なメールアドレスを入力してください').required('メールアドレスは必須です'),
  password: yup.string().min(8, 'パスワードは8文字以上必要です').required('パスワードは必須です')
});

const { meta } = useForm<FormValues>({ validationSchema: schema });
const isFormValid = computed(() => meta.value.valid);

// 各フィールドのバリデーション設定
const { value: email, errorMessage: errorsEmail, meta: metaEmail } = useField<string>('email');
const { value: password, errorMessage: errorsPassword, meta: metaPassword } = useField<string>('password');

// フォーム送信処理
const login = async () => {
  try {
    const userCredential = await signInWithEmailAndPassword(getAuth(), email.value, password.value);
    router.push('/');
  } catch (error: any) {
    alert('ログインに失敗しました: ' + error.message);
  }
};
</script>

<style scoped>
.main-content {
  margin: 0 auto;
  padding: 30px 15px;
  max-width: 1230px;
}

.form__heading {
  padding: 20px;
  text-align: center;
}

.form {
  margin: 0 auto;
  max-width: 470px;
  text-align: center;
  padding: 30px;
}

.form__group {
  width: 100%;
  height: 70px;
  margin-bottom: 5px;
}

.form__input {
  padding: 10px;
  width: 90%;
  height: 25px;
  border: 1px solid #bbb;
  border-radius: 3px;
  appearance: none;
  background: #F2F2F2;
}

.form__error {
  height: 20px;
  color: #ff0000;
  text-align: left;
  font-size: 0.8rem;
}

.form__button {
  margin-top: 20px;
}

.form__button-submit {
  padding: 10px;
  width: 50%;
  height: 40px;
  border: none;
  background: #214BE0;
  color: #fff;
  cursor: pointer;
  font-size: 0.9rem;
  white-space: nowrap;
}

.form__button-submit:hover {
  background: #5770cd;
}

.form__button-submit:disabled {
  background: #aaa;
}

.login-register-switching {
  text-align: center;
}

.login-register-switching a {
  color: #214BE0;
  text-decoration: none;
}
</style>