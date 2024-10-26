import {createApp} from "vue";
import NoDataComponent from "../common/js/component/NoDataComponent";


require('./../common/common')

// Register vue component
const app = createApp({});
app.component('no-result', NoDataComponent);

app.mount('#app');

