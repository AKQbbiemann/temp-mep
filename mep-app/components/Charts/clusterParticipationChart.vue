<script setup>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";
import { Colors } from "chart.js";
import { Pie } from "vue-chartjs";
import { useI18n } from "vue-i18n";
const { t } = useI18n();

const props = defineProps({
  dataValues: Array,
  dataLabels: Array,
});

const data = {
  labels: props.dataLabels,
  datasets: [
    {
      data: props.dataValues,
    },
  ],
};
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(Colors);

const options = {
  responsive: true,
  maintainAspectRatio: false,
  layout: {
    padding: -10,
  },
  plugins: {
    legend: {
      display: true,
      position: "bottom",
      align: "center",
      labels: {
        boxWidth: 15,
        boxHeight: 15,
        useBorderRadius: true,
        borderRadius: 5,
      },
    },
  },
};
</script>

<template>
  <div class="w-full h-[275px] mt-8">
    <Pie :data="data" :options="options" />
  </div>
</template>
