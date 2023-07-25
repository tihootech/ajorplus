<template>
    <main-layout title="ورود به حساب‌کاربری">

        <form class="container" @submit.prevent="login">
            <div class="card tile">
                <div class="text-center">
                    
                </div>
                <div class="input-group">
                    <label class="label"> ایمیل </label>
                    <input required type="email" name="text" class="input" v-model="inputs.email">
                </div>
                <div class="input-group">
                    <i class="bi pass-toggle" :class="passVisible ? 'bi-eye-slash' : 'bi-eye'" @click="passVisible = !passVisible"></i>
                    <label class="label"> رمزعبور </label>
                    <input required :type="passVisible ? 'text' : 'password'" name="password" class="input" v-model="inputs.password">
                </div>
                <div class="text-center py-2">
                    <Btn icon="box-arrow-in-left" :loading="loading"> ورود به حساب‌کاربری </Btn>
                </div>
            </div>
        </form>

        <AnimatedBackgroundN1 />

    </main-layout>
</template>

<script>

import { defineComponent } from 'vue';
import MainLayout from '@/MainLayout.vue';
import AnimatedBackgroundN1 from '@/GlobalComponents/AnimatedBackgroundN1.vue';

export default {
    components: { MainLayout, AnimatedBackgroundN1 },
    props : [],
    data : function () {
        return {
            inputs : {email : '', password : ''},
            loading : false,
            passVisible : false,
            redirecting : false,
        }
    },
    methods : {
        login : function () {
            var url = route('do_login');
            this.loading = true;
            axios.post(url, this.inputs).then(res => {
                if (res.data.succes) {
                    this.redirecting = true;
                    this.swalSuccess(res.data.message);
                    this.openRoute('dashboard');
                }else {
                    this.swalError(res.data.message);
                }
            }).catch(err => this.swalGeneralErrors(err)).finally(x => this.loading = false);
        }
    }
}
</script>

<style lang="css">

.container {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container > .card {
    min-width: 500px;
}

.container > .logo-img {
    position: absolute;
    top: 25px;
    left: 25px;
}

.pass-toggle {
    position: absolute;
    top: 50%;
    left: 15px;
    font-size: 1.5rem;
    color: var(--primary);
    cursor: pointer;
    transition: .2s;
}

.pass-toggle:hover {
    transform: scale(1.25);
}

.pass-toggle:active {
    color: var(--primary-darken-2);
}

</style>
