<template>
    <div class="dropdown filter-dropdown">
        <button href="#" data-bs-toggle="dropdown" class="quick-filter-item"
                :class="{'active':isValueInRequest}">
            {{ isValueInRequest && filter.selected_label ? filter.selected_label : filter.label }}
            <span v-if="isValueInRequest">{{ getComputedValue() }}</span>
            <a v-if="isValueInRequest" @click="clearFilter" class="btn filter-clear-btn">
                <i  @click="clearFilter" class="ti ti-x"></i>
            </a>
        </button>


        <ul class="dropdown-menu" :id="filter.name+'_select2_container'">
            <li v-if="filter.type==='text' ||filter.type==='email' ">
                <div class="dropdown-item active">
                    <div class="d-flex gap-1">
                        <input :type="filter.type"
                               :value="inputValue"
                               v-model="inputValue"
                               class="form-control mb-0"
                               :placeholder="filter.placeholder?filter.placeholder:'search...'">
                        <button class="btn btn-primary fs-2 px-2" type="button">
                            <i class="ti ti-circle-check-filled"></i>
                        </button>
                    </div>

                </div>
            </li>
            <template v-else-if="filter.type==='select2'">
                <li>
                    <select :id="filter.name+'_select2'">
                        <option selected v-if="filter.selectedValue &&filter.selectedValue.id"
                                :value="filter.selectedValue.id">{{ filter.selectedValue.full_name }}
                        </option>
                    </select>
                </li>
            </template>
            <template v-else>
                <li v-for="value in filter.values" :key="value.value">
                    <a :href="generateUrl(value[valueKey])"
                       class="dropdown-item"
                       :class="{'active':selectedValue.includes(value[valueKey])}">
                        <i class="ti me-1"
                           :class="{'ti-circle-check-filled':selectedValue.includes(value[valueKey]),'ti-circle':!selectedValue.includes(value[valueKey])}"></i>
                        {{ value[filter.labelKey ? filter.labelKey : "label"] }}
                    </a>
                </li>
            </template>
        </ul>
    </div>

</template>


<script setup>

import {defineProps, onMounted, ref} from "vue";

const props = defineProps({
    filter: {
        default: {},
        type: Object,
    },
    requestData: {
        default: {},
        type: Object,
    },
    baseUrl: {
        default: "",
        type: String,
        required: true
    },
});
const isValueInRequest = ref(false);
const inputValue = ref('');
const selectedValue = ref('');
const inputFields = ['text', 'email'];
const valueKey = ref(props.filter.valueKey ? props.filter.valueKey : 'value');
const labelKey = ref(props.filter.labelKey ? props.filter.labelKey : "label");

onMounted(() => {
    const filter = props.filter;
    isInRequest();

    if (filter.type === 'select2') {
        initializeSelect2(filter);
    }
});

function initializeSelect2(f) {
    const container = '#' + f.name + '_select2_container'
    const elementId = '#' + f.name + '_select2';
    $(container).on('click', function (e) {
        e.stopPropagation();
    });
    $(elementId).select2({
        theme: "bootstrap-5",
        placeholder: f.placeholder,
        allowClear: true,
        dropdownParent: $(container),
        ajax: {
            url: f.url,
            dataType: 'json',
            method: 'post',
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (response) {
                console.log(response)
                let data = [];
                if (response.success) {
                    data = response.data.map(function (item) {
                        return {
                            id: item.id,
                            text: item[f.data_label]
                        };
                    })
                }
                return {
                    results: data
                };
            },
            cache: true
        },
        // Initialize with predefined data
        initSelection: function (element, callback) {
            let data = [];
            $(element.val().split(",")).each(function () {
                data.push({id: this, text: element.find("option[value='" + this + "']").text()});
            });
            callback(data);
        }
    }).on('change', function () {
        // Get the selected value
        const selectedValue = $(this).val();
        window.location.href = generateUrl(selectedValue)
    });
    // new TomSelect(elementId, {
    //     create: false,
    //     valueField: f.data_value,
    //     labelField: f.data_label,
    //     searchField: 'keyword',
    //     preload: true,
    //     load: function(query, callback) {
    //         if (!query.length) return callback();
    //
    //         // // Fetch data via AJAX
    //         // fetch(`https://api.example.com/search?q=${encodeURIComponent(query)}`)
    //         //     .then(response => response.json())
    //         //     .then(data => {
    //         //         // Transform the response data into the format TomSelect requires
    //         //         const results = data.map(item => ({
    //         //             id: item.id,
    //         //             name: item.name
    //         //         }));
    //         //         callback(results);
    //         //     }).catch(() => {
    //         //     callback();
    //         // });
    //     }
    // });
}

function isInRequest() {
    const {requestData, filter} = props;
    isValueInRequest.value = !!requestData.hasOwnProperty(filter.name) && requestData[filter.name];
    if (isValueInRequest && requestData[filter.name]) {
        selectedValue.value = [requestData[filter.name]];
        if (filter.isMulti) {
            selectedValue.value = requestData[filter.name].split(',')
        }

        if (inputFields.includes(filter.type)) {
            inputValue.value = requestData[filter.name];
        }
    }
}

function getComputedValue() {
    const {filter} = props;
    const labelKey = filter.labelKey ? filter.labelKey : "label";
    const valueKey = filter.valueKey ? filter.valueKey : "value";

    let value = "";
    switch (filter.type) {
        case 'dropdown':
            let requestValue = isValueInRequest.value;
            if (filter.isMulti) {
                requestValue = requestValue.split(',');
            } else {
                requestValue = [requestValue];
            }
            // Now get the values from the values
            const matchedValues = filter.values.filter(i => requestValue.includes(i[valueKey])).map(i => i[labelKey]);

            value = matchedValues.slice(0, 2).join(', ');
            if (matchedValues.length > 2) {
                value += ` & ${matchedValues.length - 2}+`;
            }
            break;
        case 'select2':
            value = filter.selectedValue ? filter.selectedValue.full_name : "";
            break;
        default:
            value = isValueInRequest.value;
            break;
    }
    return value;
}

function clearFilter(e) {
    console.log(e)
    e.preventDefault();
    e.stopPropagation();
    window.location.href = generateClearUrl();
}

function generateUrl(value) {
    const {requestData, filter, baseUrl} = props;
    const {page, ...urlParams} = requestData;

    let newParams = [];
    let currentValue = requestData[filter.name] ? (filter.isMulti ? requestData[filter.name].split(',') : [requestData[filter.name]]) : [];
    if (!currentValue.includes(value)) {
        newParams = filter.isMulti ? [...currentValue, value] : [value];
    } else {
        newParams = filter.isMulti ? [...currentValue.filter(i => i !== value)] : [];
    }


    urlParams[filter.name] = newParams.join(',');

    const cleanedObject = Object.fromEntries(Object.entries(urlParams).filter(([_, v]) => v != null));

    const url = new URL(baseUrl);
    url.search = new URLSearchParams(cleanedObject).toString();
    return url.toString();
}

function generateClearUrl() {
    const {requestData, filter, baseUrl} = props;
    const {page, ...urlParams} = requestData;
    delete urlParams[filter.name];
    const url = new URL(baseUrl);
    url.search = new URLSearchParams(urlParams).toString();
    return url.toString();
}


</script>


<style scoped lang="scss">

</style>
