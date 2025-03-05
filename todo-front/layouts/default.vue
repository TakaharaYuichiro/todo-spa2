<template>
  <div>
    <header class="header">
      <div class="header__inner">
        <div class="header__logo">
          ToDo
        </div>
      </div>
    </header>
    <slot />
  </div>
</template>

<style>
  body {
      margin: 0;
      font-family: 'Meiryo',"ヒラギノ角ゴ Pro W3","ＭＳ Ｐゴシック","Osaka", sans-serif;
  }
  .header {
      background: #000;
  }
  .header__inner {
      margin: 0 auto;
      padding: 20px 15px;
  }
  .header__logo {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      font-size: 24px;
  }

    
  /* safariにおけるselectの表示調整 */
  select{
    -moz-appearance: none;
    -webkit-appearance: none;
  }
</style>