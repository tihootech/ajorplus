<template>
    <dashboard-layout title="وضعیت قمیرها">

        <div class="section heading">
            <div class="tile">
                <h2 v-if="from == 'home'"> نگاه کلی </h2>
                <h2 v-else> قیمرها </h2>
                <div class="forges-navigator">
                    <Btn v-for="symbol in symbols" tag="a" class="mx-1" :href="`#section-${symbol}`"> {{symbol}} </Btn>
                </div>
            </div>
        </div>

        <div v-if="from == 'home'" class="section">
            <div v-for="item in list" :id="`section-${item}`">
                <div class="overview">
                    <div class="tile">
                        <h3 class="mb-4"> وضعیت هر دهانه <span v-if="item != all"> قیمر {{item}} </span> </h3>
                        <div class="mounth-stats">
                            <div v-for="n in 4">
                                <span v-if="n == 1"> خالی </span>
                                <span v-if="n == 2"> خاموش </span>
                                <span v-if="n == 3"> درحال‌پخت </span>
                                <span v-if="n == 4"> پخته‌شده </span>
                                <b> {{charts.all[n]}} </b>
                                <progress :value="charts[item][n]" :max="totals['all']" :class="`stat-${n}`"></progress>
                            </div>
                        </div>
                    </div>
                    <div class="tile">
                        <h3 v-if="item == 'all'" class="mb-4"> وضعیت کلی قمیرها </h3>
                        <h3 v-else class="mb-4"> وضعیت کلی قمیر {{item}} </h3>
                        نمایش چارت...
                    </div>
                    <div class="tile">
                        <h3 class="mb-4"> وضعیت آجرها </h3>
                        <div>
                            <span> آجر 3.6 </span> <i class="bi bi-arrow-left text-primary bigger"></i> <b>{{counts[item]['3.6']}}</b>
                            <hr class="devider">
                            <span> 5.6 آجر </span> <i class="bi bi-arrow-left text-primary bigger"></i> <b>{{counts[item]['5.6']}}</b>
                            <hr class="devider">
                            <span> لیفتون </span> <i class="bi bi-arrow-left text-primary bigger"></i> <b>{{counts[item]['L']}}</b>
                        </div>
                    </div>
                </div>
                <div v-if="item != 'all'" class="forges">
                    <span class="forge-arrow"></span>
                    <div class="forge-parts">
                        <div class="forge-part">
                            <span v-for="n in forgeList[item].part1" class="forge-square" :style="`--w:${100/forgeList[item].part1.length}%;--bg:${forges[item][n-1].color}`"
                            @click="openModal(forges[item][n-1])">
                                <i v-if="forges[item][n-1].fire" class="bi bi-fire fire"></i>
                                <i v-if="forges[item][n-1].mark" class="bi bi-star-fill mark"></i>
                                <b class="forge-number"> {{faNumber(n)}} </b>
                            </span>
                        </div>
                        <p class="forge-label">{{item}}</p>
                        <div class="forge-part">
                            <span v-for="n in forgeList[item].part2" class="forge-square" :style="`--w:${100/forgeList[item].part1.length}%;--bg:${forges[item][n-1].color}`"
                            @click="openModal(forges[item][n-1])">
                                <i v-if="false" class="bi bi-fire"></i>
                                <i v-if="false" class="bi bi-mark"></i>
                                <b class="forge-number"> {{faNumber(n)}} </b>
                            </span>
                        </div>
                    </div>
                    <span class="forge-arrow"></span>
                </div>
                <hr class="big-hr">
            </div>
        </div>

        <div v-else class="forges-container">
            <div v-for="mounths, symbol in forges">
                <div class="forges">
                    <span class="forge-arrow"></span>
                    <div class="forge-parts">
                        <div class="forge-part">
                            <span v-for="n in forgeList[symbol].part1" class="forge-square" :style="`--w:${100/forgeList[symbol].part1.length}%;--bg:${mounths[n-1].color}`"
                            @click="openModal(mounths[n-1])">
                                <i v-if="mounths[n-1].fire" class="bi bi-fire fire"></i>
                                <i v-if="mounths[n-1].mark" class="bi bi-star-fill mark"></i>
                                <b class="forge-number"> {{faNumber(n)}} </b>
                            </span>
                        </div>
                        <p class="forge-label">{{symbol}}</p>
                        <div class="forge-part">
                            <span v-for="n in forgeList[symbol].part2" class="forge-square" :style="`--w:${100/forgeList[symbol].part1.length}%;--bg:${mounths[n-1].color}`"
                            @click="openModal(mounths[n-1])">
                                <i v-if="false" class="bi bi-fire"></i>
                                <i v-if="false" class="bi bi-mark"></i>
                                <b class="forge-number"> {{faNumber(n)}} </b>
                            </span>
                        </div>
                    </div>
                    <span class="forge-arrow"></span>
                </div>
            </div>
        </div>


        <Transition name="bounce">
            <div v-if="currentMounth" class="modal">
                <div class="modal-content">
                    <span class="close" @click="currentMounth=null">&times;</span>
                    <form class="mounth-form" @submit.prevent="updateMounth">
                        <div class="input-group">
                            <label class="label"> آجر </label>
                            <select class="input" v-model="currentMounth.brick">
                                <option value="3.6">3.6</option>
                                <option value="5.6">5.6</option>
                                <option value="L">لیفتون</option>
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
    props : ['symbols', 'forges', 'charts', 'bricks', 'counts', 'from'],
    mounted : function () {
        this.list = [...this.symbols];
        this.list.unshift('all');
    },
    data : function () {
        return {
            currentMounth : null,
            saving : false,
            list : [],
        }
    },
    computed : {
        forgeList : function () {
            var result = {};
            for (var i = 0; i < this.symbols.length; i++) {
                var symbol = this.symbols[i];
                var forgeCount = this.forges[symbol].length;
                var forgeMiddle = this.middle(forgeCount);
                result[symbol] = {};
                result[symbol].part1 = this.createArray(1, forgeMiddle);
                result[symbol].part2 = this.createArray(forgeMiddle + 1, forgeCount);
            }
            return result;
        },
        totals : function () {
            var list = this.list;
            var totals = {};
            for (var i = 0; i < list.length; i++) {
                var sum = 0;
                var item = list[i];
                for (var j = 1; j <= 4; j++) {
                    sum += Number(this.charts.all[j]);
                }
                totals[item] = sum;
            }
            return totals;
        },
    },
    methods : {
        createArray : function (min, max) {
            max++;
            return Array(max - min).fill(min).map((value, i) => value + i);
        },
        middle : function (n) {
            return n%2 == 0 ? (n/2) : (n-1)/2;
        },
        openModal : function (mounth) {
            this.currentMounth = mounth;
        },
        updateMounth : function () {
            this.saving = true;
            var url = route('mounth.update', this.currentMounth);
            axios.put(url, this.currentMounth).then(res => {
                if (res.data.success) {
                    this.swalSuccess();
                    this.currentMounth = null;
                }else {
                    this.swalError(res.data.message);
                }
            }).catch(err => this.swalGeneralErrors(err)).finally(this.saving = false);
        }
    }
}
</script>

<style lang="css">


/*** =============================================
========= *** CHARTS & STATISTICS *** ============
============================================= ***/

.overview {
    display: flex;
    justify-content: space-between;
    margin-bottom: 32px;
}

.overview > .tile {
    padding: 48px;
}

.overview > .tile:nth-child(1) {
    width: 30%;
}

.overview > .tile:nth-child(2) {
    width: 45%;
}

.overview > .tile:nth-child(3) {
    width: 20%;
}

.overview .mounth-stats {
    display: flex;
    flex-direction: column-reverse;
}

.overview progress {
    display: block;
    width: 100%;
    height: 24px;
    margin: 8px 0;
}

.overview .devider {
    border-width: 2px;
    border-color: var(--gray);
    border-style: solid;
    margin: 8px 0;
}

.overview progress.stat-1 {
    accent-color: gray;
}

.overview progress.stat-2 {
    accent-color: brown;
}

.overview progress.stat-3 {
    accent-color: orange;
}

.overview progress.stat-4 {
    accent-color: green;
}

/*** =============================================
============ *** FORGE SHAPES *** ================
============================================= ***/

.forges {
    margin: 32px;
    background-color: #013838;
    color: #fff;
    position: relative;
    padding: 1.5rem 5rem;
    border-radius: 9999px;
    border: 2px solid #0AD4D4;
}

.forges > .forge-parts {
    display: flex;
    flex-direction: column;
}

.forges > .forge-parts > .forge-label {
    display: flex;
    justify-content: center;
    border: 2px solid #0AD4D4;
    border-radius: 99px;
    font-size: 1.25rem;
    font-weight: bold;
    padding: 4px 0;
}

.forges > .forge-parts > .forge-part {
    display: flex;
    margin: 0 24px;
}

.forges > .forge-parts > .forge-part > .forge-square {
    width: var(--w);
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-left: 2px solid #0AD4D4;
    cursor: pointer;
    background-color: var(--bg);
    transition: .2s;
    position: relative;
}

.forges > .forge-parts > .forge-part > .forge-square > i {
    position: absolute;
    top: 0;
    font-size: 1.25rem;
}

.forges > .forge-parts > .forge-part > .forge-square > .fire {
    right: 5px;
}

.forges > .forge-parts > .forge-part > .forge-square > .mark {
    left: 5px;
}

.forges > .forge-parts > .forge-part > .forge-square:hover {
    transform: scale(1.25);
    border: 2px solid #0AD4D4;
    z-index: 5;
}

.forges > .forge-parts > .forge-part > .forge-square:first-child {
    border-right: 2px solid #0AD4D4;
}

/*** =============================================
============ *** FORGE ARROWS *** ==============
============================================= ***/

.forge-arrow {
    position: absolute;
    top: 2.5rem;
    width: 6rem;
    height: 6rem;
    border-radius: 15rem;
}

.forge-arrow::after {
    content: '';
    position: absolute;
    width: 1rem;
    transform: rotate( 20deg);
    background-color: #fff;
    height: .1rem;
}

.forge-arrow::before {
    content: '';
    position: absolute;
    width: 1rem;
    height: .1rem;
    transform: rotate(-50deg);
    background-color: #fff;
}

.forge-arrow:nth-of-type(1) {
    right: 1.75rem;
    border-right: 2px solid #fff;
}

.forge-arrow:nth-of-type(1)::after {
    right: 1.5rem;
    bottom: -.2rem;
}

.forge-arrow:nth-of-type(1)::before {
    bottom: .4rem;
    right: 1.6rem;
}

.forge-arrow:nth-of-type(2) {
    left: 1.75rem;
    border-left: 2px solid #fff;
}

.forge-arrow:nth-of-type(2)::after {
    left: 1.5rem;
    top: -.2rem;
}

.forge-arrow:nth-of-type(2)::before {
    left: 1.6rem;
    top: .4rem;
}



/*** =============================================
============ *** MOUNTH FORM *** ==============
============================================= ***/



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

.big-hr {
    border-width: 4px;
    border-color: var(--dashboard-dark);
    border-style: solid;
    margin-bottom: 32px;
}

</style>
