// dashlite theme
import './css/dashlite.min.css'
import './css/skins/theme-green.css'
import '../node_modules/tom-select/dist/css/tom-select.min.css'
import '../node_modules/tom-select/dist/css/tom-select.bootstrap4.min.css'

// start stimulus app
import './js/bootstrap'

// custom elements definitions
import SpinningDots from "./js/elements/SpinningDots"
import LoaderOverlay from "./js/elements/LoaderOverlay"
import Skeleton from "./js/elements/Skeleton"
import Toast from "./js/elements/Toast";
import {InputChoices, SelectChoices} from "./js/elements/Choices";
import AutogrowTextarea from "./js/elements/AutogrowTextarea";
import DatePicker from "./js/elements/DatePicker";

// Custom Element
customElements.define('app-loader-spinning', SpinningDots)
customElements.define('app-loader-overlay', LoaderOverlay)
customElements.define('app-loader-skeleton', Skeleton)
customElements.define('app-toast', Toast)
customElements.define('app-input-choices', InputChoices, {extends: 'input'})
customElements.define('app-select-choices', SelectChoices, {extends: 'select'})
customElements.define('app-textarea-autogrow', AutogrowTextarea, {extends: 'textarea'})
customElements.define('app-datepicker', DatePicker, {extends: 'input'})

const pushPermission = document.querySelector('#push-permission');
if (pushPermission && Notification.permission === 'default') {
    pushPermission.addEventListener("click", async() => {
        await Notification.requestPermission()
    })
}
