<template>
    <Component :is="tag" type="submit" class="btn" :class="theme" :disabled="loading">
        <template v-if="loading">
            <div class="loading-ring"></div>
            <span class="btn-loading-content"> لطفا صبر کنید... </span>
        </template>
        <template v-else>
            <i v-if="icon" class="bi" :class="`bi-${icon}`"></i>
             <slot></slot>
        </template>
    </Component>
</template>

<script>
export default {
    props : {
        tag : { type : String, default : 'button' },
        icon : { type : String, default : '' },
        theme : { type : String, default : 'primary' },
        loading : { type : Boolean, default : false },
    }
}
</script>

<style lang="css" scoped>

/* BUTTON ITSELF */

.btn {
    font-family: inherit;
    display: inline-block;
    height: 2.6em;
    line-height: 2.5em;
    padding: 0 24px;
    position: relative;
    overflow: hidden;
    border: 2px solid transparent;
    transition: color .5s;
    z-index: 1;
    font-size: 17px;
    border-radius: 99px;
    font-weight: 500;
    background: inherit;
}

.btn:disabled {
    cursor:not-allowed;
}

.btn .btn-loading-content {
    padding-right: 36px;
}

.btn:before {
    content: "";
    position: absolute;
    z-index: -1;
    height: 150px;
    width: 275px;
    border-radius: 50%;
}

.btn:before {
    top: 100%;
    left: 100%;
    transition: all .7s;
}

.btn:hover:before {
    top: -30px;
    left: -30px;
}

.btn:active:before {
    transition: background 0s;
}

/* BUTTON ICON */

.btn > i {
    font-size: 1.25rem;
    margin-left: 6px;
    position: relative;
    top : 2px;
}

/* COLOR THEMES */

.btn.primary {
    border-color: var(--primary);
    color: var(--primary);
}

.btn.primary:disabled {
    border-color: var(--primary-lighten-1);
    color: var(--primary-lighten-1);
}

.btn.primary:not(:disabled):before {
    background: var(--primary);
}

.btn.primary:not(:disabled):hover {
    color: #fff;
    border-color: var(--primary-darken-1);
}
.btn.primary:not(:disabled):active:before {
    background: var(--primary-darken-1);
}

/* LOADING RING */

.loading-ring {
    display: inline-block;
    position: absolute;
}

.loading-ring:after {
    content: " ";
    display: inline-block;
    width: 20px;
    height: 20px;
    margin: 8px;
    border-radius: 50%;
    border: 6px solid var(--primary);
    border-color: var(--primary) transparent var(--primary) transparent;
    animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}


</style>
