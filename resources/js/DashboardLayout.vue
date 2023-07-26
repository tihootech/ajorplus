<template>

    <Head :title="title" />

    <div class="dashboard-area">
        <button type="button" class="hamburger-toggler" @click="asideOpen = true">
            <i class="bi bi-list"></i>
        </button>
        <aside :class={open:asideOpen}>
            <div class="dashboard-logo">
                <img class="logo-img" src="../images/logo.png" alt="AJOR-PLUS">
            </div>
            <nav class="dashboard-nav">
                <Link :href="route('dashboard')" class="nav-link" :class="{active:route().current() == 'dashboard'}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    داشبورد
                </Link>
                <Link :href="route('dashboard.qomers')" class="nav-link" :class="{active:route().current() == 'dashboard.qomers'}">
                    <i class="bi bi-fire"></i>
                    قیمرها
                </Link>
                <Link v-if="user.role == 'admin'" :href="route('dashboard.users')" class="nav-link" :class="{active:route().current() == 'dashboard.users'}">
                    <i class="bi bi-person-fill"></i>
                    سطوح دسترسی
                </Link>
                <Link :href="route('dashboard.data')" class="nav-link" :class="{active:route().current() == 'dashboard.data'}">
                    <i class="bi bi-database-fill"></i>
                    داده‌ها
                </Link>
            </nav>
            <div class="logout-section">
                <a :href="route('logout')" class="logout-link">
                    <i class="bi bi-power"></i>
                    خروج از حساب‌کاربری
                </a>
            </div>
            <i class="bi bi-x close-aside" @click="asideOpen = false"></i>
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
                asideOpen : false,
            }
        }
    }
</script>

<style>


.dashboard-area {
    height: 100vh;
    display: flex;
    position: relative;
}

.dashboard-area main {
    overflow-y: auto;
    padding-bottom: 64px;
}

.dashboard-area > aside {
    width: 15%;
    background-color: var(--dashboard-dark);
    padding: 32px 0;
    display: flex;
    flex-direction: column;
    position: relative;
}

.dashboard-area > aside > .logout-section {
    margin-top: auto;
    text-align: center;
}

.dashboard-area > aside > .logout-section > .logout-link {
    color: var(--dashboard-light);
    display: block;
    padding: 16px 0;
    transition: 0.3s;
}

.dashboard-area > aside > .logout-section > .logout-link:hover {
    background-color: var(--dashboard-light);
    color: var(--dashboard-dark);
}

.dashboard-area > aside > .logout-section > .logout-link > i {
    font-size: 1.25rem;
    margin-left: 4px;
    position: relative;
    top: 2px;
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

.close-aside {
    display: none;
    color: var(--dashboard-light);
    font-size: 3rem;
    position: absolute;
    top: 15px;
    left: 25px;
}

.close-aside:hover, .close-aside:active {
    color: var(--primary);
}

.hamburger-toggler {
    position: absolute;
    top: 90px;
    right: 10px;
    align-self: flex-start;
    z-index: 10;
    background-color: var(--dashboard-dark);
    color: var(--primary);
    font-size: 2.5rem;
    width: 67px;
    height: 67px;
    border-radius: 50%;
}

.hamburger-toggler > i {
    position: relative;
    top: 3px;
}

@media only screen and (max-width: 1080px) {
    .dashboard-area > main {
        width: 75%;
    }
    .dashboard-area > aside {
        width: 25%;
    }
}

@media only screen and (max-width: 840px) {

    .dashboard-area > main {
        width: 100%;
    }

    .dashboard-area > aside {
        display: none;
        width: 100%;
        z-index: 50;
        position: absolute;
        align-items: center;
    }

    .dashboard-area > aside.open {
        display: block;
    }

    .dashboard-nav > .nav-link {
        border-top-left-radius: 99px;
        border-bottom-left-radius: 99px;
    }

    .dashboard-nav {
        flex-direction: row;
        justify-content: center;
    }

    .close-aside {
        display: inline-block;
    }

}

@media only screen and (max-width: 620px) {
    .dashboard-nav {
        flex-wrap: wrap;
        padding: 0 24px;
    }
    .dashboard-nav .nav-link {
        width: 50%;
        text-align: center;
    }
}

</style>
