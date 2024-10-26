<template>
  <div class="container no-result" :class="containerClass">
    <i v-if="showLoading" class="far fa-2x fa-spinner fa-spin"></i>

    <div class="no-img-wrapper" v-if="showImage">
      <img :src="imageLink" alt="no data" class="no-result-img"/>
    </div>

    <h3 v-if="showTitle" :class="['no-result-title text-black', titleClass]">
      {{ title ? title : "No Data available" }}
    </h3>
    <p :class="subtitleClass" class="no-result-subtitle">
      {{ subtitle ? subtitle : "Please check your filter if any." }}
    </p>

    <slot name="custombtn"></slot>
    <a v-if="showBtn" :href="url" class="btn btn-blue btn-sm">{{ btnTxt }}</a>
  </div>
</template>
<script>
import {defineComponent} from "vue";

export default defineComponent({
  name: "NoDataComponent",
  props: {
    containerClass:{
      default: null,
      type: String
    },
    title: String,
    subtitle: String,
    showBtn: {
      default: false,
      type: Boolean
    },
    btnTxt: {
      default: "Click Here",
      type: String
    },
    url: {
      default: "#",
      type: String
    },
    showTitle: {
      default: true,
      type: Boolean
    },
    showLoading: {
      default: false,
      type: Boolean
    },
    showImage: {
      default: false,
      type: Boolean
    },
    imageLink: {
      default: "",
      type: String
    },
    titleClass: {
      default: "",
      type: String
    },
    subtitleClass: {
      default: "",
      type: String
    }

  },
  mounted() {

  }
})
</script>
<style lang="scss">
.no-result-img {
  //height: 200px;
  //margin-bottom: 0px;
  //margin-top: 30px;
}

.no-img-wrapper {
  //height: 100px;
  //width: 100px;
  border-radius: 50%;
  //background: #F3F7FF;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;

  img {
    height: 200px;
    //width: 100px;
  }
}

.no-result {
  /*padding-bottom: 40px;*/
}
</style>
