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
                <form class="filters">
                    <div class="input-group">
                        <label class="label"> جستجو (برحسب نام) </label>
                        <input type="text" class="input" v-model="filters.phrase">
                    </div>
                    <div class="input-group">
                        <label class="label"> وضعیت </label>
                        <select class="input" v-model="filters.state">
                            <option value=""> -- همه -- </option>
                            <option value="1"> خالی </option>
                            <option value="2"> خاموش </option>
                            <option value="3"> درحال‌پخت </option>
                            <option value="4"> پخته‌شده </option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="label"> مرتب‌سازی برحسب </label>
                        <select class="input" v-model="filters.order">
                            <option value="num"> شماره </option>
                            <option value="symbol"> نماد </option>
                            <option value="state" v-if="!filters.state"> وضعیت </option>
                        </select>
                    </div>
                    <div class="toggler">
                        <p class="mb-3"> توضیحات </p>
                        <toggler :object="filters" keyword="descriptions" />
                    </div>
                    <div>
                        <label class="bi bi-fire filter-i" :class="{selected:filters.fire}" for="fire"></label>
                        <input class="hidden" type="checkbox" id="fire" v-model="filters.fire">

                        <label class="bi bi-star-fill filter-i" for="mark" :class="{selected:filters.mark}"></label>
                        <input class="hidden" type="checkbox" id="mark" v-model="filters.mark">

                    </div>
                </form>
            </div>
        </div>

        <div class="section">
            <div class="tile table-container">
                <table v-if="list.length" class="bordered striped">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> نماد </th>
                            <th> اسم </th>
                            <th> ظرفیت </th>
                            <th> آجر </th>
                            <th> وضعیت </th>
                            <th> عملیات </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mounth, i in list">
                            <th> {{i+1}} </th>
                            <td>
                                {{mounth.symbol_name}}
                                <i v-if="mounth.fire" class="bi bi-fire bigger text-primary mx-1"></i>
                                <i v-if="mounth.mark" class="bi bi-star-fill bigger text-primary mx-1"></i>
                            </td>
                            <td :class="{'with-info':filters.descriptions}">
                                <span> {{mounth.name}} </span>
                                <p class="mounth-discription"> {{mounth.discription}} </p>
                            </td>
                            <td> {{mounth.quantity}} </td>
                            <td> {{mounth.brick_name}} </td>
                            <td> <span class="badge" :style="`--bg:${mounth.color}`"> {{mounth.persian_state}} </span> </td>
                            <td>
                                <Btn class="action" theme="success" @click="editMounth(i)"> <i class="bi bi-pencil-fill"></i> </Btn>
                                <Btn class="action" theme="warning" @click="destroyMounth(i)"> <i class="bi bi-trash-fill"></i> </Btn>
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
                            <select class="input" v-model="currentMounth.brick" @change="autoSelectCapacity">
                                <option value="T8"> تیغه ۸ </option>
                                <option value="T10"> تیغه ۱۰ </option>
                                <option value="T12"> تیغه ۱۲ </option>
                                <option value="T15"> تیغه ۱۵ </option>
                                <option value="FB"> فومدار بزرگ </option>
                                <option value="FS"> فومدار کوچک </option>
                                <option value="LS"> لیفتون قالب کوچک </option>
                                <option value="L5"> لیفتون ۵cm </option>
                                <option value="L55"> لیفتون ۵/۵cm </option>
                                <option value="LI"> لیفتون عراقی </option>
                                <option value="S25"> سقفی 25 </option>
                                <option value="S30"> سقفی 30 </option>
                                <option value="ETC"> متفرقه </option>
                            </select>
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
                                <option>A</option>
                                <option>B</option>
                                <option>C</option>
                                <option>D</option>
                                <option>E</option>
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
                                تایید
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
            filters : {phrase : '', state : '', order : 'num', fire:false, mark:false, descriptions : true},
        }
    },
    computed : {
        list : function () {

            var list = this.mounths;
            var filters = this.filters;
            if (filters.phrase) {
                list = list.filter( (el) => el.name.includes(filters.phrase) );
            }
            if (filters.state) {
                list = list.filter( (el) => el.state == filters.state );
            }
            if (filters.fire) {
                list = list.filter( (el) => el.fire );
            }
            if (filters.mark) {
                list = list.filter( (el) => el.mark );
            }

            if (filters.order == 'state' && filters.state) {
                this.filters.order = 'num';
            }
            if (filters.order) {
                list = list.sort( (e1, e2) => (e1[filters.order] > e2[filters.order]) ? 1 : (e1[filters.order] < e2[filters.order]) ? -1 : 0 );
            }

            return list;
        }
    },
    methods : {
        autoSelectCapacity : function () {
            this.currentMounth.quantity = null;
            if( this.currentMounth.brick == 'T8' ) this.currentMounth.quantity = 13000;
            if( this.currentMounth.brick == 'T10') this.currentMounth.quantity = 12000;
            if( this.currentMounth.brick == 'T12') this.currentMounth.quantity = 8500;
            if( this.currentMounth.brick == 'T15') this.currentMounth.quantity = 8000;
            if( this.currentMounth.brick == 'FB' ) this.currentMounth.quantity = 4000;
            if( this.currentMounth.brick == 'FS' ) this.currentMounth.quantity = 8000;
            if( this.currentMounth.brick == 'LS' ) this.currentMounth.quantity = 26000;
            if( this.currentMounth.brick == 'L5' ) this.currentMounth.quantity = 18000;
            if( this.currentMounth.brick == 'L55') this.currentMounth.quantity = 18000;
            if( this.currentMounth.brick == 'S25') this.currentMounth.quantity = 1800;
            if( this.currentMounth.brick == 'S30') this.currentMounth.quantity = 1400;
        },
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
    }
}
</script>

<style lang="css">

.filters {
    display: flex;
    align-items: center;
    justify-content: space-around;
}

.filters > div {
    padding: 0 16px;
}

.filters > .input-group {
    width: 20%;
}

.filters > .toggler {
    width: 10%;
    text-align: center;
}

.filter-i {
    cursor: pointer;
    color: var(--gray);
    font-size: 2.25rem;
    margin: 0 8px;
    transition: 0.3s;
}

.filter-i::before {
    transition: 0.2s;
}

.filter-i:hover::before {
    transform: scale(1.2);
}

.filter-i.selected {
    color: var(--primary);
}

.filter-i:active {
    color: var(--primary-darken-2);
}

.mounth-discription {
    display: none;
}

.with-info span {
    font-weight: bold;
}

.with-info .mounth-discription {
    display: block;
    font-size: 14px;
}

.action:not(:last-child) {
    margin-left: 8px;
}

.action {
    margin: 0 4px;
}

.action i {
    font-size: 1.25rem;
}

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

@media only screen and (max-width: 1080px) {

    .filters {
        flex-wrap: wrap;
    }
    .filters > .input-group {
        width: 50%;
    }
    .filters > .toggler {
        width: 50%;
    }
}

@media only screen and (max-width: 840px) {

    .filters > .input-group {
        width: 100%;
    }

}

</style>
