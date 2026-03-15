<script lang="ts" setup>
import { useI18n } from 'vue-i18n';
import type { HTMLAttributes } from "vue"

const { t } = useI18n();

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <nav
    :aria-label="$t('breadcrumb')"
    :data-slot="$t('breadcrumb')"
    :class="props.class"
  >
    <slot />
  </nav>
</template>
