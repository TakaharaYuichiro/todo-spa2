<template>
  <div class="create-form">
    <table class="create-form__table">
      <tbody>
        <tr>
          <th class="create-form__table__th__todo">ToDo</th>
          <th class="create-form__table__th__category">
            <div class="create-form__table__th__category__text">カテゴリ</div>
            <nuxt-link class="create-form__table__th__category__button" to="/category">編集</nuxt-link>
          </th>
          <th class="create-form__table__th__register" rowspan="2">
            <div class="create-form__button">
              <button class="create-form__button-submit" type="button" @click="storeTodo(newTodo)"
                :disabled="!isFormValid">
                新規ToDo登録
              </button>
            </div>
          </th>
        </tr>

        <tr>
          <td class="create-form__table__td">
            <input v-model="newTodo" class="create-form__item__input" type="text" name="ToDo"
              placeholder="新しいToDoを入力してください" @blur="metaNewTodo.touched = true" />
          </td>
          <td class="create-form__table__td">
            <select v-model="categoryId" class="create-form__item__select">
              <option value="0">カテゴリなし</option>
              <option v-for="category in categories" v-bind:value="category.id">
                {{ category.name }}
              </option>
            </select>
          </td>
        </tr>

        <tr>
          <td class="create-form__table__td">
            <div class="form__error">{{ errorsNewTodo }}</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick, computed } from 'vue';
import { useField, useForm } from 'vee-validate';
import * as yup from 'yup';
import type { Category } from '~/types/category';
import type { User } from '~/types/user';

const runtimeConfig = useRuntimeConfig();
const API_URL = runtimeConfig.public.apiUrl;

interface FormValues {
  newTodo: string;
  categoryId: number;
}

const schema = yup.object({
  newTodo: yup.string().max(191, 'Todoは191文字以内としてください').required('ToDoは必須です'),
});

const { meta } = useForm<FormValues>({ validationSchema: schema });
const isFormValid = computed(() => meta.value.valid);

// 各フィールドのバリデーション設定
const { value: newTodo, errorMessage: errorsNewTodo, meta: metaNewTodo } = useField<string>('newTodo');

const categoryId = ref(0);

const props = defineProps<{
  categories: Category[] | null;
  currentUserInfo: User;
}>();

const categories = computed(() => props.categories ?? []);
const currentUserInfo = computed(() => props.currentUserInfo ?? { id: 0, name: '' });

// 親ページへのイベント
const emits = defineEmits(['trigger-registration']);
const emitEvent = () => emits('trigger-registration');

const storeTodo = async (newTodo: string): Promise<void> => {
  // ユーザーチェック
  if (currentUserInfo === null || currentUserInfo === undefined) {
    console.error("登録エラー", "ユーザー情報がありません");
    return;
  }

  // ToDoデータをデータベースに登録
  const adjustedCategoryId = categoryId.value == 0 ? null : categoryId.value;
  try {
    await fetchWithAuth(`${API_URL}/api/todo/`, 'POST', {
      user_id: currentUserInfo.value.id,
      category_id: adjustedCategoryId,
      content: newTodo
    });

    // データ登録を親ページにイベントで伝える
    emitEvent();
    alert("Todoを登録しました！");
  } catch (error) {
    console.error("登録エラー", error);
  }
}

onMounted(() => {
  nextTick(() => {
    if (categories.value.length > 0) {
      categoryId.value = categories.value[0].id;
    }
  });
});
</script>

<style scoped>
.create-form {
  margin: 120px auto;
  width: 90%;
}

.create-form__table {
  width: 100%;
}

.create-form__table__th__todo {
  vertical-align: bottom;
  color: #777;
}

.create-form__table__th__category {
  display: flex;
  justify-content: center;
  align-items: end;
}

.create-form__table__th__category__text {
  color: #777;
  margin-right: 25px;
}

.create-form__table__th__category__button {
  border: none;
  background: transparent;
  text-decoration: underline;
  font-size: small;
  color: #666;
}

.create-form__table__th__category__button:hover {
  color: #edc;
}

.create-form__table__th__register {
  width: 180px;
  vertical-align: top;
  padding-top: 20px;
}

.create-form__button {
  width: 100%;
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
  height: 37px;
}

.form__error {
  height: 20px;
  color: #ff0000;
  text-align: left;
  font-size: small;
}
</style>