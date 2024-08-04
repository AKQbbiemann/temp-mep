<script setup>
import { useClustersStore } from "@/stores/clusters";
import { toRaw } from "vue";
import { format, parseISO } from "date-fns";

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
  TimeScale,
} from "chart.js";
import { Line } from "vue-chartjs";
import { useI18n } from "vue-i18n";
import { addYears } from "date-fns";

const clustersStore = useClustersStore();
const chartData = ref();
const isLoading = ref(false);
onMounted(async () => {
  await getChartData();
});
let keys = [];
let values = [];
async function getChartData() {
  try {
    isLoading.value = true;
    chartData.value = await toRaw(
      clustersStore.getFTEsChartData(props.clusterId, props.profileId)
    );
    keys = Object.keys(chartData.value);
    /*     let starDate = format(new Date(), "yyyy-MM-dd");
    labels.push(starDate);
    values.push(props.currentFTE); */
    for (let i = 0; i < keys.length; i++) {
      labels.push(keys[i]);
      values.push(Object.values(chartData.value)[i]);
    }
    const endDate = format(addYears(new Date(), 1), "yyyy-MM-dd");
    values.push(Object.values(chartData.value)[keys.length - 1]);
    console.log(values);
    labels.push(endDate);
    data.labels = labels;
    data.data = values;
    console.log(labels);

    isLoading.value = false;
  } catch {}
}
const { t } = useI18n();
const props = defineProps({
  profileId: Number | String,
  clusterId: Number | String,
});

console.log(props.clusterId);
console.log(props.profileId);
let labels = [];

// Use the generated labels in the chart
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
  TimeScale
);

// The data for our dataset
const data = ref({
  labels: labels, // Use the generated labels here
  datasets: [
    {
      label: "FTEs changes",
      fill: true,
      backgroundColor: "#E5EEEC",
      borderColor: "#05806d",
      data: values,
      tension: 0.2,
      stepped: true,
    },
  ],
});

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        drawOnChartArea: false,
      },
    },
    x: {
      grid: {
        drawOnChartArea: false,
      },
    },
  },
});
</script>
‚
<template>
  <div class="h-[300px] w-[300px]" v-if="!isLoading">
    <Line :data="data" :options="chartOptions" />
  </div>
</template>
