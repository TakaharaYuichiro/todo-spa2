import { getAuth } from "firebase/auth";

async function getCsrfToken() {
  const runtimeConfig = useRuntimeConfig();
  const API_URL = runtimeConfig.public.apiUrl;

  await fetch(`${API_URL}/sanctum/csrf-cookie`, {
    credentials: "include", // クッキーを送信
  });

  // クッキーから CSRF トークンを取得
  const csrfToken = useCookie("XSRF-TOKEN")?.value;
  return csrfToken;
}

export async function fetchWithAuth(url: string, method: string = "GET", data?: any) {
  const auth = getAuth();
  const user = auth.currentUser;
  if (!user) {
    throw new Error("User is not authenticated");
  }

  // Firebase ID トークンを取得
  const idToken = await user.getIdToken();

  // Laravel の CSRF トークンを取得
  const csrfToken = await getCsrfToken();

  // ヘッダーを設定
  const headers: Record<string, string> = {
    "Authorization": `Bearer ${idToken}`, // Firebase 認証
    "Content-Type": "application/json",
    "X-XSRF-TOKEN": csrfToken || "", // Laravel の CSRF トークン
  };

  // HTTP リクエストオプション
  const options: RequestInit = {
    method,
    headers,
    credentials: "include", // 🍪 クッキー送信 (セッション維持のため)
    body: data ? JSON.stringify(data) : undefined,
  };

  const response = await fetch(url, options);

  if (!response.ok) {
    throw new Error(`HTTP error! Status: ${response.status}`);
  }

  return response.json();
}
