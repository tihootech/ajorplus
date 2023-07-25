
// IMPORTING DEFAULT BOOTSTRAP BY LARAVEL
import './bootstrap';

// IMPORTING GLOBAL CSS FILES
import '../css/global.css';
import 'bootstrap-icons/font/bootstrap-icons.css';


// INITIATING INERTIA PROJECT AND LOADING INERTIA COMPONENTS
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';

// SWAL
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// IMPORTING GLOBAL COMPONENTS
import Toggler from './GlobalComponents/Toggler.vue';

// CUSTOM DIRECTIVES
const clickOutside = {
    beforeMount: (el, binding) => {
        el.clickOutsideEvent = event => {
            if (!(el == event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener("click", el.clickOutsideEvent);
    },
    unmounted: el => {
        document.removeEventListener("click", el.clickOutsideEvent);
    },
};

// GLOBAL METHODS

var redirect = function(path) {
    location.href = path;
}
var historyBack = function() {
    history.back();
}
var nl2br = function (text) {
    return (text + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br />' + '$2');
}
var shorten = function(str, n = 75) {
    return (str && str.length > n) ? str.substr(0, n - 1) + '...' : str;
}
var openRoute = function(routeName, parameters = {}) {
    this.$inertia.visit(route(routeName, parameters), {
        method: 'get'
    });
}
var openURL = function(url) {
    this.$inertia.visit(url, {
        method: 'get'
    });
}
var dTime = function(date) {
    var date = new Date(date);
    var hours = date.getHours();
    var mins = date.getMinutes();
    if (hours < 10) {
        hours = '0' + hours;
    }
    if (mins < 10) {
        mins = '0' + mins;
    }
    return hours + ':' + mins;
}
var pDate = function(date) {
    return new Date(date).toLocaleDateString('fa-IR');
}
var copyToClipboard = function(text) {
    var input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    input.select();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
}
var enNumber = function (number) {
    if (typeof number === 'string') {
        var persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        for(var i=0; i < 10; i++) {
            number = number.replace(persianNumbers[i], i);
        }
    }
    return number;
}
var faNumber = function (number) {
    const farsiDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return number ? number.toString().split('').map(x => farsiDigits[x]).join('') : '';
}
var swalSuccess = function(title = 'تغییرات با موفقیت ذخیره شد', timer = 1500) {
    if (timer) {
        this.$swal({
            icon: 'success',
            title: title,
            showConfirmButton: false,
            timer: timer
        });
    } else {
        this.$swal({
            icon: 'success',
            title: title,
            showConfirmButton: true,
        });
    }
}
var swalInfo = function(title = null, timer = 1500) {
    if (timer) {
        this.$swal({
            icon: 'info',
            title: title,
            showConfirmButton: false,
            timer: timer
        });
    } else {
        this.$swal({
            icon: 'info',
            title: title,
            showConfirmButton: true,
        });
    }
}
var swalError = function(message = 'خطایی رخ داد') {
    this.$swal({
        icon: 'error',
        title: 'خطا...',
        html: message,
    });
}
var swalValidationErrors = function(list) {
    var errorMessages = '';
    for (var key in list) {
        errorMessages += `<p>${list[key]}</p>`;
    }
    this.swalError(errorMessages);
}
var swalErrorsArray = function(array) {
    var errorMessages = '';
    for (var i = 0; i < array.length; i++) {
        errorMessages += `<p>${array[i]}</p>`;
    }
    this.swalError(errorMessages);
}
var swalDefaultRes = function(res, successMessage = null, failureMessage = null) {
    if (res.data.success) {
        if (res.data.message) {
            successMessage = res.data.message;
        }
        this.swalSuccess(successMessage);
    } else {
        if (res.data.message) {
            failureMessage = res.data.message;
        }
        if (res.data.error) {
            failureMessage = res.data.error;
        }
        this.swalError(failureMessage);
    }
};
var swalGeneralErrors = function(err) {
    if (err.response.status == 422) {
        var list = err.response.data.errors;
        this.swalValidationErrors(list);
    } else if (err.response.status == 413) {
        this.swalError('محتوای آپلود شده بسیار سنگین است!');
    } else if (err.response.status == 419) {
        this.swalError('نشست شما منقضی شده، لطفا صفحه را رفرش کنید');
    } else {
        this.swalError();
    }
}
var toggleInArray = function (arr, value) {
    var index = arr.indexOf(value);
    if (index >= 0) {
        arr.splice(index, 1);
    }else {
        arr.push(value);
    }
    return arr;
}

var methods = {
    route,
    redirect,
    historyBack,
    nl2br,
    shorten,
    openRoute,
    openURL,
    dTime,
    pDate,
    copyToClipboard,
    enNumber,
    faNumber,
    swalSuccess,
    swalInfo,
    swalError,
    swalValidationErrors,
    swalErrorsArray,
    swalDefaultRes,
    swalGeneralErrors,
    toggleInArray,
};

// CREATE INERTIA APP
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
        .mixin({
            methods: methods,
            components : { Link, Toggler }
        })
        .directive('click-outside', clickOutside)
        .use(VueSweetalert2)
        .use(plugin)
        .mount(el)
    },
})

// CUSTOMIZING INERTIA PROGRESS BAR
// import { InertiaProgress } from '@inertiajs/progress'
// InertiaProgress.init({
//   delay: 0,
//   color: '#8dc63e',
//   includeCSS: true,
//   showSpinner: true,
// })
