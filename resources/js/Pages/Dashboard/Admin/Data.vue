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
                            <!-- <template v-if="mounth.id"> -->
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
                            <!-- </template> -->
                        </tr>
                    </tbody>
                </table>
                <div v-else class="alert-warning">
                    موردی یافت نشد!
                </div>
            </div>
        </div>

        <MounthModal :list="mounths" :index="currentIndex" @close="closeModal" />

    </dashboard-layout>
</template>

<script>

import { defineComponent } from 'vue';
import DashboardLayout from '@/DashboardLayout.vue';
import MounthModal from './Fragments/MounthModal.vue';

export default {
    components: { DashboardLayout, MounthModal },
    props : ['mounths'],
    data : function () {
        return {
            saving : false,
            currentIndex : -1,
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
        createMounth : function () {
            this.mounths.unshift({id:0});
            this.currentIndex = 0;
        },
        editMounth : function (index) {
            this.currentIndex = index;
        },
        closeModal : function (newMounth) {
            if (newMounth) {
                this.mounths[this.currentIndex] = newMounth;
            }else {
                if (!this.mounths[0].id) {
                    this.mounths.splice(0, 1);
                }
            }
            this.currentIndex = -1;
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
