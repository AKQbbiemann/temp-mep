<script setup>
const { t } = useI18n();

const props = defineProps({
  row: Object,
});

const hoverLoad = ref("");

const getProgressbar = (items) => {
  return [
    {
      label: "organisation_load",
      percentage: items.organisation_load,
      color: "#32A546",
    },
    {
      label: "base_load",
      percentage: items.base_load,
      color: "#05806d",
    },
    {
      label: "local_load",
      percentage: items.local_load,
      color: "#FFCC00",
    },
    {
      label: "comprehensive_load",
      percentage: items.comprehensive_load,
      color: "#E7324C",
    },
  ];
};
</script>

<template>
  <div class="flex flex-col justify-between gap-2">
    <PercentageBar
      :items="
        getProgressbar({
          organisation_load: row['organisation_load'],
          base_load: row['base_load'],
          local_load: row['local_load'],
          comprehensive_load: row['comprehensive_load'],
        })
      "
      height="30"
      :showPercentage="false"
      :showTitle="false"
      :hoverLoad="hoverLoad"
      @mouseover="hoverLoad = $event"
      @mouseleave="hoverLoad = ''"
    />
    <div class="flex justify-between">
      <UTooltip
        :text="$t('ORGANISATION_LOAD')"
        :popper="{
          placement: 'bottom',
          offsetDistance: 4,
        }"
        :ui="{
          rounded: 'rounded-md ',
          color: 'text-white',
          background: 'bg-akq-green-ag',
        }"
      >
        <div
          class="rounded border-2 border-akq-green-ag px-2 py-1 w-min mb-1 cursor-pointer"
          @mouseover="hoverLoad = 'organisation_load'"
          @mouseleave="hoverLoad = ''"
        >
          <span class="text-akq-green-ag"
            >{{ row["organisation_load"] }} %</span
          >
        </div>
      </UTooltip>
      <UTooltip
        :text="$t('BASE_LOAD')"
        :popper="{ placement: 'bottom', offsetDistance: 4 }"
        :ui="{
          rounded: 'rounded-md ',
          color: 'text-white',
          background: 'bg-akq-green',
        }"
      >
        <div
          class="rounded border-2 border-akq-green px-2 py-1 w-min mb-1 cursor-pointer"
          @mouseover="hoverLoad = 'base_load'"
          @mouseleave="hoverLoad = ''"
        >
          <span class="text-akq-green">{{ row["base_load"] }} %</span>
        </div>
      </UTooltip>

      <UTooltip
        :text="$t('LOCAL_LOAD')"
        :popper="{ placement: 'bottom', offsetDistance: 4 }"
        :ui="{
          rounded: 'rounded-md ',
          color: 'text-white',
          background: 'bg-akq-yellow',
        }"
      >
        <div
          class="rounded border-2 border-akq-yellow px-2 py-1 w-min mb-1 cursor-pointer"
          @mouseover="hoverLoad = 'local_load'"
          @mouseleave="hoverLoad = ''"
        >
          <span class="text-akq-yellow">{{ row["local_load"] }} %</span>
        </div>
      </UTooltip>

      <UTooltip
        :text="$t('ORGANISATION_LOAD')"
        :popper="{ placement: 'bottom', offsetDistance: 4 }"
        :ui="{
          rounded: 'rounded-md ',
          color: 'text-white',
          background: 'bg-akq-red',
        }"
      >
        <div
          class="rounded border-2 border-akq-red px-2 py-1 w-min mb-1 cursor-pointer"
          @mouseover="hoverLoad = 'comprehensive_load'"
          @mouseleave="hoverLoad = ''"
        >
          <span class="text-akq-red"> {{ row["comprehensive_load"] }} % </span>
        </div>
      </UTooltip>
    </div>
  </div>
</template>
