<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import type { HTMLAttributes } from "vue"
import { Loader2Icon } from "lucide-vue-next"
import { cn } from "@/lib/utils"

const { t } = useI18n();

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <Loader2Icon
    role="status"
    :aria-label="$t('Loading')"
    :class="cn('size-4 animate-spin', props.class)"
  />
</template>
