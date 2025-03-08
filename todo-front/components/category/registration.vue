<template>
  <div class="create-form">
    <div class="form-header">
      <NuxtLink to="/" class="form-header__button">戻る</NuxtLink>
    </div>
    <table class="create-form__table">
      <tbody>
        <tr>
          <th class="create-form__table__th__category">カテゴリ名</th>
          <th class="create-form__table__th__register" rowspan="2">
            <div class="create-form__button">
              <button ref="submit_button" class="create-form__button-submit" type="button"
                @click="storeCategory(newCategory)" :disabled="!isFormValid">
                カテゴリを登録
              </button>
            </div>
          </th>
        </tr>
        <tr>
          <td class="create-form__table__td">
            <input v-model="newCategory" class="create-form__item__input" type="text" name="カテゴリ"
              placeholder="新しいカテゴリを入力してください" />
          </td>
        </tr>
        <tr>
          <td class="create-form__table__td">
            <div class="form__error">{{ errorsNewCategory }}</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { defineEmits } from 'vue';
import { useField, useForm } from 'vee-validate';
import * as yup from 'yup';
import { fetchWithAuth } from "@/utils/api";

const runtimeConfig = useRuntimeConfig();
const API_URL = runtimeConfig.public.apiUrl;

interface FormValues {
  categoryId: number;
}

const schema = yup.object({
  newCategory: yup.string().max(191, 'カテゴリ名は191文字以下としてください').required('カテゴリ名は必須です'),
});

const { meta } = useForm<FormValues>({ validationSchema: schema });
const isFormValid = computed(() => meta.value.valid);

// 各フィールドのバリデーション設定
const { value: newCategory, errorMessage: errorsNewCategory, meta: metaNewCategory } = useField<string>('newCategory');

// 親ページへのイベント
const emits = defineEmits(['trigger-registration']);
const emitEvent = () => {
  emits('trigger-registration');
};

const storeCategory = async (categoryName: string) => {
  try {
    await fetchWithAuth(`${API_URL}/api/category/`, 'POST', {
      name: categoryName
    });

    // データ登録を親ページにイベントで伝える(さらに、親ページからeditorコンポーネントに伝わる)
    emitEvent();

    alert("カテゴリを登録しました！");
  } catch (error) {
    console.error("登録エラー", error);
  }
};
</script>

<style scoped>
.create-form {
  width: 100%;
}

.form-header {
  padding: 0 20px;
}

.form-header__button {
  color: #333;
  background-color: transparent;
  border: none;
  text-decoration: underline;
  font-size: 0.9rem;
}

.form-header__button:hover {
  color: #999;
}

.create-form__table {
  margin: 120px auto;
  width: 90%;
}

.create-form__table__th__category {
  vertical-align: bottom;
  color: #777;
}

.create-form__table__th__category div {
  margin: 0 2px;
}

.create-form__table__th__register {
  width: 180px;
  vertical-align: top;
  padding-top: 20px;
}

.create-form__table__td {
  vertical-align: top;
  padding: 5px;
  height: 30px;
}

.create-form__item {
  display: block;
}

.create-form__item__input {
  width: 95%;
  height: 30px;
}

.create-form__item__select {
  width: 95%;
  height: 30px;
}

.create-form__button {
  width: 95%;
  height: 50px;
}

.create-form__button-submit {
  padding: 1px 20px;
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 3px;
  background: #000;
  color: #fff;
  cursor: pointer;
  font-size: large;
}

.create-form__button-submit:disabled {
  background: #aaa;
  pointer-events: none;
}

.create-form__button-submit:hover {
  background: #666;
}

.form__error {
  height: 20px;
  color: #ff0000;
  text-align: left;
  font-size: small;
}
</style>