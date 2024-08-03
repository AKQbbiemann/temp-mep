<template>
  <header class="flex justify-between items-center bg-white h-16 px-5 shadow">
    <UIcon
      name="i-heroicons-bars-3 w-8 h-8 hamburger-icon cursor-pointer"
      @click="$emit('open', true)"
    />
    <ProfileMenu />
  </header>
</template>
