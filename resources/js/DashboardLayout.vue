<template>

    <Head :title="title" />

    <div class="dashboard-area">
        <aside>
            <div class="dashboard-logo">
                <!-- <img class="logo-img" src="/assets/img/logo.png" alt="AJOR-PLUS"> -->
            </div>
            <nav class="dashboard-nav">
                <Link :href="route('admin.home')" class="nav-link" :class="{active:route().current() == 'admin.home'}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    داشبورد
                </Link>
                <Link :href="route('admin.qomers')" class="nav-link" :class="{active:route().current() == 'admin.qomers'}">
                    <i class="bi bi-fire"></i>
                    قیمرها
                </Link>
                <Link :href="route('admin.users')" class="nav-link" :class="{active:route().current() == 'admin.users'}">
                    <i class="bi bi-person-fill"></i>
                    سطوح دسترسی
                </Link>
                <Link :href="route('admin.data')" class="nav-link" :class="{active:route().current() == 'admin.data'}">
                    <i class="bi bi-database-fill"></i>
                    داده‌ها
                </Link>
            </nav>
        </aside>
        <main class="main">
            <slot></slot>
        </main>
    </div>

</template>

<script>

    import { Head } from '@inertiajs/vue3'


    export default {
        props: {
            title: String,
        },
        components: {
            Head
        },
        data : function () {
            return {
                user : this.$page.props.user,
            }
        }
    }
</script>

<style>


.dashboard-area {
    height: 100vh;
    display: flex;
}

.dashboard-area main {
    overflow-y: auto;
    padding-bottom: 64px;
}

.dashboard-area > aside {
    width: 15%;
    background-color: var(--dashboard-dark);
    padding: 32px 0;
}

.dashboard-area > main {
    width: 85%;
}

.dashboard-nav {
    display: flex;
    flex-direction: column;
}

.dashboard-nav > .nav-link {
    display: block;
    color: var(--dashboard-light);
    font-size: 20px;
    padding: 10px 20px;
    transition: .3s;
    border-top-right-radius: 99px;
    border-bottom-right-radius: 99px;
    border: 2px solid transparent;
    margin: 4px 0;
}

.dashboard-nav > .nav-link:hover {
    background-color: var(--dashboard-light);
    color: var(--dashboard-dark);
    border-color: var(--dashboard-light);
}

.dashboard-nav > .nav-link.active {
    background-color: var(--primary);
    color: var(--dashboard-light);
}

.dashboard-nav > .nav-link > i {
    margin-left: 8px;
    font-size: 24px;
    position: relative;
    top: 2px;
}

.dashboard-logo {
    margin-bottom: 24px;
    text-align: center;
}

</style>
