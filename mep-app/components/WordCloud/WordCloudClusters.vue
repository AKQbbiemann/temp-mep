<script setup>
const { VueWordCloud } = useWordCloud();
const list = [
  ["Customer Care", 32],
  ["Datacenter Security", 7],
  ["Internal Service", 7],
  ["Mainframe & Print Service", 6],
  ["Service Delivery", 27],
  ["Workplace", 15],
  ["Service Design Core", 15],
  ["RZ Betrieb", 9],
  ["Silbing", 10],
];
</script>

<template>
  <div class="flex justify-center align-middle">
    <vue-word-cloud
      style="height: 600px; width: 90%"
      :words="list"
      :color="
        ([, weight]) =>
          weight > 30
            ? 'gray'
            : weight > 25
            ? ' #034D41'
            : weight > 10
            ? '#05806d'
            : 'black'
      "
      font-family="Roboto"
      :rotation="50"
    />
  </div>
</template>
