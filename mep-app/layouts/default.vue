<script setup>
let isOpen = ref(false);

// Link to Google Fonts
useHead({
  title: "MEP 2024",
  htmlAttrs: {
    lang: "en",
  },
  meta: [
    { charset: "utf-8" },
    {
      name: "viewport",
      content: "width=device-width, initial-scale=1,  maximum-scale=1",
    },
    { hid: "description", name: "description", content: "" },
    { name: "format-detection", content: "telephone=no" },
  ],
  link: [
    {
      rel: "stylesheet",
      href: "https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&display=swap",
    },
  ],
});
function setIsOpen(event) {
  isOpen.value = event;
}
</script>

<template>
  <div class="mx-auto w-full flex bg-zinc-100">
    <SlideSidbar :open="isOpen" @open="setIsOpen($event)" />
    <Sidebar />
    <main class="grow">
      <AppHeader @open="setIsOpen($event)" />
      <slot />
    </main>
  </div>
</template>
