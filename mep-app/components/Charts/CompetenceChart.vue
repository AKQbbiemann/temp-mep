<script setup>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";
import { Pie } from "vue-chartjs";
import { useI18n } from "vue-i18n";
const { t } = useI18n();

const props = defineProps([
  "base_load",
  "comprehensive_load",
  "local_load",
  "organisation_load",
]);

ChartJS.register(ArcElement, Tooltip, Legend);
const labels = Object.keys(props).map((label) => t(label.toUpperCase()));
const values = Object.values(props);
const translatedLabels = ref();
const data = {
  labels: labels,
  datasets: [
    {
      backgroundColor: ["#05806d", "#E7324C", "#FFCC00", "#32A546"],
      data: values,
    },
  ],
};
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
  <div class="h-[300px] w-[300px]">
    <Pie :data="data" :options="options" />
  </div>
</template>
