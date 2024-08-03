<script setup>
import VueCal from "vue-cal";
import { onMounted } from "vue";
import useEventGenerator from "@/composables/useEventGenerator";

const { locale } = useI18n();
const { events, generateEvents } = useEventGenerator();

const calEvents = [
  {
    start: new Date(),
    end: "2024-07-27",
    dayViewPeriod: [0, 8], // between 0 and 24
    bgColor: "#d69999",
    color: "#d6d6d6",
    title: "Apple - Mobile software for new device",
    content: "",
    type: "reqirement",
  },
  {
    start: "2024-07-19",
    end: "2024-07-29",
    dayViewPeriod: [9, 17], // between 0 and 24
    bgColor: "#bcccbc",
    color: "#ffffff",
    title: "Google - Connect our API to system",
    content: "",
    type: "reqirement",
  },
  {
    start: new Date(),
    end: "2024-07-20",
    dayViewPeriod: [2, 4], // between 0 and 24
    bgColor: "#fd9c42d9",
    color: "#ffffff",
    title: "Phaze 1 - Syncronize documents with Apple",
    content: "",
    type: "phaze",
  },
  {
    start: "2024-07-21",
    end: "2024-07-24",
    dayViewPeriod: [5, 7], // between 0 and 24
    bgColor: "#ff6666d9",
    color: "#ffffff",
    title: "Phaze 2 - Start to implement API's",
    content: "",
    type: "phaze",
  },
];

onMounted(() => {
  generateEvents(calEvents);
});
</script>

<template>
  <vue-cal
    ref="myCalDiv"
    :events="events"
    today-button
    :editable-events="{
      title: false,
      drag: true,
      resize: false,
      create: true,
      delete: true,
    }"
    :disable-views="['years', 'year', 'day']"
    events-count-on-year-view
    events-on-month-view="short"
    :show-time-in-cells="false"
    active-view="week"
    :locale="locale"
  ></vue-cal>
</template>
