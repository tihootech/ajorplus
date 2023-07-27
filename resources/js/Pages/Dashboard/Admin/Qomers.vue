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
            <div v-for="item in list" class="forge-wrapper" :id="`section-${item}`">
                <div class="overview">
                    <div class="tile">
                        <h3 class="mb-4"> وضعیت هر دهانه <span v-if="item != 'all'"> قیمر {{item}} </span> </h3>
                        <div class="mounth-stats">
                            <div v-for="n in 4">
                                <span v-if="n == 1"> خالی </span>
                                <span v-if="n == 2"> خاموش </span>
                                <span v-if="n == 3"> درحال‌پخت </span>
                                <span v-if="n == 4"> پخته‌شده </span>
                                <b> : {{charts[item][n]}} </b>
                                <div class="progress-bar" :class="`stat-${n}`">
                                    <div class="progress" :style="`width:${(charts[item][n] * 100) / totals[item]}%`"> {{Math.round((charts[item][n] * 100) / totals[item])}}% </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <p> مجموع : {{totals[item]}} </p>
                    </div>
                    <div class="tile">
                        <h3 v-if="item == 'all'" class="mb-4"> وضعیت کلی قمیرها </h3>
                        <h3 v-else class="mb-4"> وضعیت کلی قمیر {{item}} </h3>
                        <div class="pie-chart-container">
                            <Pie :options="chartOptions" :data="chartsData[item]" />
                        </div>
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
                <div v-if="item != 'all'" class="forge-space"></div>
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
                        <p class="forge-label"> <span>{{item}}</span> </p>
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
            <div v-for="mounths, symbol in forges" class="forge-wrapper forge-wrapper-qomers">
                <div class="forge-space-qomers"></div>
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
                        <p class="forge-label"> <span>{{symbol}}</span> </p>
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
                <hr class="big-hr mt-5">
            </div>
        </div>


        <Transition name="bounce">
            <div v-if="currentMounth" class="modal">
                <div class="modal-content">
                    <span class="close" @click="currentMounth=null">&times;</span>
                    <form v-if="canEdit" class="mounth-form" @submit.prevent="updateMounth">
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
                    <div v-else class="view-mounth">
                        <p> <span> نام </span> <b> : </b> <strong> {{currentMounth.name}} </strong> </p>
                        <p> <span> آجر </span> <b> : </b> <strong> {{currentMounth.brick_name}} </strong> </p>
                        <p> <span> ظرفیت </span> <b> : </b> <strong> {{currentMounth.quantity}} </strong> </p>
                        <p> <span> نماد </span> <b> : </b> <strong> {{currentMounth.symbol}} </strong> </p>
                        <p> <span> وضعیت </span> <b> : </b> <strong :style="`color:${currentMounth.color}`"> {{currentMounth.persian_state}} </strong> </p>
                        <p> <span> توضیحات </span> <b> : </b> <strong> {{currentMounth.discription}} </strong> </p>
                        <p class="icons">
                            <i v-if="currentMounth.fire" class="bi bi-fire"></i>
                            <i v-if="currentMounth.mark" class="bi bi-star-fill"></i>
                        </p>
                    </div>
                </div>
            </div>
        </Transition>


    </dashboard-layout>
</template>

<script>

import { defineComponent } from 'vue';
import DashboardLayout from '@/DashboardLayout.vue';

import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Pie } from 'vue-chartjs';
ChartJS.register(ArcElement, Tooltip, Legend);

export default {
    components: { DashboardLayout, Pie },
    props : ['symbols', 'forges', 'charts', 'bricks', 'counts', 'from'],
    mounted : function () {
        this.list = [...this.symbols];
        this.list.unshift('all');
    },
    data : function () {
        return {
            user : this.$page.props.user,
            currentMounth : null,
            saving : false,
            list : [],
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
            },
        }
    },
    computed : {
        canEdit : function () {
            return this.user.role == 'admin';
        },
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
                    sum += Number(this.charts[item][j]);
                }
                totals[item] = sum;
            }
            return totals;
        },
        chartsData : function () {
            var list = this.list;
            var result = {};
            for (var i = 0; i < list.length; i++) {
                var item = list[i];
                var chartData = {};
                var dataSets = {};
                chartData.labels = ['خالی', 'خاموش', 'درحال‌پخت', 'پخته‌شده'];
                dataSets.backgroundColor = ['gray', 'brown', 'orange', 'green'];
                dataSets.data = [];
                for (var n = 1; n <= 4; n++) {
                    dataSets.data.push(this.charts[item][n]);
                }
                chartData.datasets = [dataSets];
                result[item] = chartData;
            }
            console.log(result);
            return result;
        }
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

.overview .progress-bar {
    display: block;
    width: 100%;
    height: 20px;
    margin: 8px 0;
    background-color: #f0f0f0;
    border-radius: 99px;
}

.overview .progress-bar .progress {
    border-radius: 99px;
    height: 20px;
    display: flex;
    justify-content: center;
    font-size: 14px;
    color: #fff;
    font-weight: bold;
}

.overview .progress-bar.stat-1 .progress {
    background-color: gray;
}

.overview .progress-bar.stat-2 .progress {
    background-color: brown;
}

.overview .progress-bar.stat-3 .progress {
    background-color: orange;
}

.overview .progress-bar.stat-4 .progress {
    background-color: green;
}

.overview .devider {
    border-width: 2px;
    border-color: var(--gray);
    border-style: solid;
    margin: 8px 0;
}

.pie-chart-container {
    display: flex;
}

.view-mounth p {
    margin: 8px 0;
}

.view-mounth .icons i {
    font-size: 2rem;
    margin: 0 8px;
    color: var(--primary);
}

/*** =============================================
============ *** FORGE SHAPES *** ================
============================================= ***/

.forge-wrapper {
    position: relative;
}

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

.forges > .forge-parts > .forge-part > .forge-square:hover {
    transform: scale(1.25);
    border: 2px solid #0AD4D4;
    z-index: 5;
}

.forges > .forge-parts > .forge-part > .forge-square:first-child {
    border-right: 2px solid #0AD4D4;
}

.forges > .forge-parts > .forge-part > .forge-square > i {
    position: absolute;
    top: -2.5px;
    font-size: 1.25rem;
}

.forges > .forge-parts > .forge-part > .forge-square > .fire {
    right: 2.5px;
}

.forges > .forge-parts > .forge-part > .forge-square > .mark {
    left: 2.5px;
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



@media only screen and (max-width: 1080px) {
    .overview {
        flex-wrap: wrap;
        gap : 24px;
    }
    .overview > .tile {
        width: 100% !important;
    }
    .forges {
        transform: rotate(90deg);
        width: 1000px;
        margin: 500px 0;
        position: absolute;
        right: -200px;
        top: 1150px;
    }
    .forges > .forge-parts > .forge-label > span {
        transform: rotate(-90deg);
    }
    .forges > .forge-parts > .forge-part {
        height: 67px;
        align-items: stretch;
    }
    .forges > .forge-parts > .forge-part:first-child {
        flex-direction: row-reverse;

    }
    .forges > .forge-parts > .forge-part > .forge-square > i, .forges > .forge-parts > .forge-part > .forge-square > b {
        transform: rotate(-90deg);
    }
    .forges > .forge-parts > .forge-part > .forge-square:hover {
        transform: rotate(-90deg) scale(1.25);
    }
    .forge-arrow {
        top: 3.5rem;
    }
    .forge-space {
        height: 1150px;
    }
    .forge-wrapper:not(:first-child) > .forge-space-qomers {
        height: 1150px;
    }
    .forge-wrapper-qomers.forge-wrapper:first-child .forges {
        top: 0;
    }
}

@media only screen and (max-width: 560px) {
    .forges {
        right: -250px;
    }
}

@media only screen and (max-width: 450px) {
    .forges {
        right: -300px;
    }
}

@media only screen and (max-width: 340px) {
    .forges {
        right: -350px;
    }
}

</style>
