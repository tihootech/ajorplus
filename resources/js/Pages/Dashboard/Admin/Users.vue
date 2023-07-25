<template>
    <dashboard-layout title="مدیریت سطوح دسترسی">

        <div class="section heading">
            <div class="tile">
                <h2> مدیریت سطوح دسترسی </h2>
                <Btn icon="plus" type="button" @click="createUser"> تعریف کاربر جدید </Btn>
            </div>
        </div>

        <div class="section">
            <div class="tile">
                <table v-if="users.length" class="bordered striped">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> نام </th>
                            <th> ایمیل </th>
                            <th> سطح دسترسی </th>
                            <th> تاریخ تعریف </th>
                            <th> عملیات </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user, i in users">
                            <th> {{i+1}} </th>
                            <td> {{user.name}} </td>
                            <td> {{user.email}} </td>
                            <td> {{user.role}} </td>
                            <td> {{pDate(user.created_at)}} </td>
                            <td>
                                <Btn class="action" theme="success" @click="editUser(i)"> <i class="bi bi-pencil"></i> </Btn>
                                <Btn class="action" theme="warning" @click="destroyUser(i)"> <i class="bi bi-trash"></i> </Btn>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="alert-warning">
                    به جز شما، هیچ کاربر دیگری وجود ندارد!
                </div>
            </div>
        </div>


        <Transition name="bounce">
            <div v-if="modalIsOpen" class="modal">
                <div class="modal-content">
                    <span class="close" @click="modalIsOpen=false">&times;</span>
                    <form v-if="currentUser" class="user-form" @submit.prevent="saveUser">
                        <div class="input-group">
                            <label class="label"> نام </label>
                            <input type="text" class="input" v-model="currentUser.name">
                        </div>
                        <div class="input-group">
                            <label class="label"> ایمیل </label>
                            <input required type="email" class="input" v-model="currentUser.email">
                        </div>
                        <div class="input-group">
                            <label class="label"> سطح دسترسی </label>
                            <select class="input" v-model="currentUser.role">
                                <option value="viewer"> Viewer </option>
                                <option value="admin"> Admin </option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="label"> رمزعبور </label>
                            <input :required="!currentUser.id" type="text" class="input" v-model="currentUser.password">
                            <small class="text-muted" v-if="currentUser.id"> اگر تمایل به تغییر رمز دارید، رمز جدید را وارد کنید. درغیراین‌صورت خالی بگذارید </small>
                        </div>
                        <div class="input-group">
                            <Btn :icon="currentUser.id ? 'check-circle-fill' : 'person-plus'" :loading="saving" type="submit" block>
                                <span v-if="currentUser.id"> ویرایش </span>
                                <span v-else> تعریف </span>
                            </Btn>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>

    </dashboard-layout>
</template>

<script>

import { defineComponent } from 'vue';
import DashboardLayout from '@/DashboardLayout.vue';

export default {
    components: { DashboardLayout },
    props : ['users'],
    data : function () {
        return {
            saving : false,
            currentUser : null,
            modalIsOpen : false,
        }
    },
    methods : {
        createUser : function () {
            this.currentUser = {id : 0};
            this.modalIsOpen = true;
        },
        editUser : function (index) {
            this.currentUser = this.users[index];
            this.modalIsOpen = true;
        },
        saveUser : function () {
            this.saving = true;
            var url = this.currentUser.id ? route('user.update', this.currentUser) : route('user.store');
            var method = this.currentUser.id ? 'put' : 'post';
            var data = this.currentUser;
            axios({url, method, data}).then(res => {
                if (res.data.success) {
                    this.swalSuccess();
                    if (!this.currentUser.id) {
                        this.users.unshift(res.data.user);
                    }
                    this.currentUser = null;
                    this.modalIsOpen = false;
                }else {
                    this.swalError(res.data.message);
                }
            }).catch(err => this.swalGeneralErrors(err)).finally(this.saving = false);
        },
        destroyUser : function (index) {
            var user = this.users[index];
            var url = route('user.destroy', user);
            this.swalAreYouSure(url, this.users, index);
        }
    },
    watch : {

    }
}
</script>

<style lang="css">

.user-form {
    text-align: right;
    display: flex;
    flex-wrap: wrap;
    align-items : flex-start;
    justify-content: center;
}

.user-form > .input-group {
    width: 50%;
}

.action:not(:last-child) {
    margin-left: 8px;
}

.action {
    margin: 0 4px;
}

</style>
