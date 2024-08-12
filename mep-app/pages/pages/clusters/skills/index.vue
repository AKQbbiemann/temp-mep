<script setup>
definePageMeta({
  layout: "clusters",
});

const list = [
  { name: "Applikations-Design", id: 1 },
  { name: "Service-Design", id: 2 },
  { name: "Applikations-Entwicklung", id: 3 },
  { name: "Applikations-Bereitstellung", id: 7 },
  { name: "Datenintegration", id: 4 },
  { name: "Forschung", id: 5 },
  { name: "Datenverarbeitungs-Design", id: 6 },
  { name: "Visualisierungs-Design", id: 8 },
  { name: "Service-Entwicklung", id: 9 },
  { name: "Service-Bereitstellung", id: 10 },
  { name: "Datentransporte & -integration", id: 11 },
  { name: "Datenarchitektur & -modellierung", id: 12 },
  { name: "Datenvisualisierung & Reporting", id: 13 },
  { name: "Frontend entwicklung", id: 14 },
  { name: "Backend entwicklung", id: 15 },
];
</script>

<template>
  <div class="sub-container ms-3 flex-1" id="container">
    <word-cloud />
  </div>
</template>
