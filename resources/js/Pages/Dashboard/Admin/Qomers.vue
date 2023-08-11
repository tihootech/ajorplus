<template>
    <dashboard-layout :title="from == 'home' ? 'داشبورد' : 'قمیرها'">

        <div class="section heading">
            <div class="tile">
                <h2 v-if="from == 'home'"> نگاه کلی </h2>
                <h2 v-else> قمیرها </h2>
                <div class="forges-navigator">
                    <Btn v-for="symbol in symbols" tag="a" class="mx-1" :href="`#section-${symbol}`"> {{symbol}} </Btn>
                </div>
            </div>
        </div>

        <div class="section">
            <div v-for="item in list" class="forge-section" :class="from == 'home' ? 'home-forges' : 'qomer-forges'" :id="`section-${item}`">
                <div v-if="from == 'home'" class="overview">
                    <div class="tile">
                        <h3 class="mb-4"> وضعیت هر دهانه <span v-if="item != 'all'"> قمیر {{item}} </span> </h3>
                        <div class="mounth-stats">
                            <div v-for="n in 4">
                                <span v-if="n == 1"> خالی </span>
                                <span v-if="n == 2"> خشت خام </span>
                                <span v-if="n == 3"> درحال‌پخت </span>
                                <span v-if="n == 4"> پخته‌شده </span>
                                <b> : {{stateStats[item][n]}} </b>
                                <div class="progress-bar" :class="`stat-${n}`">
                                    <div class="progress" :style="`width:${(stateStats[item][n] * 100) / totals[item]}%`"> {{Math.round((stateStats[item][n] * 100) / totals[item])}}% </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <p> مجموع : {{totals[item]}} </p>
                    </div>
                    <div class="tile">
                        <h3 class="mb-4"> وضعیت قالب‌ها </h3>
                        <div class="brick-results">
                            <div v-for="brickInFa, brickInEn in bricks" class="brick-result">
                                <div class="brick-info">
                                    <b> {{brickInFa}} </b>
                                    <i class="bi bi-arrow-left text-primary bigger"></i>
                                    <em> تعداد کل : </em>
                                    <b v-if="brickStats[item][brickInEn]['sum']">{{faNumber(brickStats[item][brickInEn]['sum'])}}</b>
                                    <em v-else> صفر </em>
                                </div>
                                <div class="brick-counts">
                                    <div v-for="n in brickCountsArray" class="brick-count" :style="`height:${(brickStats[item][brickInEn][n] * 25) + 50}px`">

                                        <span v-if="n == 4"> پخته‌شده </span>
                                        <span v-if="n == 3"> درحال‌پخت </span>
                                        <span v-if="n == 2"> خشت‌خام </span>

                                        <p v-if="brickStats[item][brickInEn][n]"> {{faNumber(brickStats[item][brickInEn][n])}} </p>
                                        <p v-else> <em><small>صفر</small></em> </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="item != 'all'" class="forges" :class="`forge-${item}`">
                    <span class="forge-arrow"></span>
                    <div class="forge-parts">
                        <div class="forge-part">
                            <span v-for="n in forgeList[item].part1" class="forge-square" :style="`--w:${100/forgeList[item].part1.length}%;--bg:${forges[item][n-1].color}`"
                            @click="openModal(forges[item], n-1)">
                                <i v-if="forges[item][n-1].fire" class="bi bi-fire fire"></i>
                                <i v-if="forges[item][n-1].mark" class="bi bi-star-fill mark"></i>
                                <b class="forge-number"> {{faNumber(n)}} </b>
                            </span>
                        </div>
                        <p class="forge-label"> <span>{{item}}</span> </p>
                        <div class="forge-part">
                            <span v-for="n in forgeList[item].part2" class="forge-square" :style="`--w:${100/forgeList[item].part1.length}%;--bg:${forges[item][n-1].color}`"
                            @click="openModal(forges[item], n-1)">
                                <i v-if="forges[item][n-1].fire" class="bi bi-fire fire"></i>
                                <i v-if="forges[item][n-1].mark" class="bi bi-star-fill mark"></i>
                                <b class="forge-number"> {{faNumber(n)}} </b>
                            </span>
                        </div>
                    </div>
                    <span class="forge-arrow"></span>
                </div>
                <hr class="big-hr">
            </div>
        </div>

        <MounthModal :list="currentList" :index="currentIndex" @close="closeModal" />

    </dashboard-layout>
</template>

<script>

import { defineComponent } from 'vue';
import DashboardLayout from '@/DashboardLayout.vue';
import MounthModal from './Fragments/MounthModal.vue';

// import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
// import { Pie } from 'vue-chartjs';
// ChartJS.register(ArcElement, Tooltip, Legend);

export default {
    components: { DashboardLayout, MounthModal },
    props : ['symbols', 'forges', 'stateStats', 'bricks', 'brickStats', 'from'],
    mounted : function () {
        this.list = [...this.symbols];
        this.list.unshift('all');
    },
    data : function () {
        return {
            currentList : [],
            currentIndex : -1,
            list : [],
            brickCountsArray : [4, 3, 2],
            // chartOptions: {
            //     responsive: true,
            //     maintainAspectRatio: false,
            // },
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
                    sum += Number(this.stateStats[item][j]);
                }
                totals[item] = sum;
            }
            return totals;
        },
        // chartsData : function () {
        //     var list = this.list;
        //     var result = {};
        //     for (var i = 0; i < list.length; i++) {
        //         var item = list[i];
        //         var chartData = {};
        //         var dataSets = {};
        //         chartData.labels = ['خالی', 'خشت خام', 'درحال‌پخت', 'پخته‌شده'];
        //         dataSets.backgroundColor = ['gray', 'brown', 'orange', 'green'];
        //         dataSets.data = [];
        //         for (var n = 1; n <= 4; n++) {
        //             dataSets.data.push(this.charts[item][n]);
        //         }
        //         chartData.datasets = [dataSets];
        //         result[item] = chartData;
        //     }
        //     return result;
        // }
    },
    methods : {
        createArray : function (min, max) {
            max++;
            return Array(max - min).fill(min).map((value, i) => value + i);
        },
        middle : function (n) {
            return n%2 == 0 ? (n/2) : (n-1)/2;
        },
        openModal : function (targetList, targetIndex) {
            this.currentList = targetList;
            this.currentIndex = targetIndex;
        },
        closeModal : function (newMounth) {
            if (newMounth) {
                this.currentList[this.currentIndex] = newMounth;
            }
            this.currentList = [];
            this.currentIndex = -1;
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
    flex-wrap: wrap;
}

.overview > .tile {
    padding: 48px;
    width: 100%;
    margin: 24px 0;
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


.brick-results {
    display: flex;
    flex-wrap: wrap;
}

.brick-results .brick-result {
    width: 33%;
    padding: 8px 0;
    font-size: 14px;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content:flex-start;
}

.brick-results .brick-result .brick-info {
    margin-bottom:auto;
    font-size: 20px;
}

.brick-results .brick-result .brick-counts {
    width: 100%;
    display: flex;
    align-items: flex-end;
    margin-top: 15px;
}

.brick-results .brick-result .brick-counts .brick-count {
    margin: 0 10px;
    width: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    color: #fff;
    border-radius: 8px;
}

.brick-results .brick-result .brick-counts .brick-count p {
    font-size: 20px;
}

.brick-results .brick-result .brick-counts .brick-count:nth-child(1) {
    background-color: green;
}

.brick-results .brick-result .brick-counts .brick-count:nth-child(2) {
    background-color: orange;
}

.brick-results .brick-result .brick-counts .brick-count:nth-child(3) {
    background-color: brown;
}

.brick-results .brick-result:not(:last-child) {
    border-bottom: 3px solid var(--primary);
}

.brick-results .brick-result i {
    margin: 0 4px;
    position: relative;
    top: 2px;
}

/*** =============================================
============ *** FORGE SHAPES *** ================
============================================= ***/

.forge-section.home-forges:not(:last-child):target {
    padding-top: 150px;
}

.forge-section.qomer-forges:not(:nth-last-child(-n+2)):target {
    padding-top: 150px;
}

.forge-section:target .forges {
    animation-name: changeColor;
    animation-duration: 1s;
    animation-direction: alternate;
}

@keyframes changeColor {
    from {
        background-color: #013838;
    }
    to {
        background-color: var(--primary);
    }
}

.forges {
    margin: 32px;
    background-color: #013838;
    color: #fff;
    position: relative;
    padding: 1.5rem 5rem;
    border-radius: 9999px;
    border: 2px solid var(--secondary);
    margin: 1rem 2rem;
    transition: 0.3s;
}

.forges > .forge-parts {
    display: flex;
    flex-direction: column-reverse;
}

.forges.forge-C > .forge-parts {
    flex-direction: column;
}

.forges > .forge-parts > .forge-label {
    display: flex;
    justify-content: center;
    border: 2px solid var(--secondary);
    border-radius: 99px;
    font-size: 1.25rem;
    font-weight: bold;
    padding: 8px 0;
}

.forges > .forge-parts > .forge-part {
    display: flex;
    margin: 0 24px;
}

.forges > .forge-parts > .forge-part:first-child {
    flex-direction: row-reverse;
}

.forges.forge-C > .forge-parts > .forge-part:first-child {
    flex-direction: row;
}

.forges.forge-C > .forge-parts > .forge-part:last-child {
    flex-direction: row-reverse;
}

.forges > .forge-parts > .forge-part > .forge-square {
    width: var(--w);
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-left: 2px solid var(--secondary);
    cursor: pointer;
    background-color: var(--bg);
    transition: .2s;
    position: relative;
}

.forges > .forge-parts > .forge-part > .forge-square:hover {
    transform: scale(1.25);
    border: 2px solid var(--secondary);
    z-index: 5;
}

.forges > .forge-parts > .forge-part > .forge-square:first-child {
    border-right: 2px solid var(--secondary);
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
    top: 3rem;
    width: 6rem;
    height: 6rem;
    border-radius: 15rem;
    transform: rotate(180deg) scaleX(-1);
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
    right: 2.25rem;
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
    left: 2.25rem;
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

    .section {
        padding: 24px !important;
    }

    .brick-results .brick-result {
        width: 50%;
    }

    .overview {
        flex-wrap: wrap;
        gap : 24px;
    }
    .overview > .tile {
        width: 100% !important;
    }

    .forges {
        margin: 110vw 0;
        transform: rotate(90deg) translateY(-50vw);
        width: 225vw;
        position: relative;
        left: 25vw;
    }

    .qomer-forges .forges {
        width: 250vw;
    }

    .forges > .forge-parts > .forge-part > .forge-square > b, .forges > .forge-parts > .forge-part > .forge-square > i, .forges > .forge-parts > .forge-label > span {
        transform: rotate(-90deg);
    }

    .forges > .forge-parts > .forge-part:first-child {
        flex-direction: row-reverse !important;
    }

    .forges.forge-C > .forge-parts > .forge-part:last-child {
        flex-direction: row;
    }

    .forges.forge-C .forge-arrow {
        transform: rotate(180deg) scaleX(-1) scaleY(-1);
    }

}

@media only screen and (max-width: 780px) {

    .section {
        padding: 12px !important;
    }

    .brick-results .brick-result {
        width: 100%;
    }

}


</style>
