import {createApp} from "vue";
import NoDataComponent from "../common/js/component/NoDataComponent.vue";
import QuickFilters from "../common/js/component/QuickFilters.vue";

require('./../common/common')


const app = createApp({});
app.component('no-result', NoDataComponent);
app.component('quick-filters',QuickFilters);

app.mount('#app');
