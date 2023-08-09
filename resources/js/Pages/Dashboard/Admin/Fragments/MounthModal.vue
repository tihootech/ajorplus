<template>
    <Transition name="bounce">
        <div v-if="open" class="modal">
            <div class="modal-content">
                <span class="close" @click="closeModal()">&times;</span>
                <form v-if="canEdit" class="mounth-form" @submit.prevent="updateMounth">
                    <div class="input-group">
                        <label class="label"> آجر </label>
                        <select class="input" v-model="mounth.brick" @change="autoSetQuantity">
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
                        <input required type="text" class="input" v-model="mounth.name">
                    </div>
                    <div class="input-group">
                        <label class="label"> ظرفیت </label>
                        <input required type="text" class="input" v-model="mounth.quantity">
                    </div>
                    <div class="input-group">
                        <label class="label"> نماد </label>
                        <select class="input" v-model="mounth.symbol">
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                            <option>E</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="label"> وضعیت </label>
                        <select class="input" v-model="mounth.state">
                            <option value="1"> خالی </option>
                            <option value="2"> خشت خام </option>
                            <option value="3"> درحال‌پخت </option>
                            <option value="4"> پخته‌شده </option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="label"> توضیحات </label>
                        <textarea v-model="mounth.discription" class="input" rows="6"></textarea>
                    </div>
                    <div class="input-group booleans">
                        <i class="bi bi-fire bool-icon" @click="mounth.fire = !mounth.fire">
                            <i v-show="mounth.fire" class="bi bi-check-circle-fill">
                        </i>
                        </i>
                        <i class="bi bi-star-fill bool-icon" @click="mounth.mark = !mounth.mark">
                            <i v-show="mounth.mark" class="bi bi-check-circle-fill">
                        </i>
                    </i>
                    </div>
                    <div class="input-group">
                        <Btn icon="check-circle-fill" :loading="saving" type="submit" block>
                            تایید
                        </Btn>
                    </div>
                </form>
                <div v-else class="view-mounth">
                    <p> <span> نام </span> <b> : </b> <strong> {{mounth.name}} </strong> </p>
                    <p> <span> آجر </span> <b> : </b> <strong> {{mounth.brick_name}} </strong> </p>
                    <p> <span> ظرفیت </span> <b> : </b> <strong> {{mounth.quantity}} </strong> </p>
                    <p> <span> نماد </span> <b> : </b> <strong> {{mounth.symbol}} </strong> </p>
                    <p> <span> وضعیت </span> <b> : </b> <strong :style="`color:${mounth.color}`"> {{mounth.persian_state}} </strong> </p>
                    <p> <span> توضیحات </span> <b> : </b> <strong> {{mounth.discription}} </strong> </p>
                    <p class="icons">
                        <i v-if="mounth.fire" class="bi bi-fire"></i>
                        <i v-if="mounth.mark" class="bi bi-star-fill"></i>
                    </p>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    props : {
        index : { type : Number, default : -1 },
        list : { type : Array, default: ()=> [] },
    },
    data : function () {
        return {
            saving : false,
            user : this.$page.props.user,
            forceClose : false,
        }
    },
    computed : {
        open : function () {
            return this.index >= 0;
        },
        mounth : function () {
            return this.list[this.index];
        },
        canEdit : function () {
            return this.user.role == 'admin';
        },
    },
    methods : {
        closeModal : function (data=null) {
            this.$emit('close', data);
        },
        autoSetQuantity : function () {
            var quantity;
            switch (this.mounth.brick) {
                case 'T8': quantity = 13000; break;
                case 'T10': quantity = 12000; break;
                case 'T12': quantity = 8500; break;
                case 'T15': quantity = 8000; break;
                case 'FB': quantity = 4000; break;
                case 'FS': quantity = 8000; break;
                case 'LS': quantity = 26000; break;
                case 'L5': quantity = 18000; break;
                case 'L55': quantity = 18000; break;
                case 'S25': quantity = 1800; break;
                case 'S30': quantity = 1400; break;
                default: quantity = null;
            }
            this.mounth.quantity = quantity;
        },
        updateMounth : function () {
            this.saving = true;
            var url = this.mounth.id ? route('mounth.update', this.mounth) : route('mounth.store');
            var method = this.mounth.id ? 'PUT' : 'POST';
            var data = this.mounth;
            axios({url, method, data}).then(res => {
                if (res.data.success) {
                    this.swalSuccess();
                    this.closeModal(res.data.mounth);
                }else {
                    this.swalError(res.data.message);
                }
            }).catch(err => this.swalGeneralErrors(err)).finally(this.saving = false);
        }
    }
}
</script>

<style lang="css" scoped>

.modal {
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal > .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 680px;
    border-radius: 24px;
    box-shadow: var(--box-shadow);
    position: relative;
    top: 50px;
}

/* The Close Button */
.modal > .modal-content > .close {
    position: absolute;
    left: 18px;
    top: 8px;
    color: var(--gray);
    font-size: 28px;
    font-weight: bold;
    z-index: 1001;
}

.modal > .modal-content > .close:hover, .modal > .modal-content > .close:focus {
    color: var(--black);
    text-decoration: none;
    cursor: pointer;
}

@media only screen and (max-width: 720px) {
    .modal > .modal-content {
        width: 94%;
    }
}

</style>
