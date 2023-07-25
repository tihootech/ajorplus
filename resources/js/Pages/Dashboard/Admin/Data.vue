<template>
    <dashboard-layout title="مدیریت داده‌ها">

        <div class="section heading">
            <div class="tile">
                <h2> مدیریت داده‌ها </h2>
                <Btn icon="plus" type="button" @click="createMounth"> تعریف داده جدید </Btn>
            </div>
        </div>

        <div class="section">
            <div class="tile">
                <table v-if="mounths.length" class="bordered striped">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> نماد </th>
                            <th> اسم </th>
                            <th> ظرفیت </th>
                            <th> وضعیت </th>
                            <th> عملیات </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mounth, i in mounths">
                            <th> {{i+1}} </th>
                            <td> {{mounth.symbol_name}}
                                <i v-if="mounth.fire" class="bi bi-fire bigger text-primary mx-1"></i>
                                <i v-if="mounth.mark" class="bi bi-star-fill bigger text-primary mx-1"></i>
                            </td>
                            <td> {{mounth.name}} </td>
                            <td> {{mounth.quantity}} </td>
                            <td> {{mounth.persian_state}} </td>
                            <td>
                                <Btn class="action" theme="success" @click="editMounth(i)"> <i class="bi bi-pencil"></i> </Btn>
                                <Btn class="action" theme="warning" @click="destroyMounth(i)"> <i class="bi bi-trash"></i> </Btn>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="alert-warning">
                    موردی یافت نشد!
                </div>
            </div>
        </div>


        <Transition name="bounce">
            <div v-if="modalIsOpen" class="modal">
                <div class="modal-content">
                    <span class="close" @click="modalIsOpen=false">&times;</span>
                    <form v-if="currentMounth" class="mounth-form" @submit.prevent="saveMounth">
                        <div class="input-group">
                            <label class="label"> آجر </label>
                            <input type="text" class="input" v-model="currentMounth.brick">
                        </div>
                        <div class="input-group">
                            <label class="label"> نام </label>
                            <input required type="text" class="input" v-model="currentMounth.name">
                        </div>
                        <div class="input-group">
                            <label class="label"> ظرفیت </label>
                            <input required type="text" class="input" v-model="currentMounth.quantity">
                        </div>
                        <div class="input-group">
                            <label class="label"> نماد </label>
                            <select class="input" v-model="currentMounth.symbol">
                                <option>FA</option>
                                <option>FB</option>
                                <option>FC</option>
                                <option>FD</option>
                                <option>FE</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="label"> وضعیت </label>
                            <select class="input" v-model="currentMounth.state">
                                <option value="1"> خالی </option>
                                <option value="2"> خاموش </option>
                                <option value="3"> درحال‌پخت </option>
                                <option value="4"> پخته‌شده </option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="label"> توضیحات </label>
                            <textarea v-model="currentMounth.discription" class="input" rows="6"></textarea>
                        </div>
                        <div class="input-group booleans">
                            <i class="bi bi-fire bool-icon" @click="currentMounth.fire = !currentMounth.fire">
                                <i v-show="currentMounth.fire" class="bi bi-check-circle-fill">
                            </i>
                            </i>
                            <i class="bi bi-star-fill bool-icon" @click="currentMounth.mark = !currentMounth.mark">
                                <i v-show="currentMounth.mark" class="bi bi-check-circle-fill">
                            </i>
                        </i>
                        </div>
                        <div class="input-group">
                            <Btn icon="check-circle-fill" :loading="saving" type="submit" block>
                                تایید و ذخیره
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
    props : ['mounths'],
    data : function () {
        return {
            saving : false,
            currentMounth : null,
            currentIndex : -1,
            modalIsOpen : false,
        }
    },
    methods : {
        createMounth : function () {
            this.currentMounth = {id : 0};
            this.modalIsOpen = true;
        },
        editMounth : function (index) {
            this.currentMounth = this.mounths[index];
            this.currentIndex = index;
            this.modalIsOpen = true;
        },
        saveMounth : function () {
            this.saving = true;
            var url = this.currentMounth.id ? route('mounth.update', this.currentMounth) : route('mounth.store');
            var method = this.currentMounth.id ? 'put' : 'post';
            var data = this.currentMounth;
            axios({url, method, data}).then(res => {
                if (res.data.success) {
                    this.swalSuccess();
                    if (!this.currentMounth.id) {
                        this.mounths.unshift(res.data.mounth);
                    }else {
                        this.mounths[this.currentIndex] = res.data.mounth;
                        this.currentIndex = -1;
                    }
                    this.currentMounth = null;
                    this.modalIsOpen = false;
                }else {
                    this.swalError(res.data.message);
                }
            }).catch(err => this.swalGeneralErrors(err)).finally(this.saving = false);
        },
        destroyMounth : function (index) {
            var mounth = this.mounths[index];
            var url = route('mounth.destroy', mounth);
            this.swalAreYouSure(url, this.mounths, index);
        }
    },
    watch : {

    }
}
</script>

<style lang="css">

.mounth-form {
    text-align: right;
    display: flex;
    flex-wrap: wrap;
    align-items : flex-start;
    justify-content: center;
}

.mounth-form > .input-group {
    width: 50%;
}

.mounth-form > .input-group:nth-child(n+3) {
    width: 33%;
}

.mounth-form > .input-group:nth-last-child(-n+3) {
    width: 100%;
}

.mounth-form > .input-group:last-child {
    width: 50%;
}

.action:not(:last-child) {
    margin-left: 8px;
}

.action {
    margin: 0 4px;
}

.booleans {
    text-align: center;
    padding: 0 !important;
    margin: 0 !important;
}

.booleans > .bool-icon {
    cursor: pointer;
    font-size: 3rem;
    color: var(--gray);
    position: relative;
    margin: 0 12px;
}

.booleans > .bool-icon:before {
    transition: 0.3s;
}

.booleans > .bool-icon:hover::before {
    transform: scale(1.25);
}

.booleans > .bool-icon:active::before {
    color: var(--primary-darken-2);
}

.booleans > .bool-icon > i {
    color: var(--primary);
    font-size: 1.5rem;
    position: absolute;
    top: -10px;
    left: -10px;
}

</style>
