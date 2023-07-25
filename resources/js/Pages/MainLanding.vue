<template>
    <main-layout title="صفحه اصلی">

        <div class="container">
            <div class="card">
                <h3 class="heading"> درحال‌حاضر صفحه لندینگ موجود نیست! </h3>
                <Link :href="route('hotels')" class="button">
                    لیست هتل‌ها
                 </Link>
            </div>
        </div>

    </main-layout>
</template>

<script>

import { defineComponent } from 'vue';
import MainLayout from '@/MainLayout.vue';

export default {
    components: { MainLayout },
}
</script>

<style lang="css" scoped>

.container {
     height: 100vh;
     display: grid;
     place-items: center;
}
.card  {
     min-width: 300px;
     min-height: 400px;
     border-radius: 30px;
     background: #f2f2f2;
     box-shadow: 15px 15px 30px #bebebe,  -15px -15px 30px #ffffff;
     padding: 25px;
     display: flex;
     flex-direction: column;
     text-align: center;
     justify-content: center;
     align-items: center;
 }

 .heading {
     color: rgb(141, 198, 62);
     text-shadow: 2px 2px 8px #d1d1d1;
 }
 .button {
     display: inline-block;
     cursor: pointer;
     position: relative;
     padding: 10px 24px;
     font-size: 18px;
     color: rgb(141, 198, 62);
     border: 2px solid rgb(141, 198, 62);
     border-radius: 34px;
     background-color: transparent;
     font-weight: 600;
     transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
     overflow: hidden;
     text-decoration: none;
 }

 .button::before {
     content: '';
     position: absolute;
     inset: 0;
     margin: auto;
     width: 50px;
     height: 50px;
     border-radius: inherit;
     scale: 0;
     z-index: -1;
     background-color: rgb(141, 198, 62);
     transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
 }

 .button:hover::before {
     scale: 3;
 }

 .button:hover {
     color: #fff;
     scale: 1.1;
     box-shadow: 0 0px 20px rgba(141, 198, 62,0.4);
 }

 .button:active {
     scale: 1;
 }

</style>
