<script setup>
const { VueWordCloud } = useWordCloud();
const list = [
  ["Applikations-Design", 19],
  ["Service-Design", 7],
  ["Applikations-Entwicklung", 7],
  ["Datenarchitektur & -modellierung", 6],
  ["Applikations-Bereitstellung", 16],
  ["Datenintegration", 15],
  ["Forschung", 15],
  ["Visualisierungs-Design", 9],
  ["Service-Entwicklung", 10],
  ["Service-Bereitstellung", 18],
  ["Service-Bereitstellung", 27],
  ["Datenvisualisierung", 8],
  ["Frontend Entwicklung ", 26],
  ["Backend Entwicklung", 28],
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
